<div>
    <div class="d-flex justify-content-center flex-column">
        @foreach ($posts as $post)
            @include('public.shared.post-item', compact('post'))
        @endforeach
    </div>
    {{ $posts->links() }}
</div>