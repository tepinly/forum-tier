@extends('layouts.app')

@section('content')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.6/cropper.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.6/cropper.js"></script>
    <style type="text/css">
        img {
            display: block;
            max-width: 100%;
        }

        .preview {
            overflow: hidden;
            width: 160px;
            height: 160px;
            margin: 10px;
            border: 1px solid red;
        }

        .modal-lg {
            max-width: 1000px !important;
        }

    </style>
    <input type="hidden" name="user_id" id="user_id" value="{{ $user->id }}">
    <div class="profile-page mt-4 d-flex flex-wrap justify-content-around align-items-start">
        <div class="profile-info p-5 mb-4 mx-2">
            <div id="avatar">
                <img class="profile-pic main-profile-pic" src={{ asset($user->avatar) }}
                    alt="{{ $user->name . '\'s avatar' }}">
            </div>
            <h1 class="ml-2">{{ $user->name }}</h1>
            {{ count($followings) }} Following <span class="mx-2"> | </span>
            <span id="followers-count">{{ count($followers) }}</span> Followers
            <p id="bio" class="mt-2">{{ $user->bio }}</p>

            {{-- Follow prompts --}}
            @if ($access < 3)
                <div id="follow-prompt" class="d-flex mb-4">
                    @if ($following)
                        <button class="btn mr-2" onclick="unfollow()">Unfollow</button>
                    @else
                        <button class="btn" onclick="follow()">Follow</button>
                    @endif
                </div>
            @endif

            {{-- Edit profile --}}
            @if ($access > 0)
                <div class="d-flex flex-wrap">
                    <div id="bio-change" class="mt-2 mr-2">
                        <button class="btn" onclick="changeBio()">Update Bio</button>
                    </div>
                    <div id="avatar-change" class="mt-2 mr-2">
                        <button class="btn" onclick="changeAvatar()">Change Avatar</button>
                    </div>

                    @if ($access == 1)
                        <div id="terminate-account">
                            <button class="btn mt-2" onclick="terminatePrompt()">Terminate</button>
                        </div>
                    @endif

                    <div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-labelledby="modalLabel"
                        aria-hidden="true">
                        <div class="modal-dialog modal-lg" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="modalLabel">Adjust image</h5>
                                    <button type="button" class="btn close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">×</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <div class="img-container">
                                        <div class="row">
                                            <div class="col-md-8">
                                                <img id="image">
                                            </div>
                                            <div class="col-md-4">
                                                <div class="preview"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn" data-dismiss="modal">Cancel</button>
                                    <button type="button" class="btn btn-primary" id="crop">Set</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        {{-- Post list --}}
        <div id="postList">
            <h3>{{ $postCount . ($postCount === 1 ? ' Post' : ' Posts') }}</h3>
            @foreach ($posts as $post)
                <div class="mini-post pt-3 px-4 pb-1 mb-4">
                    <h5><a href="{{ route('post.show', ['id' => $post->id]) }}">{{ $post->title }}</a></h5>
                    <p class="mt-3">
                        {{ $post->created_at->diffForHumans() }} <span class="mx-3"> {{ $post->likes }} <i
                            class="fas fa-heart"></i> </span>
                        {{ count($post->comments) . (count($post->comments) === 1 ? ' Comment' : ' Comments') }}
                    </p>
            </div>
            @endforeach
            {{ $posts->links() }}
        </div>
    </div>

    <script>
        let $modal = $("#modal");
        let image = document.getElementById("image");
        let cropper;
        let bio = document.getElementById('bio').innerHTML;
        const userId = document.getElementById('user_id').value;

        function terminatePrompt() {
            $('#terminate-account').html(`
                <button class="btn mt-2" onclick="terminateCancel()">Cancel</button>
                <button class="btn mr-2 mt-2" onclick="terminateConfirm()">Confirm</button>
            `)
        }

        function terminateCancel() {
            $('#terminate-account').html(`
                <button class="btn mr-2 mt-2" onclick="terminatePrompt()">Terminate</button>
            `)
        }

        function terminateConfirm() {
            const userId = document.getElementById('user_id').value;
            $.ajax({
                type: 'POST',
                url: `/users/${userId}/delete`,
                data: {
                    _token: $('meta[name="csrf-token"]').attr("content"),
                },
                success: function(data) {
                    $('.profile-page').html(`
                        Account terminated.
                    `)
                },
            });
        }

        function unfollow() {
            $.ajax({
                type: 'POST',
                url: `/users/${userId}/unfollow`,
                data: {
                    _token: $('meta[name="csrf-token"]').attr("content"),
                },
                success: function(data) {
                    $('#follow-prompt').html(`
                        <button class="btn" onclick="follow()">Follow</button>
                    `)
                    $('#followers-count').html(`
                        ${data.followers}
                    `)
                },
            });
        }

        function follow() {
            $.ajax({
                type: 'POST',
                url: `/users/${userId}/follow`,
                data: {
                    _token: $('meta[name="csrf-token"]').attr("content")
                },
                success: function(data) {
                    $('#follow-prompt').html(`
                        <button class="btn" onclick="unfollow()">Unfollow</button>
                    `)
                    $('#followers-count').html(`
                        ${data.followers}
                    `)
                },
            });
        }

        function changeAvatar() {
            $('#avatar-change').html(`
                <input type="file" name="image" class="image btn">
            `)
        }

        function changeBio() {
            $('#bio').html(`
                <input type="text" name="bio" class="bio mr-2 mb-2" id="bio-change-input" value=${bio}>
                <button class="btn" onclick="updateBio()">Done</button>
            `)
        }

        function updateBio() {
            const newBio = document.getElementById('bio-change-input').value;

            $.ajax({
                type: "POST",
                dataType: "json",
                url: `/users/${userId}/bio`,
                data: {
                    _token: $('meta[name="csrf-token"]').attr("content"),
                    bio: newBio,
                },
                success: function(data) {
                    bio = data.bio;
                    $('#bio-change').html(`
                        <button class="btn" id="bio-change-button" onclick="changeBio()">Update Bio</button>
                    `)
                    $('#bio').html(`${data.bio}`);
                },
            });
        }

        $("body").on("change", ".image", function(e) {
            let files = e.target.files;
            let done = function(url) {
                image.src = url;
                $modal.modal("show");
            };
            let reader;
            let file;
            let url;
            if (files && files.length > 0) {
                file = files[0];
                if (URL) {
                    done(URL.createObjectURL(file));
                } else if (FileReader) {
                    reader = new FileReader();
                    reader.onload = function(e) {
                        done(reader.result);
                    };
                    reader.readAsDataURL(file);
                }
            }
        });

        $modal
            .on("shown.bs.modal", function() {
                cropper = new Cropper(image, {
                    aspectRatio: 1,
                    viewMode: 3,
                    preview: ".preview",
                });
            })
            .on("hidden.bs.modal", function() {
                cropper.destroy();
                cropper = null;
            });

        $("#crop").click(function() {
            canvas = cropper.getCroppedCanvas({
                width: 200,
                height: 200,
            });
            canvas.toBlob(function(blob) {
                url = URL.createObjectURL(blob);
                let reader = new FileReader();
                reader.readAsDataURL(blob);
                reader.onloadend = function() {
                    let base64data = reader.result;
                    const userId = document.getElementById('user_id').value;
                    $.ajax({
                        type: "POST",
                        dataType: "json",
                        url: `/users/${userId}/avatar`,
                        data: {
                            _token: $('meta[name="csrf-token"]').attr("content"),
                            image: base64data,
                        },
                        success: function(data) {
                            $('#avatar').html(
                                `<img class="profile-pic main-profile-pic" src={{ asset('${data.newAvatar}') }}>`
                            );
                            $('#avatar-change').html(`
                                <button class="btn" onclick="changeAvatar()">Change Avatar</button><br>
                                Avatar updated
                            `);
                            $modal.modal('hide');
                        },
                    });
                };
            });
        });
    </script>
@endsection
