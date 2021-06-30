<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function controlPanel() {
        $users = User::orderBy('created_at', 'DESC')->paginate(100);
        $posts = Post::orderBy('created_at', 'DESC')->paginate(20);

        return view('admin.control-panel', compact('users', 'posts'));
    }
}
