@extends('layouts.app')

@section('content')
    <h2><a href="{{ route('user.profile', ['user_id' => $user->id]) }}"><</a> {{ $user->name }}'s Followers</h2>
    <div id="followersList" class="d-flex flex-wrap justify-content-start align-items-start">
        @foreach ($followers as $follower)
            <div class="follow-panel m-3">
                <a href="{{ route('user.profile', ['user_id' => $follower->user->id]) }}">
                    <img class="follow-profile-pic mr-2" src="{{ asset($follower->user->avatar) }}" width="50" alt="{{ asset($follower->user->name) }}">
                    {{ $follower->user->name }}
                </a>
                <span class="mx-2"> | </span>
                {{ count($follower->user->posts) }} Posts
            </div>
        @endforeach
    </div>
@endsection