@if (isset($title))
    <h2 class="mb-2">{{ __($title) }}</h2>
@endif
<div class="d-flex justify-content-between flex-wrap">
    @foreach ($categories as $category)
        <div class="pointer d-flex flex-column flex-content-between mb-4" style="width: 32%;">
            <a  class="d-block pointer"
                href="{{ route('posts.category', ['category' => $category->id]) }}">
                <img
                    class="img-fluid rounded"
                    src="{{ $category->main_photo }}"
                    alt="{{ $category->name }}"
                >
                <span class="text-center text-uppercase text-secondary">{{ $category->name }}</span>
            </a>
        </div>
    @endforeach
</div>
