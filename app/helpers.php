<?php

use App\Models\Friend;

if (!function_exists('isFollowing')) {
    function isFollowing($user_id, $friend_id) {
        $following = Friend::firstWhere(['user_id' => $user_id, 'friend_id' => $friend_id]);
        if(!is_null($following))
            return $following;
        return False;
    }
}

// 0 -> User | 1 -> Admin | 2 -> Moderator |3 -> Author
if (!function_exists('accessLevel')) {
    function accessLevel($user, $post = null, $comment = null) {
        if ($user->roles->first() != null) $access = $user->roles->first()->id;
        elseif ( ($post != null && $post->user_id == $user->id) || ($comment != null && $comment->user_id == $user->id) ) $access = 3;
        else $access = 0;
        return $access;
    }
}