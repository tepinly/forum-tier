<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Like extends Model
{
    use HasFactory;

    function users() {
        return $this->belongsTo(User::class, 'user_id');
    }

    function posts() {
        return $this->belongsTo(Post::class, 'post_id');
    }
}
