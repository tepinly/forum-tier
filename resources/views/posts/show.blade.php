@extends('layouts.app')

@section('content')
        <h1 id="title">{{ $post->title }}</h1>
        <h5 id="author">By {{ $post->user->name . ' - ' . $post->created_at->diffForHumans() }}</h5>
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

        <div class="likes">
            <button class="btn btn-danger" id="like-btn" onclick="likePost()">
            @if ($liked) <i class="fas fa-heart"></i>
            @else <i class="far fa-heart"></i>
            @endif
            </button><label for="like-btn" id="likeCount">{{ $post->likes }}</label>
        </div>

        <div id="addComment">
            <textarea name="comment-body" id="comment-body" cols="50" rows="5"></textarea>
            <button onclick="commentPost()">Comment</button>
        </div>

        <div id="commentList">
            @include('posts.comments', ['comments' => $comments])
        </div>

        <!-- Data Loader -->
        <div class="auto-load text-center">
            <svg version="1.1" id="L9" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                x="0px" y="0px" height="60" viewBox="0 0 100 100" enable-background="new 0 0 0 0" xml:space="preserve">
                <path fill="#000"
                    d="M73,50c0-12.7-10.3-23-23-23S27,37.3,27,50 M30.9,50c0-10.5,8.5-19.1,19.1-19.1S69.1,39.5,69.1,50">
                    <animateTransform attributeName="transform" attributeType="XML" type="rotate" dur="1s"
                        from="0 50 50" to="360 50 50" repeatCount="indefinite" />
                </path>
            </svg>
        </div>

        <script>
            function getToken() {
                return $('meta[name="csrf-token"]').attr('content');
            }
    
            // TODO: POST CRUD --------
    
            function updatePost() {
                const postId = document.getElementById('id').value;
                const postBody = document.getElementById('body').value;
    
                $.ajax({
                    type: "POST",
                    url: `/posts/${postId}`,
                    data: {_token: getToken(), id: postId, body: postBody},
                    success: function (data) {
                        $("#post-body").html(data.body);
                        $("#edit").html(`
                            <button onclick="editPost()">Edit</button>
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
                            <input type="text" name="body" id="body" value="${postBody}">
                            <button class="update-btn" onclick="updatePost()">Done</button>
                        `);
            }
    
            function destroyPost() {
                const postId = document.getElementById('id').value;
    
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
                            <button onclick="deletePost()">Delete</button>
                        `);
            }
    
            function deletePost() {
                $("#delete").html(`
                            <p>This will delete the entire thread, it's an irreversible action</p>
                            <button class="update-btn" onclick="cancelDeletePost()">Cancel</button>
                            <button class="update-btn" onclick="destroyPost()">Confirm</button>
                        `);
            }
    
            // --------
    
            function likePost() {
                const postId = document.getElementById('id').value;
    
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
                const postId = document.getElementById('id').value;
                const commentBody = document.getElementById('comment-body').value;
    
                $.ajax({
                    type: "post",
                    url: `/posts/${postId}/comment`,
                    data: {_token: getToken(), id: postId, body: commentBody},
                    success: function (data) {
                        $("#commentList").prepend(`
                        <div class="comment">
                            <div class="comment-header">
                                <img width="40px" src="{{ asset($user->avatar) }}" alt="${data.userName}'s avatar">
                                <p>${data.userName} - ${data.commentDate}</p>
                            </div>
                            <p>
                                ${data.commentBody}
                            </p>
                        </div>
                        `);
                        $('#comment-body').val('');
                    },
                    error: function(e) {
                        console.log(e.responseText);
                    }
                });
            }

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
                        url: `/posts/${postId}/comments-fetch/${page}`,
                        type: "post",
                        data: {_token: getToken(), id: postId, page: page},
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
                        $("#commentList").append(response.loadedComments);
                    })
                    .fail(function(jqXHR, ajaxOptions, thrownError) {
                        console.log('Server error occured');
                    });
                }
            });
        </script>
@endsection