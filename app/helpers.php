<?php

use Illuminate\Support\Facades\Auth;

if (!function_exists('hasAccess')) {
    function hasAccess($user_id) {
        if (Auth::check() && $user_id == Auth::user()->id) return True;
        return False;
    }
}