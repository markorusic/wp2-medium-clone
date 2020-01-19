@extends('admin.shared.layout')

@section('content')
    @component('shared.card-wrapper', [
        'header' => ['title' => 'Update category']
    ])
        @include('shared.form', [
            'config' => [
                'method' => 'put',
                'endpoint' => route('admin.categories.update', [
                    'category' => $category->id
                ])
            ],
            'fields' => [
                [
                    'name' => 'name',
                    'label' => 'Name',
                    'placeholder' => 'Enter name',
                    'value' => $category->name,
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
                    'value' => $category->description,
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
                    'value' => $category->main_photo,
                    'validation' => '
                        data-validate
                        data-required
                    '
                ]
            ]
        ])
    @endcomponent
@endsection
