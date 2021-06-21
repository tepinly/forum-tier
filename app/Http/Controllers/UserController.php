<?php

namespace App\Http\Controllers;

use App\Models\Friend;
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

        $posts = Post::where('user_id', $user_id)->with('comments')->orderBy('created_at', 'DESC')->simplePaginate(10);
        $user = User::firstWhere('id', $user_id);
        $friends = Friend::where('user_id', $user->id)->get();
        $friendsOf = Friend::where('friend_id', $user->id)->get();
        $viewData = [
            'posts' => $posts,
            'user' => $user,
            'friends' => $friends,
            'friendsOf' => $friendsOf
        ];
        $logged = False;

        if($user_id === Auth::user()->id) {
            $logged = True;
            return view('user.personal', compact('viewData', 'logged'));
        }
        return view('user.profile', $viewData);
    }

    public function avatarChange(Request $request) {
        $request->validate([
            'avatar' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $imageName = time().'.'.$request->avatar->extension();  

        $request->avatar->move(public_path('img'), $imageName);
        $user = Auth::user();
        $user->avatar = 'img/' . $imageName;
        $user->save();

        return back();
    }
}
