@extends('layouts.app')

@section('content')
<form action="/posts" method="post">
    @csrf
    <input type="text" name="title" id="title">
    <input type="text" name="body" id="body">
    <button type="submit">Post</button>
</form>
@endsection