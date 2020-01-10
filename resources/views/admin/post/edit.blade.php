@extends('admin.shared.layout')

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
                    'label' => 'Title',
                    'placeholder' => 'Enter post title',
                    'required' => true,
                    'value' => $post->title
                ],
                // [
                //     'type' => 'photo',
                //     'name' => 'main_photo',
                //     'label' => 'Post main photo',
                //     'value' => $post->main_photo
                // ],
                [
                    'type' => 'textarea',
                    'name' => 'content',
                    'label' => 'Markdown Content',
                    'placeholder' => 'Enter markdown',
                    'required' => true,
                    'value' => $post->content
                ]
            ]
        ])
    @endcomponent
@endsection
