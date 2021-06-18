@extends('layouts.app')

@section('content')
        <h1 id="title">{{ $post->title }}</h1>
        <h5 id="author">By {{ $post->user->name }}</h5>
        <p id="post-body">{{ $post->body }}</p>
        <input type="hidden" name="id" id="id" value="{{ $post->id }}">
        
        @if ($user == $post->user)
        <div id="edit">
            <button onclick="editPost()">Edit</button>
        </div>
        <div id="delete">
            <button onclick="deletePost()">Delete</button>
        </div>
        @endif
@endsection