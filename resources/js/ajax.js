function updatePost() {
    const CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
    const postId = document.getElementById('id').value;

    $.ajax({
        type: "POST",
        url: `/posts/${postId}`,
        data: {_token: CSRF_TOKEN, id: postId},
        success: function (data) {
            console.log(data.body);
            $("#post-body").html(data.body);
        },
        error: function(e) {
            console.log(postId);
            console.log(e.responseText);
        }
    });
}
