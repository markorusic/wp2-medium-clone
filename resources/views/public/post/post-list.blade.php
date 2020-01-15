@if (isset($title))
    <h2 class="pb-3 mb-3 border-bottom">{{ __($title) }}</h2>
@endif
<div class="d-flex justify-content-center flex-column">
    @foreach ($posts as $post)
        @php
            $postEntryUrl = route('posts.index', ['post' => $post->id]);
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
                        <span>{{ $post->user->name }}</span>
                        <span class="text-secondary">{{ $post->created_at->format('M d, Y') }}</span>
                    </div>
                </div>
                <div class="ml-5">
                    <a class="pointer" href="{{ $postEntryUrl }}">
                        <img
                            class="rounded"
                            style="height: 200px;"
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
