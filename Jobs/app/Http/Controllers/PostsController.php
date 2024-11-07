<?php
namespace App\Http\Controllers;
use App\Models\Posts;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use App\Notifications\NewPostNotification;
use App\Models\User;
// use App\Models\Post;
use App\Models\Like;
use App\Notifications\PostInteractionNotification;
// use App\Models\Post;
use App\Models\PostCategory;
use function Laravel\Prompts\search;

// use Illuminate\Http\Request;
// use App\Models\Post;
class PostsController extends Controller
{
    public function index(Request $request)
    {
        $categories = PostCategory::all();
        $selectedCategories = $request->input('category');
        $query = Posts::with('category');    
        if ($selectedCategories) {
            $query->whereIn('category_id', $selectedCategories);
        }
        $query->orderBy('created_at', 'desc');
        $posts = $query->paginate(10);
        return view('posts.index', compact('posts', 'categories'));
    }
    
    public function search(Request $request)
{
    $search = $request->query('query');
    $posts = Posts::where(function ($query) use ($search) {
        $query->where('title', 'like', "%$search%")
            ->orWhere('content', 'like', "%$search%")
            ->orWhereHas('user', function ($userQuery) use ($search) {
            $userQuery->where('name', 'like', "%$search%");
            });
    })->paginate(10);
    return view('posts.search', compact('posts', 'search'));
}
    /**
     * Show the form for creating a new resource.
    */
    public function show($id)
{
    $post = Posts::findOrFail($id);
    return view('posts.show', compact('post'));
}
    public function create()
    {
        if (Auth::user()->status != "admin" && Auth::user()->status != "employer") {
            return redirect()->route('posts.index')->with('error', 'Unauthorized access.');
        }
        return view('posts.create'); 
    }
    public function store(Request $request)
    {
        if (Auth::user()->status != "admin" && Auth::user()->status != "employer") {
            return redirect()->route('posts.index')->with('error', 'Unauthorized access.');
        }
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'category_id' => 'required|exists:post_categories,id', // التحقق من وجود الفئة الصحيحة
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:12288',
        ]);
        $postData = $request->all();
        $postData['user_id'] = Auth::id();
        $postData['status'] = (Auth::user()->status == "admin") ? 'approved' : 'pending';
        // Handle image upload
        if ($request->hasFile('image')) {
            // استخدام التاريخ والوقت فقط كاسم للصورة
            $imageName = now()->format('YmdHis') . '.' . $request->file('image')->getClientOriginalExtension();
            $imagePath = $request->file('image')->storeAs('images/posts', $imageName, 'public');
            $postData['image'] = $imagePath;
        }        
        try {
            $post = Posts::create($postData);
        } catch (\Exception $e) {
            return redirect()->route('posts.index')->with('error', 'Failed to create post: ' . $e->getMessage());
        }
        if (Auth::user()->status == "employer") {
            $admins = User::where('status', 'admin')->get();
            foreach ($admins as $admin) {
                $admin->notify(new NewPostNotification($post));
            }
        }
        return redirect()->route('posts.index')->with('success', 'The post has been created successfully and is awaiting approval.');
    }
    public function edit($id)
    {
        $post = Posts::findOrFail($id);
        // تحقق إذا كان المستخدم هو صاحب البوست أو المسؤول
        if (Auth::id() != $post->user_id && Auth::user()->status != "admin") {
            return redirect()->route('posts.index')->with('error', 'Unauthorized access.');
        }
        return view('posts.edit', compact('post'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $post = Posts::findOrFail($id);
    
        // تحقق من إذن الوصول
        if (Auth::id() != $post->user_id && Auth::user()->status != "admin") {
            return redirect()->route('posts.index')->with('error', 'Unauthorized access.');
        }
    
        // التحقق من صحة البيانات المدخلة
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'category_id' => 'required|exists:post_categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:12288',
            'pdf' => 'nullable|file|mimes:pdf|max:10240',
        ]);
    
        // تحديث القيم
        $post->title = $request->input('title');
        $post->content = $request->input('content');
        $post->category_id = $request->input('category_id');
    
        // معالجة تحميل الصورة
        if ($request->hasFile('image')) {
            // حذف الصورة القديمة إذا كانت موجودة
            if ($post->image) {
                Storage::disk('public')->delete($post->image);
            }
            // استخدام التاريخ والوقت فقط كاسم للصورة
            $imageName = now()->format('YmdHis') . '.' . $request->file('image')->getClientOriginalExtension();
            $imagePath = $request->file('image')->storeAs('images/posts', $imageName, 'public');
            $post->image = $imagePath; // تعيين المسار الجديد للصورة
        }
    
        // حفظ التغييرات في قاعدة البيانات
        $post->save();
    
        return redirect()->route('posts.index')->with('success', 'Post updated successfully.');
    }
    

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $post = Posts::findOrFail($id);
        if (Auth::id() == $post->user_id || Auth::user()->status == "admin") {
            if ($post->image) {
                Storage::disk('public')->delete($post->image);
            }
            $post->delete();
            return redirect()->route('posts.index')->with('success', 'Post deleted successfully.');
        } else {
            return redirect()->route('posts.index')->with('error', 'Unauthorized action.');
        }
    }
    /**
     * Approve or reject a post.
     */
    public function approve($id, Request $request)
    {
        $post = Posts::findOrFail($id);
        if (Auth::user()->status != "admin") {
            abort(403, 'Unauthorized action.');
        }
        $action = $request->input('action');
        if ($action == 'approve') {
            $post->status = 'approved';
            $message = 'Post approved successfully.';
        } elseif ($action == 'reject') {
            $post->status = 'rejected';
            $message = 'Post rejected successfully.';
        } else {
            return redirect()->route('posts.index')->with('error', 'Invalid action.');
        }
        $post->save();
        return redirect()->route('posts.index')->with('success', $message);
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
        return redirect()->back()->with('message', 'Comment added successfully!');
    }
    public function reject($id)
    {
        $post = Posts::findOrFail($id);
        // تحقق مما إذا كان المستخدم هو المسؤول
        if (Auth::user()->status != "admin") {
            return redirect()->route('posts.index')->with('error', 'Unauthorized access.');
        }
        // حذف البوست
        $post->delete();
        return redirect()->route('posts.index')->with('success', 'Post has been rejected and deleted successfully.');
    }

}
