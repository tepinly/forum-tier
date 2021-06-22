<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Friend;

class FriendController extends Controller
{
    public function followers($user_id) {
        $user = User::firstWhere('id', $user_id);
        $followers = Friend::where('user_id', $user->id)->get();

        return view('user/followers', [
            'user' => $user,
            'followers' => $followers
        ]);
    }

    public function following($user_id) {
        $user = User::firstWhere('id', $user_id);
        $followings = Friend::where('friend_id', $user->id)->get();

        return view('user/following', [
            'user' => $user,
            'followings' => $followings
        ]);
    }

    public function unfollow($user_id) {
        $friend = User::firstWhere('id', $user_id);
        $user = Auth::user();
        $following = isFollowing($user->id, $friend->id);
        $following->delete();
        $followers = Friend::where('friend_id', $friend->id)->count();

        return response()->json([
            'followers' => $followers
        ], 200);
    }

    public function follow($user_id) {
        $friend = User::firstWhere('id', $user_id);
        $user = Auth::user();

        $follow = new Friend;
        $follow->user_id = $user->id;
        $follow->friend_id = $friend->id;
        $follow->save();
        $followers = Friend::where('friend_id', $friend->id)->count();

        return response()->json([
            'followers' => $followers
        ], 200);
    }
}
