@extends('layouts.app')

@section('content')
    <h2><a href="{{ route('user.profile', ['user_id' => $user->id]) }}"><</a> {{ $user->name }}'s Followers</h2>
    <div id="followersList">
        @foreach ($followers as $follower)
            <div class="follow-panel">
                <a href="{{ route('user.profile', ['user_id' => $follower->friend->id]) }}">
                    <img class="follow-profile-pic mr-2" src="{{ asset($follower->friend->avatar) }}" width="50" alt="{{ asset($follower->friend->name) }}">
                    {{ $follower->friend->name }}
                </a>
                <span class="mx-2"> | </span>
                {{ count($follower->friend->posts) }} Posts
            </div>
        @endforeach
    </div>
@endsection