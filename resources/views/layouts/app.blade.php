<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Styles -->
    <link href="{{ asset('fonts/open-iconic-bootstrap.css') }}" rel="stylesheet">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-dark">
            <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <a class="navbar-brand" href="{{ url('/dashboard') }}">{{ config('app.name', 'Chorganizer') }}</a>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <!-- Left Side Of Navbar -->
                <div class="mr-auto">&nbsp;</div>

                <!-- Right Side Of Navbar -->
                <ul class="nav navbar-nav navbar-right">
                    <!-- Authentication Links -->
                    @guest
                        <li class="nav-item"><a class="nav-link" href="{{ route('login') }}">Login</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{ route('register') }}">Register</a></li>
                    @else
                        <li class="nav-item dropdown">
                            <a href="#" class="nav-link dropdown-toggle" id="navbarDropdown" data-toggle="dropdown" role="button" aria-expanded="false" aria-haspopup="true">
                                {{ Auth::user()->firstname }} {{ Auth::user()->surname }} <span class="caret"></span>
                            </a>

                            <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                                <li class="dropdown-item">
                                    <a href="{{ route('profile.edit', Auth::user()->id) }}">
                                        {{ trans('My Profile') }}
                                    </a>
                                </li>
                                <li class="dropdown-item">
                                    <a href="{{ route('logout') }}"
                                        onclick="event.preventDefault();
                                        document.getElementById('logout-form').submit();">
                                        {{ trans('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                        {{ csrf_field() }}
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @endguest
                </ul>
            </div>
        </nav>

        @include('layouts.modals')

        <div class="wrapper">
            <nav id="sidebar">
                <ul class="navbar-nav">
                    @guest
                    @else
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('concerts') }}">
                                <span class="oi oi-musical-note"></span>&nbsp;
                                {{ trans('Concerts') }}
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('rehearsals') }}">
                                <span class="oi oi-infinity"></span>&nbsp;
                                {{ trans('Rehearsals') }}
                            </a>
                        </li>
                        @if (Auth::user()->can('manageUsers'))
                            <li class="nav-item">
                                <a class="nav-link" href="{{ url('/admin/users') }}">
                                    <span class="oi oi-people"></span>&nbsp;
                                    {{ trans('Users') }}
                                </a>
                            </li>
                        @endif
                        @if (Auth::user()->can('manageVoices'))
                            <li class="nav-item">
                                <a class="nav-link" href="{{ url('/admin/voices') }}">
                                    <span class="oi oi-pulse"></span>&nbsp;
                                    {{ trans('Voices') }}
                                </a>
                            </li>
                        @endif
                    @endguest
                </ul>
            </nav>
            <section id="content" class="container">
                <div class="flash-message">
                    @foreach (['danger', 'warning', 'success', 'info'] as $msg)
                        @if(Session::has('alert-' . $msg))
                            <p class="alert alert-{{ $msg }}">
                                {{ Session::get('alert-' . $msg) }} <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                            </p>
                        @endif
                    @endforeach
                </div> <!-- .flash-message -->

                @if (isset($errors) && count($errors) > 0)
                    <p class="alert alert-danger">
                        {{ trans('There were errors with your input. Check the form.') }} <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                    </p>
                @endif

                @yield('content')
            </section>
        </div>
    </div>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}"></script>
</body>
</html>
