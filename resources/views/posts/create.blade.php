@extends('layouts.app')

@section('content')
    <form action="/posts" method="post">
        @csrf
        <h4>Give your post a title</h4>
        <input type="text" name="title" id="title" class="mt-2" required>
        <h4 class="mt-5">Type away ~</h4>
        <div class="mt-3">
            <textarea name="body"  id="post-create-body" oninput='this.style.height = "";this.style.height = this.scrollHeight + 3 + "px"' required></textarea><br>
        </div>
        <button type="submit" class="btn mt-4">Post</button>
    </form>
@endsection
