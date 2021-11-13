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
        .top-nav {
            position: fixed;
            width: 100%;
            z-index: 1024;
            left: 0;
        }

        .sidebar {
            position: sticky;
            height: 100vh;
            top: 0;
            z-index: 100;
            padding: 55px 0 0;
            box-shadow: inset -1px 0 0 rgb(0 0 0 / 10%);
        }

        .right {
            padding-top: 68px;
        }
    </style>
</head>

<body>
    <div class="container-fluid">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm top-nav">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    {{ config('app.name', 'Laravel') }}
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse justify-content-between" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav mr-auto">

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

                            <div class="dropdown-menu dropdown-menu-end " aria-labelledby="navbarDropdown">
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

        <div class="row">
            <nav id="sidebarMenu" class="bg-light col-lg-2 col-md-3 collapse d-md-block px-5 sidebar">
                <div class="position-sticky pt-3">
                    <ul class="nav flex-column nav-pills">
                        <li class="nav-item">
                            <a class="nav-link {{  Request::is('admin/users/*') || Request::is('admin/users')? 'active' : '' }}" aria-current="page" href="{{ route('admin.users') }} ">
                                Users
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{  Request::is('admin/recipes/*') || Request::is('admin/recipes')? 'active' : '' }}" href="{{ route('admin.recipes') }} ">
                                Recipes
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{  Request::is('admin/roles/*') || Request::is('admin/roles')? 'active' : '' }}" href="{{ route('admin.roles') }} ">
                                Roles
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{  Request::is('admin/permissions/*') || Request::is('admin/permissions')? 'active' : '' }}" href="{{ route('admin.permissions') }} ">
                                Permissions
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>
            <main class="col right">
                @yield('content')
            </main>
        </div>

    </div>
</body>

</html>