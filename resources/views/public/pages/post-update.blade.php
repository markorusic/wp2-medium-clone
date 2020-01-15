@extends('public.shared.layout')

@section('content')
    @component('shared.card-wrapper', [
        'header' => ['title' => 'Update post']
    ])
        @include('shared.form', [
            'config' => [
                'method' => 'put',
                'endpoint' => route('post-update', [
                    'post' => $post->id
                ])
            ],
            'fields' => [
                [
                    'name' => 'title',
                    'label' => 'Title',
                    'placeholder' => 'Enter post title',
                    'value' => $post->title
                ],
                [
                    'type' => 'textarea',
                    'name' => 'description',
                    'label' => 'Description',
                    'placeholder' => 'Enter description',
                    'value' => $post->description
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
                    'placeholder' => 'Enter markdown content',
                    'style' => 'display: none;',
                    'value' => $post->content
                ]
            ]
        ])
    @endcomponent
@endsection