@extends('layouts.app')

@section('content')
<div>
    <img src="{{ asset($user->avatar) }}" alt="{{ $user->name }}">
    <h1>{{ $user->name }}</h1>
    <p>{{ $user->bio }}</p>

    <div id="postList">
        <h3>{{ count($posts) . (count($posts) === 1 ? " Post" : " Posts") }}</h3>
        @foreach ($posts as $post)
            <div class=".post">
                <p>{{ $post->title }} | {{ $post->created_at->diffForHumans() }} | {{ $post->likes  }} <i class="fas fa-heart"></i></p>
                {{ count($post->comments) . (count($post->comments) === 1 ? " Comment" : " Comments") }}
            </div>
        @endforeach
        {{ $posts->links() }}
    </div>
</div>
@endsection