@extends('public.shared.layout')

@section('content')
    <div class="my-3 px-5 pt-5">
        <div class="d-flex justify-content-between my-4">
            <div class="d-flex flex-column">
                <div class="d-flex align-items-center">
                    <h1 class="mr-3">{{ $user->name }}</h1>
                    @if ($user->id === auth()->id())
                        <a class="btn btn-primary" href="{{ route('users.edit', ['user' => $user->id]) }}">
                            <i class="fa fa-pencil mr-2"></i>
                            {{ __('Edit profile') }}
                        </a>
                    @else
                        @include('public.user.follow-button', compact('user'))
                    @endif
                </div>
                <div class="my-2">
                    <h5 class="text-dark font-weight-normal">{{ $user->bio }}</h5>
                </div>
                <div class="my-2">
                    <span class="text-secondary mr-3">
                        {{ $user->following_count }} Following
                    </span>
                    <span class="text-secondary">
                        <span data-followers-count>{{ $user->followers_count }}</span> Followers
                    </span>
                </div>
            </div>
            <div class="ml-5">
                @include('public.user.user-avatar', [
                    'size' => 'lg',
                    'user' => $user
                ])
            </div>
        </div>

        <div class="my-5">
            @include('public.post.post-list', [
                'title' => 'Posts',
                'posts' => $posts
            ])
        </div>
    </div>
@endsection
