@extends('layouts.app')

@section('content')
    <h2>{{ $user->name }}'s Followers</h2>
    <div id="followersList">
        @foreach ($followers as $follower)
            <div class="follow-panel">
                <img src="{{ asset($follower->friend->avatar) }}" width="50" alt="{{ asset($follower->friend->name) }}">
                <a href="{{ route('user.profile', ['user_id' => $follower->friend->id]) }}">{{ $follower->friend->name }}</a>
                <span class="mx-2"> | </span>
                {{ count($follower->friend->posts) }} Posts
            </div>
        @endforeach
    </div>
@endsection