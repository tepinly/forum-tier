@extends('layouts.app')

@section('content')
    <div class="new-post-btn">
        <a href="{{ route('post.create') }}" class="btn">📝 Post</a>
    </div>

    <div id="postList">
        {{-- @include('posts.post-card', ['posts' => $posts]) --}}

        {{-- Data Loader --}}
        <div class="auto-load text-center mt-4">
            <svg version="1.1" id="L9" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                x="0px" y="0px" height="60" viewBox="0 0 100 100" enable-background="new 0 0 0 0" xml:space="preserve">
                <path fill="#000"
                    d="M73,50c0-12.7-10.3-23-23-23S27,37.3,27,50 M30.9,50c0-10.5,8.5-19.1,19.1-19.1S69.1,39.5,69.1,50">
                    <animateTransform attributeName="transform" attributeType="XML" type="rotate" dur="1s"
                        from="0 50 50" to="360 50 50" repeatCount="indefinite" />
                </path>
            </svg>
        </div>
    </div>

    <script>
        function getToken() {
            return $('meta[name="csrf-token"]').attr('content');
        }

        $(document).ready(function() {
            let page = 1;
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
                    if (response.loadedPosts.length == 0) {
                        $('.auto-load').html("");
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
