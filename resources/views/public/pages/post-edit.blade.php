@extends('public.shared.layout')

@section('content')
    @component('shared.card-wrapper', [
        'header' => ['title' => 'Edit post']
    ])
        @include('shared.form', [
            'config' => [
                'method' => 'put',
                'endpoint' => route('posts.update', [
                    'post' => $post->id
                ])
            ],
            'fields' => [
                [
                    'name' => 'title',
                    'label' => 'Title',
                    'placeholder' => 'Enter post title',
                    'value' => $post->title,
                    'validation' => '
                        data-validate
                        data-required
                    '
                ],
                [
                    'type' => 'select',
                    'options' => $categories,
                    'value' => $post->categories,
                    'displayProperty' => 'name',
                    'multiple' => true,
                    'name' => 'categories[]',
                    'label' => 'Categories',
                    'validation' => '
                        data-validate
                        data-required
                    '
                ],
                [
                    'type' => 'textarea',
                    'name' => 'description',
                    'label' => 'Description',
                    'placeholder' => 'Enter description',
                    'value' => $post->description,
                    'validation' => '
                        data-validate
                        data-required
                    '
                ],
                [
                    'type' => 'photo',
                    'name' => 'main_photo',
                    'label' => 'Main photo',
                    'placeholder' => 'Click to upload main photo',
                    'value' => $post->main_photo,
                    'validation' => '
                        data-validate
                        data-required
                    '
                ],
                [
                    'type' => 'textarea',
                    'name' => 'content',
                    'label' => 'Markdown Content',
                    'placeholder' => 'Enter markdown content',
                    'style' => 'display: none;',
                    'value' => $post->content,
                    'validation' => '
                        data-validate
                        data-required
                    '
                ]
            ]
        ])
    @endcomponent
@endsection
