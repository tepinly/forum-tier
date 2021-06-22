<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Friend extends Model
{
    use HasFactory;

    // user -> the follower
    function user() {
        return $this->belongsTo(User::class, 'user_id');
    }

    // friend -> being followed
    function friend() {
        return $this->belongsTo(User::class, 'friend_id');
    }
}
