<div class="d-flex flex-wrap">
    @foreach ($categories as $category)
        <a class="btn btn-dark mr-1 px-3 pointer" href="#">
            {{ $category->name  }}
        </a>
    @endforeach
</div>
