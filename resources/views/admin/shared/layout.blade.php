@include('admin.shared.head')
<body>
    <div id="app" class="d-flex">
        <div class="sidebar py-3">
            <div class="container">
                <a class="navbar-brand text-light font-weight-bold" href="{{ route('admin.home') }}">
                    {{ config('app.name', 'Laravel') }}
                </a>
            </div>
            <ul class="list-group list-group-flush">
                <li class="list-group-item">
                    <a href="{{ route('admin.users.index-view') }}" class="text-light d-block">Users</a>
                </li>
                <li class="list-group-item">
                    <a href="{{ route('admin.posts.index-view') }}" class="text-light d-block">Posts</a>
                </li>
                <li class="list-group-item">
                    <a href="{{ route('admin.comments.index-view') }}" class="text-light d-block">Comments</a>
                </li>
                <li class="list-group-item">
                    <a href="{{ route('admin.categories.index-view') }}" class="text-light d-block">Categories</a>
                </li>
            </ul>
        </div>
        <div class="main-content">
            <nav class="navbar navbar-expand-md navbar-light border-bottom">
                <div class="container">
                    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                        <span class="navbar-toggler-icon"></span>
                    </button>

                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <!-- Left Side Of Navbar -->
                        <ul class="navbar-nav mr-auto">

                        </ul>

                        <!-- Right Side Of Navbar -->
                        <ul class="navbar-nav ml-auto">
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link text-light dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ auth('admin')->user()->name }} <span class="caret"></span>
                                </a>

                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('admin.logout') }}"
                                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                                    >
                                        {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('admin.logout') }}" method="POST" style="display: none;">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>
            <main class="py-3">
                @yield('content')
            </main>
        </div>
    </div>

    <!-- Scripts -->
    @include('admin.shared.scripts')
</body>
</html>
