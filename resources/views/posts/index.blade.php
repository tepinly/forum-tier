@extends('layouts.app')

@section('content')
    <div id="postList">
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
