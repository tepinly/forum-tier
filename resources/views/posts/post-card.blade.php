@foreach ($posts as $post)
    <div class="post card mt-3">
        <input type="hidden" name="id" id="post-id" value="{{ $post->id }}">
        <a href="{{ route('post.show', ['id' => $post->id]) }}">
            <div class="card-header">
                <h5>📌 {{ $post->title }}</h5>
            </div>
        </a>
        <div class="card-body">
            <p>
                By <a href="{{ route('user.profile', ['user_id' => $post->user->id]) }}">{{ $post->user->name }}</a>
                - {{ $post->created_at->diffForHumans() }} <br> {{ $post->likes }} <i class="fas fa-heart"></i> |
                {{ count($post->comments) . (count($post->comments) === 1 ? ' Comment' : ' Comments') }}
            </p>
        </div>
    </div>
@endforeach
