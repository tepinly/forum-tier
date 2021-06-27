<?php

namespace App\Http\Controllers;

use App\Models\Friend;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Post;

class UserController extends Controller
{
    public function profile($user_id)
    {
        $user = User::firstWhere('id', $user_id);
        if ($user === null) abort(404, 'User doesn\'t exist');

        $posts = Post::where('user_id', $user_id)->with('comments')->orderBy('created_at', 'DESC')->simplePaginate(10);
        $followings = Friend::where('user_id', $user->id)->get();
        $followers = Friend::where('friend_id', $user->id)->get();
        $access = accessLevel(Auth::user());
        if (isFollowing(Auth::user()->id, $user->id))
            $following = True;
        else
            $following = False;

        return view('user.profile', compact('posts', 'user', 'followings', 'followers', 'access', 'following'));
    }

    public function updateAvatar(Request $request)
    {
        $folderPath = public_path('img/avatars/');
        $image_parts = explode(";base64,", $request->image);
        $image_base64 = base64_decode($image_parts[1]);
        $imageName = uniqid() . '.png';
        $imageFullPath = $folderPath . $imageName;
        file_put_contents($imageFullPath, $image_base64);

        $user = User::firstWhere('id', $request->user_id);
        if ($user->avatar != 'img/default-avatar.png' && \File::exists(public_path($user->avatar))){
            \File::delete(public_path($user->avatar));
        }

        $user->avatar = 'img/avatars/' . $imageName;
        $user->save();

        return response()->json([
            'success' => 'Crop Image Uploaded Successfully',
            'newAvatar' => $user->avatar
        ]);
    }

    public function updateBio(Request $request)
    {
        $user = User::firstWhere('id', $request->user_id);
        $user->bio = $request->bio;
        $user->save();

        return response()->json([
            'bio' => $request->bio
        ]);
    }

    public function delete($user_id) {
        $user = User::firstWhere('id', $user_id);
        $user->delete();

        return response()->json([
            'body' => 'Account deleted.'
        ], 200);
    }
}
