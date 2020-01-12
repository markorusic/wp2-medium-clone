@extends('public.shared.layout')

@section('content')
    <div class="container py-2">
        <div class="d-flex justify-content-center flex-column">
            @foreach ($posts as $post)
                @include('public.shared.post-item', compact('post'))
            @endforeach
        </div>
    </div>
@endsection
