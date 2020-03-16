@if (isset($title))
    @if ($posts->count() === 0)
        <h3>No posts yet.</h3>
    @else
        <h2 class="pb-3 mb-3 border-bottom">{{ __($title) }}</h2>
    @endif
@endif
<div class="d-flex justify-content-center flex-column">
    @foreach ($posts as $post)
        @php
            $postEntryUrl = route('posts.show', ['post' => $post->id]);
        @endphp
        <div class="mb-5">
            <div class="d-flex justify-content-between">
                <div class="d-flex flex-column justify-content-between">
                    <div class="d-flex flex-column">
                        <h3>
                            <a class="text-black pointer" href="{{ $postEntryUrl }}">
                                {{ $post->title }}
                            </a>
                        </h3>
                        <span class="text-secondary">{{ $post->description }}</span>
                    </div>
                    <div class="d-flex flex-column">
                        @if ($post->relationLoaded('user'))
                            @include('public.user.user-name', [
                                'user' => $post->user
                            ])
                        @endif
                        <span class="text-secondary">{{ $post->created_at->format('M d, Y') }}</span>
                    </div>
                </div>
                <div class="ml-5">
                    <a class="pointer" href="{{ $postEntryUrl }}">
                        <img
                            class="rounded"
                            style="width: 300px;"
                            src="{{ $post->main_photo }}"
                            alt="{{ $post->title }}"
                        >
                    </a>
                </div>
            </div>
        </div>
    @endforeach
</div>
{{ $posts->links() }}
