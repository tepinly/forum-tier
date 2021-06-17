<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Like extends Model
{
    use HasFactory;

    function user() {
        return $this->belongsTo(User::class, 'user_id');
    }

    function post() {
        return $this->belongsTo(Post::class, 'post_id');
    }
}
