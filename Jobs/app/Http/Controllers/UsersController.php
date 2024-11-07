<?php
namespace App\Http\Controllers;
use App\Models\posts;
use App\Models\users;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (Auth::user()->status !== 'admin') {
            return redirect()->route('home')->with('error', 'You are not authorized to view this page.');
        }
        $users = users::paginate(10);
        return view('users.index', compact('users'));
    }
    public function create()
    {
        if (Auth::user()->status !== 'admin') {
            return redirect()->route('home')->with('error', 'You are not authorized to view this page.');
        }
        return view('users.create');
    }
    public function store(Request $request)
    {
        // تحقق من صحة البيانات
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'status' => 'required|string|in:admin,employer,candidate',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:8048',
            'cv' => 'nullable|mimes:pdf|max:8048', // التحقق من ملف الـ CV
        ]);
        $user = users::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => bcrypt($request->input('password')),
            'status' => $request->input('status'),
        ]);
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $timestamp = now()->format('YmdHis');
            $filename = $timestamp . '.' . $image->getClientOriginalExtension();
            $image->storeAs('public/profile_images', $filename);
            $user->image = $filename;
        }
        if ($request->hasFile('cv')) {
            $cv = $request->file('cv');
            $cvFilename = now()->format('YmdHis') . '.' . $cv->getClientOriginalExtension();
            $cv->storeAs('public/cvs', $cvFilename);
            $user->cv = $cvFilename;
        }
        $user->save();
        return redirect()->route('users.index')->with('success', 'User added successfully.');
    }
    public function show(users $user)
    {
        // if (Auth::user()->status !== 'admin' && Auth::user()->status !== 'candidate' && Auth::user()->id !== $user->id) {
        //     return redirect()->route('users.index')->with('error', 'You are not authorized to view this profile.');
        // }
        $posts = $user->posts;
        return view('users.show', compact('user', 'posts'));
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(users $user)
    {
        // تحقق إذا كان المستخدم المسجل هو الأدمن أو صاحب الحساب
        if (Auth::user()->status !== 'admin' && Auth::user()->id !== $user->id) {
            return redirect()->route('users.show', $user->id)->with('error', 'You are not authorized to edit this profile.');
        }

        // عرض صفحة التعديل
        return view('users.edit', compact('user'));
    }

    
    public function update(Request $request, users $user)
{
    // Check if the authenticated user is an admin or the owner of the profile
    if (Auth::user()->status !== 'admin' && Auth::user()->id !== $user->id) {
        return redirect()->route('users.show', $user->id)->with('error', 'You are not authorized to update this profile.');
    }

    // Base validation for name, email, password, image, and CV
    $rules = [
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
        'password' => 'nullable|string|min:8|confirmed',
        'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:8048',
        'cv' => 'nullable|mimes:pdf|max:8048',
    ];

    // If the authenticated user is an admin, add validation for 'status'
    if (Auth::user()->status === 'admin') {
        $rules['status'] = 'required|string|in:admin,employer,candidate';
    }

    // Validate the request
    $request->validate($rules);

    // Update user fields
    $user->name = $request->input('name');
    $user->email = $request->input('email');

    // Update the status only if the authenticated user is an admin
    if (Auth::user()->status === 'admin') {
        $user->status = $request->input('status');
    }

    // Update the password if it is provided
    if ($request->filled('password')) {
        $user->password = bcrypt($request->input('password'));
    }

    // Update the profile image if it is uploaded
    if ($request->hasFile('image')) {
        if ($user->image) {
            Storage::delete('public/profile_images/' . $user->image);
        }
        $image = $request->file('image');
        $filename = now()->format('YmdHis') . '.' . $image->getClientOriginalExtension();
        $image->storeAs('public/profile_images', $filename);
        $user->image = $filename;
    }

    // Update the CV file if it is uploaded
    if ($request->hasFile('cv')) {
        if ($user->cv) {
            Storage::delete('public/cvs/' . $user->cv);
        }
        $cv = $request->file('cv');
        $cvFilename = now()->format('YmdHis') . '.' . $cv->getClientOriginalExtension();
        $cv->storeAs('public/cvs', $cvFilename);
        $user->cv = $cvFilename;
    }

    // Save the updated user data
    $user->save();

    return redirect()->route('users.show', $user->id)->with('success', 'User updated successfully.');
}


    public function destroy(users $user)
    {
        if ($user->image) {
            Storage::delete('public/profile_images/' . $user->image);
        }
        if ($user->cv) {
            Storage::delete('public/cvs/' . $user->cv);
        }
        $user->posts()->each(function($post) {
            // حذف الصور المرتبطة بالبوست إذا كانت موجودة
            if ($post->image) {
                Storage::disk('public')->delete($post->image); // تأكد من إضافة / بين المجلدات
            }
            // حذف البوست
            $post->delete();
        });
        // حذف المستخدم المحدد
        $user->delete();
        // إعادة التوجيه إلى صفحة الفهرس مع رسالة نجاح
        return redirect()->route('users.index')->with('success', 'User deleted successfully.');
    }
    public function hasLiked(posts $post)
{
    return $this->likes()->where('post_id', $post->id)->exists();
}

}
