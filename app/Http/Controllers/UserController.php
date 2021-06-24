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
        if (!User::where('id', $user_id)->exists()) {
            abort(404, 'User does not exist');
        }

        $posts = Post::where('user_id', $user_id)->with('comments')->orderBy('created_at', 'DESC')->simplePaginate(10);
        $user = User::firstWhere('id', $user_id);
        $followings = Friend::where('user_id', $user->id)->get();
        $followers = Friend::where('friend_id', $user->id)->get();
        $access = hasAccess($user->id);
        if (!$access && isFollowing(Auth::user()->id, $user->id))
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

        $user = Auth::user();
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
        $user = Auth::user();
        $user->bio = $request->bio;
        $user->save();

        return response()->json([
            'bio' => $request->bio
        ]);
    }
}
