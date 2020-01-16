@extends('public.shared.layout')

@section('content')
    @component('shared.card-wrapper', [
        'header' => ['title' => 'Edit profile']
    ])
        @include('shared.form', [
            'config' => [
                'method' => 'put',
                'endpoint' => route('users.update', [
                    'user' => $user->id
                ])
            ],
            'fields' => [
                [
                    'name' => 'name',
                    'label' => 'Full name',
                    'placeholder' => 'Enter full name',
                    'value' => $user->name
                ],
                
                [
                    'type' => 'textarea',
                    'name' => 'bio',
                    'label' => 'Bio',
                    'placeholder' => 'Enter bio',
                    'value' => $user->bio
                ],
                [
                    'type' => 'photo',
                    'name' => 'avatar',
                    'label' => 'Avatar photo',
                    'placeholder' => 'Click to upload avatar photo',
                    'value' => $user->avatar
                ]
            ]
        ])
    @endcomponent
@endsection
