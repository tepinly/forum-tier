<?php

use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\FriendController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;

// Email Verification
Auth::routes(['verify' => true]);

Route::get('/email/verify', function () {
    return view('auth.verify');
})->middleware('auth')->name('verification.notice');

Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();

    return redirect('/home');
})->middleware(['auth', 'signed'])->name('verification.verify');

Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();

    return back()->with('message', 'Verification link sent!');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');


Route::group(['middleware' => ['auth', 'verified']], function () {
    // Create Post
    Route::get('/posts/create', [PostController::class, 'create'])->name('post.create');
    Route::post('/posts', [PostController::class, 'store'])->name('post.store');
    
    // Read Post
    Route::get('/posts/{id}', [PostController::class, 'show'])->name('post.show');
    
    // Update Post
    Route::get('/posts/{post_id}/edit', [PostController::class, 'edit'])->name('post.edit');
    Route::post('/posts/{post_id}', [PostController::class, 'update'])->name('post.update');
    
    // Destroy Post
    Route::post('/posts/{post_id}/destroy', [PostController::class, 'destroy'])->name('post.destroy');
    
    // Like Post
    Route::post('/posts/{post_id}/like', [LikeController::class, 'like'])->name('post.like');
    
    
    // Comment on Post
    Route::post('/posts/{post_id}/comment', [CommentController::class, 'create'])->name('post.comment');
    
    // Delete Comment
    Route::post('/posts/{post_id}/comments/{comment_id}', [CommentController::class, 'delete'])->name('delete.comment');
    
    // Fetch Comments Pagination
    Route::post('/posts/{post_id}/comments-fetch/{page}', [CommentController::class, 'fetch'])->name('post.comments.fetch');


    // User Profile
    Route::get('/users/{user_id}', [UserController::class, 'profile'])->name('user.profile');
    
    // Update User Avatar
    Route::post('/users/{user_id}/avatar', [UserController::class, 'updateAvatar'])->name('user.avatar.update');
    
    // Update User Bio
    Route::post('/users/{user_id}/bio', [UserController::class, 'updateBio'])->name('user.bio.update');
    
    // Unfollow User
    Route::post('/users/{user_id}/unfollow', [FriendController::class, 'unfollow'])->name('user.unfollow');
    
    // Follow User
    Route::post('/users/{user_id}/follow', [FriendController::class, 'follow'])->name('user.follow');
});

// Admin routes
Route::group(['middleware' => ['auth', 'verified', 'admin']], function () {
    Route::get('/admin', [AdminController::class, 'controlPanel'])->name('admin.control.panel');
    Route::post('/users/{user_id}/delete', [UserController::class, 'delete'])->name('user.delete');
});

// Home page
Route::get('/posts', [PostController::class, 'index'])->name('posts');
Route::get('/home', [PostController::class, 'index']);
Route::get('/', [PostController::class, 'index']);

// Fetch Post Pagination
Route::post('/posts/fetch/{page}', [PostController::class, 'postsFetch'])->name('posts.fetch');

// User Followers Index
Route::get('/users/{user_id}/followers', [FriendController::class, 'followers'])->name('user.followers');

// User Following Index
Route::get('/users/{user_id}/following', [FriendController::class, 'following'])->name('user.following');