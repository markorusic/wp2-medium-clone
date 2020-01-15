@if (auth()->id() !== $user->id)
    @php $isFollowing = $user->isFollowing(); @endphp
    <div>
        <a href="#" class="btn btn{{ $isFollowing ? '' : '-outline' }}-success"
            data-user-action="follow"
            data-user-id="{{ $user->id }}"
        >
            <i class="fa fa-user-o mr-2"></i>
            <span>{{ $isFollowing ? 'Following' : 'Follow' }}</span>
        </a>
    </div>
@endif