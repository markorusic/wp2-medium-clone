@extends('public.shared.layout')

@section('content')
    <div class="my-4">
        <h3 class="mb-3">My activity</h3>
        @include('shared.user-activity-list', compact('activities'))
    </div>
@endsection
