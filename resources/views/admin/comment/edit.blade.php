@extends('admin.shared.layout')

@section('content')
    @component('shared.card-wrapper', [
        'header' => ['title' => 'Update comment']
    ])
        @include('shared.form', [
            'config' => [
                'method' => 'put',
                'endpoint' => route('admin.comments.update', [
                    'comment' => $comment->id
                ])
            ],
            'fields' => [
                [
                    'type' => 'textarea',
                    'name' => 'content',
                    'label' => 'Content',
                    'placeholder' => 'Enter content',
                    'value' => $comment->content,
                    'validation' => '
                        data-validate
                        data-required
                    '
                ]
            ]
        ])
    @endcomponent
@endsection
