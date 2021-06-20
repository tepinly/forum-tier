<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Post;

class UserController extends Controller
{
    public function profile($user_id) {
        if(!User::where('id', $user_id)->exists()) {
            abort(404, 'User does not exist');
        }

        $posts = Post::where('user_id', $user_id)->orderBy('created_at', 'DESC')->simplePaginate(10);
        $user = User::firstWhere('id', $user_id);
        $viewData = [
            'posts' => $posts,
            'user' => $user
        ];
        $logged = False;

        if($user_id === Auth::user()->id) {
            $logged = True;
            return view('user.personal', compact('viewData', 'logged'));
        }
        return view('user.profile', $viewData);
    }
}
