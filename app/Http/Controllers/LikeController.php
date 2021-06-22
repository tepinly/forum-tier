<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Post;
use App\Models\User;
use App\Models\Like;

class LikeController extends Controller
{
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
}
