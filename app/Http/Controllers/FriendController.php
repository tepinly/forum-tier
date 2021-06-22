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
        $followers = Friend::where('user_id', $user->id);

        return view('followers', [
            'user' => $user,
            'followers' => $followers
        ]);
    }
}
