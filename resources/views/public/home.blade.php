@extends('public.shared.layout')

@section('content')
    <div class="my-3">
        <div class="mb-5">
            @include('public.category.category-list', [
                'title' => 'Explore',
                'categories' => $categories
            ])
        </div>
        <div class="row">
            <div class="col-8">
                @include('public.post.post-list', [
                    'title' => 'New Posts',
                    'posts' => $posts
                ])
            </div>
            <div class="col-3 offset-1 align-self-start sticky-top">
                @include('public.post.sidebar-post-list', [
                    'title' => 'Popular Posts',
                    'posts' => $popular_posts
                ])
            </div>
        </div>
    </div>
@endsection
