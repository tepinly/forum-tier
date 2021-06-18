<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Post;
use App\Models\User;
use App\Models\Like;

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
        return Post::get();
    }

    public function userPosts($user_id) {
        if(!User::where('id', $user_id)->exists()) {
            abort(404, 'User does not exist');
        }
        return Post::where('user_id', $user_id)->get();
    }

    public function show($id) {
        $post = Post::find($id);
        $user = Auth::user();
        $liked = Like::firstWhere(['user_id' => $user->id, 'post_id' => $post->id]) ? true : false;
        return view('posts.show', [
            'post' => $post,
            'user' => $user,
            'liked' => $liked
        ]);
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

    public function like(Request $request) {
        $post = Post::find($request->id);
        $user = Auth::user();
        if(!Like::firstWhere(['user_id' => $user->id, 'post_id' => $post->id])) {
            $like = new Like;
            $like->post_id = $post->id;
            $like->user_id = $user->id;
            $like->save();
            $post->likes += 1;
            $post->save();

            return response()->json([
                'liked' => true
            ], 200);
        }

        $like = Like::firstWhere(['user_id' => $user->id, 'post_id' => $post->id]);
        $like->delete();
        $post->likes -= 1;
        $post->save();

        return response()->json([
            'liked' => false
        ], 200);
    }
}
