<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use App\Models\Like;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        $comments = Comment::where(['user_id' => $user->id, 'post_id' => $post->id])->orderBy('created_at', 'DESC')->paginate(10);
        return view('posts.show', [
            'post' => $post,
            'user' => $user,
            'liked' => $liked,
            'comments' => $comments
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
            $state = true;
        }
        else {
            $like = Like::firstWhere(['user_id' => $user->id, 'post_id' => $post->id]);
            $like->delete();
            $post->likes -= 1;
            $post->save();
            $state = false;
        }

        return response()->json([
            'liked' => $state,
            'likeCount' => $post->likes
        ], 200);
    }

    public function comment(Request $request) {
        $post = Post::find($request->id);
        $user = Auth::user();

        $comment = new Comment;
        $comment->post_id = $post->id;
        $comment->user_id = $user->id;
        $comment->body = $request->body;
        $comment->save();

        return response()->json([
            'commentBody' => $comment->body,
            'commentDate' => $comment->created_at->diffForHumans(),
            'userName' => $user->name
        ], 200);
    }
}
