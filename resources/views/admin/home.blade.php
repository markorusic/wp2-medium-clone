@extends('admin.partials.layout', [
    'content_title' => 'Home'
])

@section('main')
    <div>
        Welcome, {{ auth()->user()->name }}
    </div>
@endsection
