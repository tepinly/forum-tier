<?php

use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Friend;

if (!function_exists('hasAccess')) {
    function hasAccess($user_id) {
        if (Auth::check() && $user_id == Auth::user()->id) return True;
        return False;
    }
}

if (!function_exists('isFollowing')) {
    function isFollowing($user_id, $friend_id) {
        $following = Friend::firstWhere(['user_id' => $user_id, 'friend_id' => $friend_id]);
        if(!is_null($following))
            return $following;
        return False;
    }
}