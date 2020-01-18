@extends('admin.shared.layout')

@section('content')
    @component('shared.card-wrapper', [
        'header' => ['title' => $user->name . '\'s activity']
    ])
        @include('shared.user-activity-list', compact('activities'))
    @endcomponent
@endsection
