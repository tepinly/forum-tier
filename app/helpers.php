<?php

use App\Models\User;
use App\Models\Friend;

if (!function_exists('isFollowing')) {
    function isFollowing($user_id, $friend_id) {
        $following = Friend::firstWhere(['user_id' => $user_id, 'friend_id' => $friend_id]);
        if(!is_null($following))
            return $following;
        return False;
    }
}

// 0 -> User | 1 -> Admin | 2 -> Moderator | 3 -> Author
if (!function_exists('accessLevel')) {
    function accessLevel($user_id, $post = null, $comment = null) {
        $user = User::firstWhere('id', $user_id);
        if (Auth::user()->id == $user_id) return 3;
        if (Auth::user()->roles->first() != null) return Auth::user()->roles->first()->id;
        return 0;
    }
}