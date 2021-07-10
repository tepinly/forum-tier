@extends('layouts.app')

@section('content')
    <h2>{{ $user->name }}'s Followings</h2>
    <div id="followersList">
        @foreach ($followings as $following)
            <div class="follow-panel">
                <img src="{{ asset($following->user->avatar) }}" width="50" alt="{{ asset($following->user->name) }}">
                {{ $following->user->name }}
            </div>
        @endforeach
    </div>
@endsection