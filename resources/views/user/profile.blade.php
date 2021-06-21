@extends('layouts.app')

@section('content')
<div>
    <img src='{{ asset($user->avatar) }}' alt="{{ $user->name }}" width="160px">
    <h1>{{ $user->name }}</h1>
    {{ count($friends) . " Following" }} | 
    {{ count($friendsOf) . (count($friendsOf) === 1 ? " Follower" : " Followers") }}
    <p>{{ $user->bio }}</p>

    <form action="/users/{{ $user->id }}/avatar" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="file" name="avatar" id="avatar" required>
        <button type="submit">Change</button>
    </form>

    <div id="postList">
        <h3>{{ count($posts) . (count($posts) === 1 ? " Post" : " Posts") }}</h3>
        @foreach ($posts as $post)
            <div class=".post">
                <p>
                    {{ $post->title }} <br>{{ $post->created_at->diffForHumans() }} | {{ $post->likes  }} <i class="fas fa-heart"></i> | 
                    {{ count($post->comments) . (count($post->comments) === 1 ? " Comment" : " Comments") }}
                </p>
            </div>
        @endforeach
        {{ $posts->links() }}
    </div>
</div>
@endsection