<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Like;

class Post extends Model
{
    use HasFactory;

    function users() {
        return $this->belongsTo(User::class, 'user_id');
    }

    function likes() {
        return $this->hasMany(Like::class);
    }
}
