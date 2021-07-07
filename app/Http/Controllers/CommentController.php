<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Comment;
use App\Models\Post;

class CommentController extends Controller
{
    public function create(Request $request) {
        $post = Post::find($request->id);
        $user = Auth::user();

        $comment = new Comment;
        $comment->post_id = $post->id;
        $comment->user_id = $user->id;
        $comment->body = $request->body;
        $comment->save();

        return response()->json([
            'commentId' => $comment->id,
            'commentBody' => $comment->body,
            'commentDate' => $comment->created_at->diffForHumans(),
            'commentCount' => Comment::where('post_id', $post->id)->count(),
            'userName' => $user->name,
        ], 200);
    }

    public function fetch(Request $request) {
        if($request->ajax())
        {
            $post = Post::find($request->id);
            $comments = Comment::where(['post_id' => $post->id])->orderBy('created_at', 'DESC')->paginate(5, ['*'], 'page', $request->page);
            if(count($comments) == 0) return response()->json(['loadedComments' => ''], 200);

            $commentList = '';
            foreach ($comments as $comment) {
                $commentList .= '
                    <div class="comment my-3" id="' . $comment->id . '">
                        <div class="comment-header d-flex align-items-end">
                            <a href="/users/' . $comment->user->id . '"><img style="max-width: 3rem" class="profile-pic mr-2" src="'. asset($comment->user->avatar) . '" alt=" ' . $comment->user->name . '">
                            <p> '. $comment->user->name . '</a> - ' . $comment->created_at->diffForHumans() . '</p>
                        </div>
                        <p class="mt-3">
                            ' . $comment->body . '
                        </p>
                    </div>
                    ';
            }
            return response()->json(['loadedComments' => $commentList], 200);  
        }
    }

    function delete(Request $request) {
        $comment = Comment::firstWhere('id', $request->comment_id);
        $comment->delete();

        return response()->json([
            'comment' => 'Deleted'
        ], 200);
    }
}
