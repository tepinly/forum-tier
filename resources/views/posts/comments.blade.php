@foreach ($comments as $comment)
    <div class="comment my-3" id="comment-{{ $comment->id }}">
        <div class="comment-header d-flex align-items-end">
            <a href="http://"><img style="max-width: 3rem" class="profile-pic mr-2" src="{{ asset($comment->user->avatar) }}" alt="{{ $comment->user->name.'\'s avatar' }}">
            <p>{{ $comment->user->name }}</a> - {{ $comment->created_at->diffForHumans() }}</p>
        </div>
        <p class="mt-3">
            {{ $comment->body }}
        </p>
        <button>Edit</button>
    </div>
@endforeach