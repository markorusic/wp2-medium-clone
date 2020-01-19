@extends('admin.shared.layout')

@section('content')
    @component('shared.card-wrapper', [
        'header' => ['title' => 'Edit user profile']
    ])
        @include('shared.form', [
            'config' => [
                'method' => 'put',
                'endpoint' => route('admin.users.update', [
                    'user' => $user->id
                ])
            ],
            'fields' => [
                [
                    'name' => 'name',
                    'label' => 'Full name',
                    'placeholder' => 'Enter full name',
                    'value' => $user->name,
                    'validation' => '
                        data-validate
                        data-required
                    '
                ],
                
                [
                    'type' => 'textarea',
                    'name' => 'bio',
                    'label' => 'Bio',
                    'placeholder' => 'Enter bio',
                    'value' => $user->bio,
                    'validation' => '
                        data-validate
                        data-required
                    '
                ],
                [
                    'type' => 'photo',
                    'name' => 'avatar',
                    'label' => 'Avatar photo',
                    'placeholder' => 'Click to upload avatar photo',
                    'value' => $user->avatar,
                    'validation' => '
                        data-validate
                        data-required
                    '
                ]
            ]
        ])
    @endcomponent
@endsection
