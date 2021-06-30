@extends('layouts.app')

@section('content')
    <div id="userList">
        @foreach ($users as $user)
            <div>
                <p>{{ $user->id }} | {{ $user->name }} - Posts: {{ count($user->posts) }}</p>
            </div>
        @endforeach
        {{ $users->links() }}
    </div>
    <div id="postList">
        @foreach ($posts as $post)
            <div>
                <p>{{ $post->id }} | {{ $post->title }} - Author: {{ $post->user->name }}</p>
            </div>
        @endforeach
        {{ $posts->links() }}
    </div>
@endsection