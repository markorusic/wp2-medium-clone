@extends('public.shared.layout')

@section('content')
    <div class="my-3">
        @include('public.post.post-list', [
            'title' => $category->name,
            'posts' => $posts
        ])
    </div>
@endsection
