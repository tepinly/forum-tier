@extends('layouts.app')

@section('content')
    <h2>Followers</h2>
    <div id="followersList">
        @foreach ($followers as $follower)
            <div class="follower-panel">
                <img src="{{ asset($follower->friend->avatar) }}" width="50" alt="{{ asset($follower->friend->name) }}">
                {{ $follower->friend->name }}
            </div>
        @endforeach
    </div>
@endsection