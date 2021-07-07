@extends('layouts.app')

@section('content')
<form action="/posts" method="post">
    @csrf
    <input type="text" name="title" id="title"><br>
    <div id="post-create-body" class="mt-3">
        <textarea name="body"></textarea><br>
    </div>
    <button type="submit" class="btn">Post</button>
</form>
@endsection