<a class="text-dark"
    href="{{ route('users.show', ['user' => $user->id]) }}">
    {{ $user->name }}
</a>