<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\ApiPostsController;
use App\Http\Controllers\Api\PostController;

Route::get('/posts', [PostController::class, 'index']);

Route::get('/api/posts', [ApiPostsController::class, 'index']);

// مسارات الـ API للبوستات
Route::middleware('auth:api')->group(function () {
    Route::get('/posts/search', [ApiPostsController::class, 'search']);
    Route::post('/posts', [ApiPostsController::class, 'store']);
    Route::put('/posts/{id}', [ApiPostsController::class, 'update']);
    Route::delete('/posts/{id}', [ApiPostsController::class, 'destroy']);
    Route::post('/posts/{id}/like', [ApiPostsController::class, 'like']);
    Route::post('/posts/{id}/comment', [ApiPostsController::class, 'comment']);
});

Route::get('/posts', [ApiPostsController::class, 'index']);
Route::get('/posts/{id}', [ApiPostsController::class, 'show']);


// مسارات المستخدمين العامة
Route::get('/users', [UserController::class, 'index']);
Route::get('/users/{id}', [UserController::class, 'show']);
Route::post('/users', [UserController::class, 'store']);
Route::put('/users/{id}', [UserController::class, 'update']);
Route::delete('/users/{id}', [UserController::class, 'destroy']);
