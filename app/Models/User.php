<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Comment;
use App\Models\Like;
use App\Models\Friend;
use App\Models\Post;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    function friends() {
        return $this->belongsToMany(User::class, Friend::class, 'user_id', 'friend_id');
    }

    function roles() {
        return $this->hasOne(Role::class);
    }

    function comments() {
        return $this->hasMany(Comment::class);
    }

    function likes() {
        return $this->hasMany(Like::class);
    }

    function posts() {
        return $this->hasMany(Post::class);
    }
}
