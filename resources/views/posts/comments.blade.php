@foreach ($comments as $comment)
    <div class="comment" id="comment-{{ $comment->id }}">
        <div class="comment-header">
            <img width="40px" src="{{ asset($comment->user->avatar) }}" alt="{{ $comment->user->name.'\'s avatar' }}">
            <p>{{ $comment->user->name . ' - ' . $comment->created_at->diffForHumans() }}</p>
        </div>
        <p>
            {{ $comment->body }}
        </p>
    </div>
@endforeach