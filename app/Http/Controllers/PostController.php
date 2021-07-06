<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Like;
use App\Models\Comment;
use App\Models\User;
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
        $posts = Post::orderBy('created_at', 'DESC')->paginate(5);

        return view('posts.index', compact('posts'));
    }
    
    public function show($id) {
        $post = Post::find($id);
        if ($post === null) return abort(404, 'Post doesn\'t exist');

        $user = User::firstWhere('id', $post->user->id);
        $liked = Like::firstWhere(['user_id' => $user->id, 'post_id' => $post->id]) ? true : false;
        $comments = Comment::where(['post_id' => $post->id])->orderBy('created_at', 'DESC')->paginate(5);
        $access =accessLevel($user->id, $post);

        return view('posts.show', compact('post', 'user', 'liked', 'comments', 'access'));
    }

    public function postsFetch($page) {
        $posts = Post::orderBy('created_at', 'DESC')->paginate(5, ['*'], 'page', $page);
        if (count($posts) == 0) return response()->json(['loadedPosts' => ''], 200);

        $postList = '';

        foreach($posts as $post) {
            $postList .= '<div class="post">
            <div class="post">
                <h5>' . $post->title . '</h5>
                <p>
                    ' . $post->user->name . '<br>' . $post->created_at->diffForHumans() . ' | ' . $post->likes . ' <i
                        class="fas fa-heart"></i> |
                    ' . count($post->comments) . (count($post->comments) === 1 ? ' Comment' : ' Comments') . '
                </p>
            </div>';
        }

        return response()->json([
            'loadedPosts' => $postList
        ], 200);
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
