@extends('layouts.app')

@section('content')
    @component('admin.shared.card-wrapper', [
        'header' => ['title' => 'Update ' . $post->title]
    ])
        @include('admin.shared.form', [
            'config' => [
                'endpoint' => '',
                'method' => 'put',
                'resource' => 'post'
            ],
            'fields' => [
                [
                    'name' => 'title',
                    'label' => 'Post title',
                    'placeholder' => 'Enter post title',
                    'required' => true,
                    'value' => $post->title
                ],
                [
                    'type' => 'textarea',
                    'name' => 'content',
                    'label' => 'Post content',
                    'placeholder' => 'Enter post content',
                    'required' => true,
                    'value' => $post->content
                ],
                [
                    'type' => 'photo',
                    'name' => 'main_photo',
                    'label' => 'Post main photo',
                    'value' => $post->main_photo
                ]
            ]
        ])
    @endcomponent
@endsection
