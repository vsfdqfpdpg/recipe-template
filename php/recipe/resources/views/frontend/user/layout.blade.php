<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    <style>
        .sidebar {
            top: 72px;
            position: sticky;
            height: calc(100vh - 72px);
            min-height: calc(100vh - 72px);
            border-radius: 8px;
        }
    </style>
</head>

<body>
    <div id="app">
        <header class="sticky-top">
            <nav class="navbar navbar-expand-lg navbar-light bg-light">
                <div class="container">
                    <a class="navbar-brand" href="{{ url('/') }}">
                        {{ config('app.name', 'Laravel') }}
                    </a>
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>

                    <div class="collapse justify-content-between navbar-collapse" id="navbarSupportedContent">
                        <!-- Left Side Of Navbar -->
                        <ul class="navbar-nav mr-auto">
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('recipe.create') }}">{{ __('Create a recipe') }}</a>
                            </li>
                        </ul>

                        <!-- Right Side Of Navbar -->
                        <ul class="navbar-nav ml-auto">
                            <!-- Authentication Links -->
                            @guest
                            @if (Route::has('login'))
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                            </li>
                            @endif

                            @if (Route::has('register'))
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                            </li>
                            @endif
                            @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }}
                                </a>

                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('profile') }}">
                                        Profile
                                    </a>
                                    <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                            @endguest
                        </ul>
                    </div>
                </div>
            </nav>
        </header>

        <div class="container mt-3">
            <div class="row">
                <aside class="bg-light col-2 sidebar p-3">
                    <ul class="nav flex-column nav-pills">
                        <li class="nav-item">
                            <a href="{{ Request::routeIs('profile') || Request::routeIs('profile.recipes') ? route('profile') : route('profile.user',['user' => $user->id]) }}" class="nav-link {{  Request::routeIs('profile') || Request::routeIs('profile.user')? 'active' : '' }}">Basic Info</a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ Request::routeIs('profile') || Request::routeIs('profile.recipes') ? route('profile.recipes') : route('profile.user.recipes', ['user'=> $user->id]) }}" class="nav-link {{  Request::routeIs('profile.recipes') || Request::routeIs('profile.user.recipes')? 'active' : '' }}">Recipes</a>
                        </li>
                    </ul>
                </aside>

                <main class="col">
                    @yield("content")
                </main>
            </div>

        </div>

    </div>
</body>

</html>