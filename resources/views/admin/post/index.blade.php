@extends('layouts.app')

@section('content')
    @component('admin.shared.card-wrapper', [
            'header' => [
                'title' => 'Post',
                'plural' => true
            ]
        ])

        @include('admin.shared.table', [
            'resource' => 'posts',
            'cols' => ['title', 'main_photo'],
            'items' => $posts
        ])
        
    @endcomponent
@endsection
