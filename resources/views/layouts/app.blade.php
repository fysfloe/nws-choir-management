<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Chorganizer') }}</title>

    <!-- Styles -->
    <link href="{{ asset('fonts/open-iconic-bootstrap.css') }}" rel="stylesheet">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body>
<div id="app">
    <nav class="navbar navbar-expand-md navbar-dark">
        <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse"
                data-target="#navContent" aria-controls="navContent" aria-expanded="false"
                aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <a class="navbar-brand" href="{{ url('/') }}">{{ config('app.name', 'Chorganizer') }}</a>

        <div class="collapse navbar-collapse">
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
                        @if (Auth::user()->avatar)
                            <div class="avatar">
                                <img src="{{ asset('/storage/avatars/' . Auth::user()->avatar) }}" alt="{{ Auth::user()->firstname . ' ' . Auth::user()->surname }}">
                            </div>
                        @endif

                        <a href="#" class="nav-link dropdown-toggle" id="navbarDropdown" data-toggle="dropdown"
                           role="button" aria-expanded="false" aria-haspopup="true">
                            {{ Auth::user()->firstname }} {{ Auth::user()->surname }} <span class="caret"></span>
                        </a>

                        <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <li class="dropdown-item">
                                <router-link to="/profile/edit/{{ Auth::user()->id }}">
                                    {{ __('My Profile') }}
                                </router-link>
                            </li>
                            <li class="dropdown-item">
                                <a href="{{ route('logout') }}"
                                   onclick="event.preventDefault();
                                        document.getElementById('logout-form').submit();">
                                    {{ __('Logout') }}
                                </a>

                                <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                      style="display: none;">
                                    {{ csrf_field() }}
                                </form>
                            </li>
                        </ul>
                    </li>
                @endguest
            </ul>
        </div>
    </nav>

    <div class="wrapper">
        <nav id="sidebar" class="navbar-nav navbar-expand-md">
            <div id="navContent" class="collapse navbar-collapse">
                <ul>
                    @guest
                    @else
                        <li class="nav-item">
                            <router-link class="nav-link" to="/">
                                <span class="oi oi-dashboard"></span>&nbsp;
                                {{ __('Dashboard') }}
                            </router-link>
                        </li>
                        <li class="nav-item">
                            <router-link to="/semesters" class="nav-link" active-class="active">
                                <span class="oi oi-calendar"></span>&nbsp;
                                {{ __('Semesters') }}
                            </router-link>
                        </li>
                        <li class="nav-item">
                            <router-link to="/projects" class="nav-link" active-class="active">
                                <span class="oi oi-project"></span>&nbsp;
                                {{ __('Projects') }}
                            </router-link>
                        </li>
                        <li class="nav-item">
                            <router-link to="/concerts" class="nav-link" active-class="active">
                                <span class="oi oi-musical-note"></span>&nbsp;
                                {{ __('Concerts') }}
                            </router-link>
                        </li>
                        @if (Auth::user()->can('manageUsers'))
                            <li class="nav-item">
                                <router-link class="nav-link" to="/admin/users" active-class="active">
                                    <span class="oi oi-people"></span>&nbsp;
                                    {{ __('Users') }}
                                </router-link>
                            </li>
                        @endif
                    @endguest
                </ul>

                <ul class="mt-3 d-block d-md-none">
                    <li class="nav-item">
                        <router-link class="nav-link" to="/profile/edit/{{ Auth::user() ? Auth::user()->id : '' }}">
                            <span class="oi oi-person"></span>&nbsp;
                            {{ __('My Profile') }}
                        </router-link>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('logout') }}"
                           onclick="event.preventDefault();
                                        document.getElementById('logout-form').submit();">
                            <span class="oi oi-account-logout"></span>&nbsp;
                            {{ __('Logout') }}
                        </a>

                        <form id="logout-form" action="{{ route('logout') }}" method="POST"
                              style="display: none;">
                            {{ csrf_field() }}
                        </form>
                    </li>
                </ul>
            </div>
        </nav>
        <section id="content" class="container">
            <div class="flash-message">
                @foreach (['danger', 'warning', 'success', 'info'] as $msg)
                    @if(Session::has('alert-' . $msg))
                        <p class="alert alert-{{ $msg }}">
                            {{ Session::get('alert-' . $msg) }} <a href="#" class="close" data-dismiss="alert"
                                                                   aria-label="close">&times;</a>
                        </p>
                    @endif
                @endforeach
            </div> <!-- .flash-message -->

            <flash-message></flash-message>

            @if (isset($errors) && count($errors) > 0)
                <p class="alert alert-danger">
                    {{ __('There were errors with your input. Check the form.') }} <a href="#" class="close"
                                                                                      data-dismiss="alert"
                                                                                      aria-label="close">&times;</a>
                </p>
            @endif

            @if (isset($breadcrumbs))
                {!! $breadcrumbs->render() !!}
            @endif

            <router-view></router-view>
            @yield('content')
        </section>
    </div>
</div>

<!-- Scripts -->
<script src="{{ asset('js/app.js') }}"></script>
</body>
</html>
