@extends('layouts.app')

@section('content')
    <h2><a href="{{ route('user.profile', ['user_id' => $user->id]) }}"><</a> {{ $user->name }}'s Following</h2>
    <div id="followersList">
        @foreach ($followings as $following)
            <div class="follow-panel">
                <a href="{{ route('user.profile', ['user_id' => $following->friend->id]) }}">
                    <img class="follow-profile-pic mr-2" src="{{ asset($following->user->avatar) }}" width="50" alt="{{ asset($following->user->name) }}">
                    {{ $following->user->name }}
                </a>
                <span class="mx-2"> | </span>
                {{ count($following->friend->posts) }} Posts
            </div>
        @endforeach
    </div>
@endsection