@extends('admin.shared.layout')

@section('content')
    <div class="container py-2">
        <div class="d-flex justify-content-center">
            <h2>Welcome back, {{ auth()->user()->name }}</h2>
        </div>
    </div>
@endsection
