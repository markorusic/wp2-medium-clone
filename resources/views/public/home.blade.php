@extends('public.shared.layout')

@section('content')
    @include('public.shared.post-list', compact('posts'))
@endsection
