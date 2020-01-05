@extends('adminlte::page')

@section('title', $title ?? 'Dashboard')

@if (isset($content_title))
    @section('content_header')
        <h1>{{ $content_title }}</h1>
    @stop
@endif

@section('content')
    <div class="card">
        <div class="card-body">
            @yield('main')
        </div>
    </div>
@stop
