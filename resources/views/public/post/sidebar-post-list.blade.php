@if (isset($title))
    <h2 class="pb-3 mb-3 border-bottom">{{ __($title) }}</h2>
@endif
@foreach ($popular_posts as $post)
    <div class="d-flex flex-column justify-content-between">
        <h3>
            <a class="text-black pointer" href="{{ route('posts.index', ['post' => $post->id]) }}">{{ $post->title }}</a>
        </h3>
        <div class="d-flex flex-column">
            <span>{{ $post->user->name }}</span>
            <span class="text-secondary">{{ $post->created_at->format('M d, Y') }}</span>
        </div>
    </div>
@endforeach
<div class="text-right">
    <a class="text-success text-uppercase font-weight-bold" href="#">
        {{ __('See more') }}
        <i class="fa fa-chevron-right" style="font-size: 14px"></i>
    </a>
</div>
