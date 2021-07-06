@extends('layouts.app')

@section('content')
    <div id="postList">
        <div class="new-post-btn">
            <a href="{{ route('post.create') }}" class="btn">Write your post</a>
        </div>
        @foreach ($posts as $post)
            <div class="post card">
                <a href="{{ route('post.show', ['id' => $post->id]) }}">
                    <div class="card-header">
                        <h5>{{ $post->title }}</h5>
                    </div>
                </a>
                <div class="card-body">
                    <p>
                        By <a href="{{ route('user.profile', ['user_id' => $post->user->id]) }}" style="color: gray">{{ $post->user->name }}</a>
                         - {{ $post->created_at->diffForHumans() }} <br> {{ $post->likes }} <i class="fas fa-heart"></i> |
                        {{ count($post->comments) . (count($post->comments) === 1 ? ' Comment' : ' Comments') }}
                    </p>
                </div>
            </div>
        @endforeach
        {{ $posts->links() }}
    </div>

    <script>
        $(document).ready(function() {
            const postId = document.getElementById('id').value;
            let page = 2;
            infinteLoadMore(page);

            $(window).scroll(function() {
                if ($(window).scrollTop() + $(window).height() >= $(document).height()) {
                    page++;
                    infinteLoadMore(page);
                }
            });

            function infinteLoadMore(page) {
                $.ajax({
                    url: `/posts/fetch/${page}`,
                    type: "post",
                    data: {_token: getToken(), page: page},
                    beforeSend: function() {
                    $('.auto-load').show();
                    }
                })
                .done(function(response) {
                    if (response.loadedComments.length == 0) {
                        $('.auto-load').html("You hit the bottom");
                        return;
                    }
                    $('.auto-load').hide();
                    $("#postList").append(response.loadedPosts);
                })
                .fail(function(jqXHR, ajaxOptions, thrownError) {
                    console.log('Server error occured');
                });
            }
        });
    </script>
@endsection
