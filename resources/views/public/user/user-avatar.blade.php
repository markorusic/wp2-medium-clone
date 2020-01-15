<a href="{{ route('users.show', ['user' => $user->id]) }}">
    <img class="avatar{{ isset($size) ? '-' . $size : '' }}"
        src="{{ $user->avatar }}"
        alt="{{ $user->name }}"
    >
</a>
