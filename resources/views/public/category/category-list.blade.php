@if (isset($title))
    <h2 class="mb-2">{{ __($title) }}</h2>
@endif
<div class="d-flex justify-content-between flex-wrap">
    @foreach ($categories as $category)
        <div class="pointer d-flex flex-column flex-content-between mb-4" style="width: 32%;">
            <img
                class="img-fluid rounded"
                {{-- src="{{ $category->main_photo }}" --}}
                src="https://miro.medium.com/max/6120/0*qdqEWekIW_KHiu6P"
                alt="{{ $category->name }}"
            >
            <span class="text-center text-uppercase text-secondary">{{ $category->name }}</span>
        </div>
    @endforeach
</div>
