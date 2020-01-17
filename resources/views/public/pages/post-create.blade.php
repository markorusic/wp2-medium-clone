@extends('public.shared.layout')

@section('content')
    @component('shared.card-wrapper', [
        'header' => ['title' => 'New post']
    ])
        @include('shared.form', [
            'config' => [
                'method' => 'post',
                'endpoint' => route('posts.store')
            ],
            'fields' => [
                [
                    'name' => 'title',
                    'label' => 'Title',
                    'placeholder' => 'Enter post title',
                    'validation' => '
                        data-validate
                        data-required
                    '
                ],
                [
                    'type' => 'select',
                    'options' => $categories,
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
                    'validation' => '
                        data-validate
                        data-required
                    '
                ],
                [
                    'type' => 'photo',
                    'name' => 'main_photo',
                    'placeholder' => 'Click to upload main photo',
                    'label' => 'Main photo',
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
                    'validation' => '
                        data-validate
                        data-required
                    '
                ]
            ]
        ])
    @endcomponent
@endsection
