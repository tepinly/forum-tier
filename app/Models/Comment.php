<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Post;

class Comment extends Model
{
    use HasFactory;

    function users() {
        return $this->belongsTo(User::class);
    }

    function posts() {
        return $this->belongsTo(Post::class);
    }
}
