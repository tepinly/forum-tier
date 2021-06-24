<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use App\Models\Like;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class PostController extends Controller
{
    public function create() {
        return view('posts.create');
    }

    public function store(Request $request) {
        $post = new Post;
        $post->user_id = Auth::user()->id;
        $post->title = $request->title;
        $post->body = $request->body;
        $post->save();

        return redirect()->route('posts');
    }

    public function index() {
        $posts = Post::orderBy('created_at', 'DESC')->paginate(10);

        return view('posts.index', compact($posts));
    }

    public function show($id) {
        $post = Post::find($id);
        $user = Auth::user();
        $liked = Like::firstWhere(['user_id' => $user->id, 'post_id' => $post->id]) ? true : false;
        $comments = Comment::where(['post_id' => $post->id])->orderBy('created_at', 'DESC')->paginate(5);
        $access = hasAccess($user->id);

        return view('posts.show', compact('post', 'user', 'liked', 'comments', 'access'));
    }

    public function edit($id) {
        return view('posts.edit', [
            'post' => Post::find($id)
        ]);
    }

    public function update(Request $request) {
        $post = Post::find($request->id);
        $post->body = $request->body;
        $post->save();

        return response()->json([
            'body' => $post->body,
            'user' => Auth::user()
        ], 200);
    }

    public function destroy(Request $request) {
        $post = Post::find($request->id);
        $post->delete();

        return response()->json([
            'body' => 'Thread deleted.'
        ], 200);
    }
}
