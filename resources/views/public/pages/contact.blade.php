@extends('public.shared.layout')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Contact') }}</div>

                <div class="card-body">
                    @include('shared.form', [
                        'config' => [
                            'method' => 'post',
                            'endpoint' => route('api.contact')
                        ],
                        'fields' => [
                            [
                                'name' => 'name',
                                'label' => 'Full name',
                                'placeholder' => 'Enter full name',
                                'validation' => '
                                    data-validate
                                    data-required
                                '
                            ],
                            [
                                'type' => 'email',
                                'name' => 'email',
                                'label' => 'Email',
                                'placeholder' => 'Enter email',
                                'validation' => '
                                    data-validate
                                    data-required
                                '
                            ],
                            [
                                'name' => 'subject',
                                'label' => 'Subject',
                                'placeholder' => 'Enter subject',
                                'validation' => '
                                    data-validate
                                    data-required
                                '
                            ],
                            [
                                'type' => 'textarea',
                                'name' => 'content',
                                'label' => 'Content',
                                'placeholder' => 'Enter Content',
                                'validation' => '
                                    data-validate
                                    data-required
                                '
                            ],
                        ]
                    ])
                </div>
            </div>
        </div>
    </div>
</div>
@endsection