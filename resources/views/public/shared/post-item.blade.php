<div class="mb-5">
    <div class="d-flex justify-content-between">
        <div class="d-flex flex-column justify-content-between">
            <div class="d-flex flex-column">
                <h3>{{ $post->title }}</h3>
                <span class="text-secondary">{{ $post->description }}</span>
            </div>
            <div class="d-flex flex-column">
                <span>{{ $post->user->name }}</span>
                <span class="text-secondary">{{ $post->created_at->format('M d, Y') }}</span>
            </div>
        </div>
        <div class="ml-5">
            <img
                class="rounded"
                style="height: 200px;"
                src="https://miro.medium.com/max/6120/0*qdqEWekIW_KHiu6P"
                {{-- src="{{ $post->main_photo }}" --}}
                alt="{{ $post->title }}"
            >
        </div>
    </div>
</div>