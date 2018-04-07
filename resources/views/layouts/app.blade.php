<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="apple-touch-icon" sizes="57x57" href="{{ asset('favicon/apple-icon-57x57.png') }}">
    <link rel="apple-touch-icon" sizes="60x60" href="{{ asset('favicon/apple-icon-60x60.png') }}">
    <link rel="apple-touch-icon" sizes="72x72" href="{{ asset('favicon/apple-icon-72x72.png') }}">
    <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('favicon/apple-icon-76x76.png') }}">
    <link rel="apple-touch-icon" sizes="114x114" href="{{ asset('favicon/apple-icon-114x114.png') }}">
    <link rel="apple-touch-icon" sizes="120x120" href="{{ asset('favicon/apple-icon-120x120.png') }}">
    <link rel="apple-touch-icon" sizes="144x144" href="{{ asset('favicon/apple-icon-144x144.png') }}">
    <link rel="apple-touch-icon" sizes="152x152" href="{{ asset('favicon/apple-icon-152x152.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('favicon/apple-icon-180x180.png') }}">
    <link rel="icon" type="image/png" sizes="192x192" href="{{ asset('favicon/android-icon-192x192.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="96x96" href="{{ asset('favicon/favicon-96x96.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon/favicon-16x16.png') }}">
    <link rel="manifest" href="{{ asset('favicon/manifest.json') }}">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="{{ asset('favicon/ms-icon-144x144.png') }}">
    <meta name="theme-color" content="#ffffff">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name') }} - Simplifying shift exchanges</title>

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body>
    <div id="app" class="main-content">
        <div class="navbar-wrapper">

            <div class="container">
                <nav class="navbar navbar-expand-lg navbar-dark pt-4 pb-4 mb-5">
                    <a class="navbar-brand" href="{{ url('/') }}">
                        <img alt="Brand" height="28" src="{{ asset('images/logo.svg') }}">
                    </a>
                    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarContent" aria-controls="navbarContent" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>

                    <div class="collapse navbar-collapse" id="navbarContent">
                        <ul class="navbar-nav mr-auto">
                            @if (! Auth::guest())
                                @if (Auth::user()->isAdmin())
                                    <li class="nav-item">
                                        <a class="nav-link {{ Route::is('exchanges.index') ? 'active': '' }}" href="{{ route('exchanges.index') }}">Exchanges</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="/groups/all">Groups</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link {{ Route::is('settings.edit') ? 'active': '' }}" href="{{ route('settings.edit') }}">Settings</a>
                                    </li>
                                @else
                                    <li class="nav-item">
                                        <a class="nav-link {{ Route::is('courses.index') ? 'active': '' }}" href="{{ route('courses.index') }}">Courses</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="/groups/index">Groups</a>
                                    </li>
                                @endif
                            @endif
                        </ul>
                        <ul class="navbar-nav">
                            @if (Auth::guest())
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('login') }}">Login</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">Register</a>
                                </li>
                            @else
                                <li class="nav-item dropdown">
                                    <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                        {{ Auth::user()->name }}
                                        <span class="caret"></span>
                                    </a>

                                    <div class="dropdown-menu dropdown-menu-right" role="menu">
                                        @if (Auth::user()->isStudent())
                                            <a class="dropdown-item disabled" href="#">Logged in as {{ Auth::user()->student->student_number }}</a>
                                            <div class="dropdown-divider"></div>
                                        @endif
                                        <form id="logout-form" action="{{ route('logout') }}" method="POST">
                                            {{ csrf_field() }}
                                            <button class="dropdown-item" type="submit">
                                                Sign out
                                            </button>
                                        </form>
                                    </li>
                                </li>
                            @endif
                        </ul>
                    </div>
                </div>
            </nav>
        </div>

        <div class="container">
            @include('flash::message')
        </div>

        <div class="container mb-5">
            @yield('content')
        </div>
    </div>

    <footer class="main-footer">
        <div class="container">
            <div class="row align-items-center p-4 text-muted small">
                <div class="col">
                    Developed with love by <a target="_blank" href="//hackathonners.org">Hackathonners</a>.
                    <br>
                    Follow us on <a target="_blank" href="//twitter.com/Hackathonners">Twitter</a> / <a target="_blank" href="//github.com/Hackathonners">Github</a> / <a target="_blank" href="//blog.hackathonners.org">Medium</a>.
                </div>
                <div class="col-2 text-center">
                    <img alt="Brand" height="28" src="{{ asset('images/logo-icon.svg') }}">
                </div>
                <div class="col d-none d-md-block text-right">
                    The source code is publicly available on <a target="_blank" href="//github.com/Hackathonners/swap">Github</a>.
                </div>
            </div>
        </div>
    </div>
</footer>

<!-- Scripts -->
<script src="{{ asset('js/app.js') }}"></script>
</body>
</html>
