@extends('layouts.app')

@section('content')
    <div class="post-comments d-flex flex-wrap justify-content-between">
        <div id="post-container">
            <h1 id="title">{{ $post->title }}</h1>
            <p id="author">By <a href="{{ route('user.profile', ['user_id' => $post->user->id]) }}">{{ $post->user->name }}</a> - {{ $post->created_at->diffForHumans() }}</p>
            <p id="post-body">{{ $post->body }}</p>
            <input type="hidden" name="id" id="post-id" value="{{ $post->id }}">

            <div class="post-controls d-flex justify-content-start my-4">
                @if ($access)
                    <div class="edit-post d-flex">
                        <div id="edit" class="mr-2">
                            <button class="btn" onclick="editPost()">Edit</button>
                        </div>
                        <div id="delete">
                            <button class="btn" onclick="deletePost()">Delete</button>
                        </div>
                    </div>
                @endif

                {{-- Like --}}
                <div class="likes ml-auto">
                    <label for="like-btn" id="likeCount"> {{ $post->likes }}</label>
                    <button class="btn like-btn" id="like-btn" onclick="likePost()">
                    @if ($liked) <i class="fas fa-heart"></i>
                    @else <i class="far fa-heart"></i>
                    @endif
                    </button>
                </div>
            </div>

            {{-- Write Comment --}}
            <div id="addComment">
                <textarea name="comment-body" id="comment-body" maxlength="280" oninput='this.style.height = "";this.style.height = this.scrollHeight + 3 + "px"'></textarea><br>
                <button class="btn mt-3" onclick="commentPost()">Comment</button>
            </div>
        </div>

        {{-- Initial Comments --}}
        <div id="commentList" class="mt-4">
            <h4 class="post-comment-count">
                {{ $commentCount > 1 ? $commentCount . ' Comments' : ($commentCount > 0 ? '1 Comment' : 'No comments yet 😴') }}
            </h4>

            {{-- fetch comments --}}
            <div class="comments-fetched">
                {{-- @include('posts.comments', ['comments' => $comments, 'access' => $access]) --}}
            </div>

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
    </div>

    <script>
        function getToken() {
            return $('meta[name="csrf-token"]').attr('content');
        }

        function getPost() {
            return document.getElementById('post-id').value;
        }

        function updatePost() {
            const postBody = document.getElementById('body').value;
            const postId = getPost();

            $.ajax({
                type: "POST",
                url: `/posts/${postId}`,
                data: {_token: getToken(), id: getPost(), body: postBody},
                success: function (data) {
                    $("#post-body").html(data.body);
                    $("#edit").html(`
                        <button class="btn" onclick="editPost()">Edit</button>
                    `);
                },
                error: function(e) {
                    console.log(e.responseText);
                }
            });
        }

        function editPost() {
            const postBody = document.getElementById('post-body').innerHTML;
            $("#edit").html(`
                <button class="btn update-btn" onclick="updatePost()">Done</button>
            `);
            $("#post-body").html(`<textarea class="post-edit-area" name="body" id="body" oninput='this.style.height = "";this.style.height = this.scrollHeight + 3 + "px"'>${postBody}</textarea>`)
        }

        function destroyPost() {
            const postId = getPost();
            $.ajax({
                type: "post",
                url: `/posts/${postId}/destroy`,
                data: {_token: getToken(), id: postId},
                success: function (data) {
                    $("#post-body").parent().html(`
                        <h1>${data.body}</h1>
                        <form action="/posts"><button type="submit">Back to posts</button></form>
                    `);
                },
                error: function(e) {
                    console.log(e.responseText);
                }
            });
        }

        function cancelDeletePost() {
            $("#delete").html(`
                        <button class="btn" onclick="deletePost()">Delete</button>
                    `);
        }

        function deletePost() {
            $("#delete").html(`
                        <button class="btn update-btn" onclick="cancelDeletePost()">Cancel</button>
                        <button class="btn update-btn" onclick="destroyPost()">Confirm</button>
                    `);
        }

        function likePost() {
            const postId = getPost();
            $.ajax({
                type: "post",
                url: `/posts/${postId}/like`,
                data: {_token: getToken(), id: postId},
                success: function (data) {
                    $('#likeCount').html(`${data.likeCount}`)
                    if(data.liked === true) {
                        $("#like-btn").html(`
                            <i class="fas fa-heart"></i>
                        `);
                    }
                    else {
                        $("#like-btn").html(`
                            <i class="far fa-heart"></i>
                        `);
                    }
                },
                error: function(e) {
                    console.log(e.responseText);
                }
            });
        }

        function commentPost() {
            const postId = getPost();
            const commentBody = document.getElementById('comment-body').value;
            $.ajax({
                type: "post",
                url: `/posts/${postId}/comment`,
                data: {_token: getToken(), id: postId, body: commentBody},
                success: function (data) {
                    $(".comments-fetched").prepend(`
                    <div class="comment my-3" id="${data.commentId}">
                        <div class="comment-header d-flex align-items-end">
                            <a href="http://"><img style="max-width: 3rem" class="profile-pic mr-2" src="{{ asset($user->avatar) }}" alt="${data.userName}'s avatar">
                            <p>${data.userName}</a> - ${data.commentDate}</p>
                        </div>
                        <p class="mt-3">
                            ${data.commentBody}
                        </p>
                    </div>
                    `);
                    $('#comment-body').val('');
                    $('.post-comment-count').html(`${data.commentCount} Comments`);
                },
                error: function(e) {
                    console.log(e.responseText);
                }
            });
        }

        $(document).ready(function() {
            const postId = getPost();
            let page = 1;
            infinteLoadMore(page);

            $(window).scroll(function() {
                if ((window.innerHeight + window.scrollY) >= document.body.offsetHeight) {
                    page++;
                    infinteLoadMore(page);
                }
            });

            function infinteLoadMore(page) {
                $.ajax({
                    url: `/posts/${postId}/comments-fetch/${page}`,
                    type: "post",
                    data: {_token: getToken(), id: postId, page: page},
                    beforeSend: function() {
                    $('.auto-load').show();
                    }
                })
                .done(function(response) {
                    if (response.loadedComments.length == 0) {
                        $('.auto-load').html("");
                        return;
                    }
                    $('.auto-load').hide();
                    $(".comments-fetched").append(response.loadedComments);
                })
                .fail(function(jqXHR, ajaxOptions, thrownError) {
                    console.log('Server error occured');
                });
            }
        });
    </script>
@endsection