<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\UserController;


Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::group(['middleware' => ['auth']], function() {
    // Create Post
    Route::get('/posts/create', [PostController::class, 'create'])->name('post.create');
    Route::post('/posts', [PostController::class, 'store'])->name('post.store');

    // Read Post
    Route::get('/posts/{id}', [PostController::class, 'show'])->name('post');

    // Update Post
    Route::get('/posts/{post_id}/edit', [PostController::class, 'edit'])->name('post.edit');
    Route::post('/posts/{post_id}', [PostController::class, 'update'])->name('post.update');

    // Destroy Post
    Route::post('/posts/{post_id}/destroy', [PostController::class, 'destroy'])->name('post.destroy');

    // Like Post
    Route::post('/posts/{post_id}/like', [LikeController::class, 'like'])->name('post.like');

    // Comment on Post
    Route::post('/posts/{post_id}/comment', [CommentController::class, 'comment'])->name('post.comment');
    
    // Fetch Comments
    Route::post('/posts/{post_id}/comments-fetch/{page}', [CommentController::class, 'commentsFetch'])->name('post.comments.fetch');

    // Update User Avatar
    Route::post('/users/{user_id}/avatar', [UserController::class, 'updateAvatar'])->name('user.avatar.update');

    // Updatae User Bio
    Route::post('/users/{user_id}/bio', [UserController::class, 'updateBio'])->name('user.bio.update');
});

Route::get('/posts', [PostController::class, 'index'])->name('posts');
Route::get('/users/{user_id}', [UserController::class, 'profile'])->name('user.profile');
