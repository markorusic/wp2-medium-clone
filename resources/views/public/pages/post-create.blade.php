@extends('public.shared.layout')

@section('content')
    @component('shared.card-wrapper', [
        'header' => ['title' => 'New post']
    ])
        @include('shared.form', [
            'config' => [
                'method' => 'post',
                'endpoint' => route('post-store')
            ],
            'fields' => [
                [
                    'name' => 'title',
                    'label' => 'Title',
                    'placeholder' => 'Enter post title'
                ],
                [
                    'type' => 'textarea',
                    'name' => 'description',
                    'label' => 'Description',
                    'placeholder' => 'Enter description',
                ],
                // [
                //     'type' => 'photo',
                //     'name' => 'main_photo',
                //     'label' => 'Post main photo'
                // ],
                [
                    'type' => 'textarea',
                    'name' => 'content',
                    'label' => 'Markdown Content',
                    'placeholder' => 'Enter markdown content',
                    'style' => 'display: none;'
                ]
            ]
        ])
    @endcomponent
@endsection
