@extends('admin.shared.layout')

@section('content')
    @component('shared.card-wrapper', [
        'header' => ['title' => 'Update category']
    ])
        @include('shared.form', [
            'config' => [
                'method' => 'post',
                'endpoint' => route('admin.categories.store')
            ],
            'fields' => [
                [
                    'name' => 'name',
                    'label' => 'Name',
                    'placeholder' => 'Enter name',
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
                    'label' => 'Main photo',
                    'placeholder' => 'Click to upload main photo',
                    'validation' => '
                        data-validate
                        data-required
                    '
                ]
            ]
        ])
    @endcomponent
@endsection
