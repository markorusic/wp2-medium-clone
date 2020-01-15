@if ($categories->count() > 0)
    <div class="d-flex flex-wrap">
        @foreach ($categories as $category)
            <a  class="btn btn-dark mr-1 px-3 pointer"
                href="{{ route('posts.category', ['category' => $category->id]) }}">
                {{ $category->name  }}
            </a>
        @endforeach
    </div>
@endif
