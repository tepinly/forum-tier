<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comment;
use App\Models\Post;
use App\Models\User;

class CommentController extends Controller
{
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

    public function commentsFetch(Request $request) {
        if($request->ajax())
        {
            $post = Post::find($request->id);
            $comments = Comment::where(['post_id' => $post->id])->orderBy('created_at', 'DESC')->paginate(5, ['*'], 'page', $request->page);
            if(count($comments) == 0) return response()->json(['loadedComments' => ''], 200);  
            
            $commentList = '';
            foreach ($comments as $comment) {
                $commentList .= '
                            <div class="comment">
                                <div class="comment-header">
                                    <img width="40px" src="'. asset($comment->user->avatar) . '" alt=" ' . $comment->user->name . '">
                                    <p> '. $comment->user->name . ' - ' . $comment->created_at->diffForHumans() . '</p>
                                </div>
                                <p>
                                    ' . $comment->body . '
                                </p>
                            </div>
                            ';
            }

            return response()->json(['loadedComments' => $commentList], 200);  
        }
    }
}
