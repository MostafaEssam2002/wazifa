<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\Posts;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use App\Notifications\NewPostNotification;
use App\Models\User;
use App\Models\Like;
use App\Notifications\PostInteractionNotification;
use App\Models\PostCategory;

class ApiPostsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api')->except(['index', 'search', 'show']);
    }

    public function index(Request $request)
    {
        return response()->json(Posts::all());
        // $categories = PostCategory::all();
        // $query = Posts::query();

        // if ($request->has('category') && is_array($request->input('category'))) {
        //     $selectedCategories = $request->input('category');
        //     if (count($selectedCategories) > 0) {
        //         $query->whereHas('categories', function ($q) use ($selectedCategories) {
        //             $q->whereIn('post_categories.id', $selectedCategories);
        //         });
        //     }
        // }

        // $posts = $query->paginate(10);

        // // تحقق مما إذا كانت النتائج فارغة
        // if ($posts->isEmpty()) {
        //     return response()->json(['message' => 'No posts available'], 200);
        // }

        // return response()->json($posts);
    }


    public function search(Request $request)
    {
        $search = $request->query('query');
        $posts = Posts::where('title', 'like', "%$search%")
                    ->orWhere('content', 'like', "%$search%")
                    ->orWhereHas('user', function ($userQuery) use ($search) {
                        $userQuery->where('name', 'like', "%$search%");
                    })->paginate(10);
        return response()->json($posts);
    }

    public function show($id)
    {
        $post = Posts::findOrFail($id);
        // Check if the request is an API request
        if (request()->is('api/*')) {
            return response()->json($post);
        }
        // For web requests, return the normal view
        return view('posts.show', compact('post'));
    }
    
    public function store(Request $request)
    {
        if (Auth::user()->status != "admin" && Auth::user()->status != "employer") {
            return response()->json(['error' => 'Unauthorized access.'], 403);
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'postcategory_id' => 'required|exists:post_categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'pdf' => 'nullable|file|mimes:pdf|max:10240',
        ]);

        $postData = $request->all();
        $postData['user_id'] = Auth::id();
        $postData['status'] = 'pending';

        if ($request->hasFile('image')) {
            $imageName = now()->format('YmdHis') . '_' . $request->file('image')->getClientOriginalName();
            $imagePath = $request->file('image')->storeAs('images/posts', $imageName, 'public');
            $postData['image'] = $imagePath;
        }

        if ($request->hasFile('pdf')) {
            $pdfName = now()->format('YmdHis') . '_' . $request->file('pdf')->getClientOriginalName();
            $pdfPath = $request->file('pdf')->storeAs('pdfs/posts', $pdfName, 'public');
            $postData['pdf'] = $pdfPath;
        }

        try {
            $post = Posts::create($postData);
            return response()->json(['message' => 'Post created successfully.', 'post' => $post], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to create post: ' . $e->getMessage()], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $post = Posts::findOrFail($id);

        if (Auth::id() != $post->user_id && Auth::user()->status != "admin") {
            return response()->json(['error' => 'Unauthorized access.'], 403);
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'pdf' => 'nullable|file|mimes:pdf|max:10240',
        ]);

        $post->title = $request->input('title');
        $post->content = $request->input('content');

        if ($request->hasFile('image')) {
            if ($post->image) {
                Storage::disk('public')->delete($post->image);
            }
            $imageName = now()->format('YmdHis') . '_' . $request->file('image')->getClientOriginalName();
            $imagePath = $request->file('image')->storeAs('images/posts', $imageName, 'public');
            $post->image = $imagePath;
        }

        if ($request->hasFile('pdf')) {
            if ($post->pdf) {
                Storage::disk('public')->delete($post->pdf);
            }
            $pdfName = now()->format('YmdHis') . '_' . $request->file('pdf')->getClientOriginalName();
            $pdfPath = $request->file('pdf')->storeAs('pdfs/posts', $pdfName, 'public');
            $post->pdf = $pdfPath;
        }

        $post->save();
        return response()->json(['message' => 'Post updated successfully.']);
    }

    public function destroy($id)
    {
        $post = Posts::findOrFail($id);
        if (Auth::id() == $post->user_id || Auth::user()->status == "admin") {
            if ($post->image) {
                Storage::disk('public')->delete($post->image);
            }
            $post->delete();
            return response()->json(['message' => 'Post deleted successfully.']);
        } else {
            return response()->json(['error' => 'Unauthorized action.'], 403);
        }
    }

    public function like($id)
    {
        $post = Posts::findOrFail($id);
        $userId = Auth::id();
        $userName = Auth::user()->name;
        $like = $post->likes()->where('user_id', $userId)->first();

        if (!$like) {
            $post->likes()->create(['user_id' => $userId]);
            $message = 'تم تسجيل الإعجاب بنجاح!';
            $liked = true;
            $postOwner = $post->user;

            if ($postOwner && $postOwner->id != $userId) {
                $postOwner->notify(new PostInteractionNotification($post, 'like', $userName));
            }
        } else {
            $like->delete();
            $message = 'تم إلغاء الإعجاب بنجاح!';
            $liked = false;
        }

        return response()->json(['message' => $message, 'liked' => $liked]);
    }

    public function comment(Request $request, $id)
    {
        $request->validate([
            'content' => 'required|string|max:255',
            'file' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx|max:2048',
        ]);

        $post = Posts::findOrFail($id);
        $userName = Auth::user()->name;
        $commentData = [
            'user_id' => Auth::id(),
            'content' => $request->input('content'),
        ];

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $fileName = now()->format('YmdHis') . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('files/comments', $fileName, 'public');
            $commentData['file'] = $filePath;
        }

        $comment = $post->comments()->create($commentData);
        $postOwner = $post->user;

        if ($postOwner && $postOwner->id != Auth::id()) {
            $postOwner->notify(new PostInteractionNotification($post, 'comment', $userName));
        }

        return response()->json(['message' => 'Comment added successfully!', 'comment' => $comment], 201);
    }
}
