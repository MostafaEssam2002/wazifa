<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostsController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\EmailController;
use App\Http\Controllers\CommentController;

// الصفحة الرئيسية وصفحات ثابتة
Route::get('/', function () { return view('welcome'); })->name('home');
Route::get('/about', function () { return view('about'); })->name('about');
Route::get('/privacy', function () { return view('privacy'); })->name('privacy');

// مصادقة المستخدمين
Auth::routes();
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// مسارات البريد الإلكتروني
Route::get('/send-email/{email}', [EmailController::class, 'showForm'])->name('send.email.form');
Route::post('/send-email', [EmailController::class, 'sendEmail'])->name('send.email');

// مسارات البوستات (Posts) مع حماية المصادقة
Route::middleware('auth')->group(function () {
    Route::resource('posts', PostsController::class);
    Route::post('/posts/{id}/approve', [PostsController::class, 'approve'])->name('posts.approve');
    Route::post('/posts/{id}/reject', [PostsController::class, 'reject'])->name('posts.reject');
    Route::post('/posts/{id}/like', [PostsController::class, 'like'])->name('posts.like');
    Route::post('/posts/{id}/comment', [PostsController::class, 'comment'])->name('posts.comment');
});
// مسارات المستخدمين مع حماية المصادقة
Route::middleware('auth')->group(function () {
    // فقط الأدمن أو صاحب البروفايل يمكنهم مشاهدة أو تعديل المستخدمين
    Route::resource('users', UsersController::class)->middleware('can:view,user');
});
Route::get('users/{user}', [UsersController::class, 'show'])->name('users.show');
Route::middleware('auth')->group(function () {
    Route::resource('users', UsersController::class)->except('show');
});


// مسارات الرسائل
Route::middleware('auth')->group(function () {
    Route::resource('messages', MessageController::class)->except(['edit', 'update']);
    Route::post('messages/{messageId}/reply', [MessageController::class, 'reply'])->name('messages.reply');
    Route::get('/messages/unreadCount', [MessageController::class, 'unreadCount']);
});

// مسارات الإشعارات (Notifications)
Route::middleware('auth')->group(function () {
    Route::delete('/notifications/{id}', [NotificationController::class, 'destroy'])->name('notifications.destroy');
    Route::put('/notifications/markAsRead/{id}', [NotificationController::class, 'markAsRead'])->name('notifications.markAsRead');
});

// Route for search
Route::get('/search', [PostsController::class, 'search']);

// Route for unliking a post
Route::post('/posts/{id}/unlike', [PostsController::class, 'unlike'])->name('posts.unlike');
Route::post('/posts/{id}/comment', [PostsController::class, 'comment'])->name('posts.comment');
Route::post('posts/{id}/comment', [PostsController::class, 'comment'])->name('posts.storeComment');
Route::post('/posts/{id}/like', [PostsController::class, 'like'])->name('posts.like');
// Route::post('/posts/{id}/like', [PostsController::class, 'like'])->name('posts.like');
Route::resource('comments', CommentController::class);




// routes/web.php
Route::get('/messages/{id}/fetch', [MessageController::class, 'fetchMessages'])->name('messages.fetch');
