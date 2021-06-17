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
    Route::get('/posts/create', [PostController::class, 'create'])->name('post_create');
    Route::post('/posts', [PostController::class, 'store'])->name('post_store');

    // Edit Post
    Route::get('/posts/{post_id}/edit', [PostController::class, 'edit'])->name('post_edit');
    Route::post('/posts/{post_id}', [PostController::class, 'update'])->name('post_update');
});

Route::get('/posts', [PostController::class, 'index'])->name('posts');
Route::get('/posts/user/{user_id}', [PostController::class, 'userPosts'])->name('posts_user');
Route::get('/posts/{id}', [PostController::class, 'show'])->name('post');