<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

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
    Route::post('/posts/{post_id}/like', [PostController::class, 'like'])->name('post.like');

    // Comment on Post
    Route::post('/posts/{post_id}/comment', [PostController::class, 'comment'])->name('post.comment');
});

Route::get('/posts', [PostController::class, 'index'])->name('posts');
Route::get('/posts/user/{user_id}', [PostController::class, 'userPosts'])->name('posts.user');