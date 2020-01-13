@extends('public.shared.layout')

@section('content')
    <div class="px-5 my-4">
        <h1>{{ $post->title }}</h1>
        <h4 class="text-secondary font-weight-normal mt-3 mb-5">
            {{ $post->description }}
        </h4>

        <div class="d-flex mb-4">
            <img
                class="avatar mr-3"
                src="{{ $post->user->avatar }}"
                alt="{{ $post->user->name }}"
            >
            <div class="d-flex flex-column">
                <span>{{ $post->user->name }}</span>
                <span class="text-secondary">{{ $post->created_at->format('M d, Y') }}</span>
            </div>
        </div>

        <div class="d-flex justify-content-center mb-5">
            <img 
                class="img-fluid"
                src="{{ $post->main_photo }}"
                alt="{{ $post->title }}"
            >
        </div>

        <div id="content" class="mb-5"></div>
        <textarea id="content-ta" class="d-none">{{ $post->content }}</textarea>

        @include('public.category.tag-category-list', [
            'categories' => $post->categories
        ])

        <div class="d-flex mt-5">
            <div class="mr-4">
                <a href="#" class="text-danger" data-user-action="like">
                    <i class="fa fa-heart{{ $post->isLiked() ? '' : '-o' }} fa-2x"></i>
                    <span class="text-dark fs-25 ml-1">
                        {{ $post->likes->count() }}
                    </span>
                </a>
            </div>
            @if (auth()->id() !== $post->user->id)
                @php $isFollowing = $post->user->isFollowing(); @endphp
                <div>
                    <a href="#" class="btn btn{{ $isFollowing ? '' : '-outline' }}-success"
                        data-user-action="follow"
                        data-user-id="{{ $post->user->id }}"
                    >
                        {{ $isFollowing ? 'Following' : 'Follow' }}
                    </a>
                </div>
            @endif
        </div>

        <div class="my-4">
            <form id="comment-form">
                <div class="input-group mb-3">
                    <input type="text" name="content" required class="form-control" placeholder="Write a comment..." aria-label="Write a comment..." aria-describedby="comment-buttom">
                    <div class="input-group-append">
                        <button type="submit" class="btn btn-success input-group-text" id="comment-buttom">Submit</button>
                    </div>
                </div>
            </form>
        </div>

        @if ($post->comments->count() > 0)
            <h3 class="mb-5 pb-3 border-bottom">Comments</h3>
            <div id="comment-list">
                @foreach ($post->comments as $comment)
                    <div class="d-flex" data-comment-id="{{ $comment->id }}">
                        <div class="d-flex mb-2">
                            <img
                                class="avatar mr-3"
                                src="{{ $comment->user->avatar }}"
                                alt="{{ $comment->user->name }}"
                            >
                        </div>
                        <div class="d-flex flex-column mb-4 w-100">
                            <div class="d-flex flex-column">
                                <div class="d-flex justify-content-between">
                                    <span>{{ $comment->user->name }}</span>
                                    @if (auth()->id() === $comment->user->id)
                                        <a href="#" class="text-dark" data-user-action="remove-comment">
                                            <i class="fa fa-times" aria-hidden="true"></i>
                                        </a>
                                    @endif
                                </div>
                                <span class="text-secondary">{{ $comment->created_at->format('M d, Y') }}</span>
                            </div>
                            <div>{{ $comment->content }}</div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
@endsection
