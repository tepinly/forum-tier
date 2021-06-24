@extends('layouts.app')

@section('content')
    @foreach ($posts as $post)
        <div class="post">
            <h5>{{ $post->title }}</h5>
            <p>
                {{ $post->user->name }}<br>{{ $post->created_at->diffForHumans() }} | {{ $post->likes }} <i
                    class="fas fa-heart"></i> |
                {{ count($post->comments) . (count($post->comments) === 1 ? ' Comment' : ' Comments') }}
            </p>
        </div>
    @endforeach
@endsection
