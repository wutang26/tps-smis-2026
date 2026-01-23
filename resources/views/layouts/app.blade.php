<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'TPS - SMIS')</title>

    <!-- Meta -->
    <meta name="description" content="System for facilitating essential functions of TPS Administration" />
    <meta name="author" content="Tanzania Police School" />

    <link rel="canonical" href="{{ url('/') }}">
    <meta property="og:url" content="{{ url('/') }}">
    <meta property="og:title" content="TPS - Moshi | Tanzania Police School">
    <meta property="og:description" content="System for facilitating essential functions of TPS Administration">
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="TPS - Moshi">

    <link rel="shortcut icon" href="{{ asset('assets/images/police-tz-logo.png') }}" />

    <!-- Theme CSS -->
    <link rel="stylesheet" href="{{ asset('assets/fonts/bootstrap/bootstrap-icons.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/main.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/custom.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/mine.css') }}" />

    <!-- Vendor CSS -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/overlay-scroll/OverlayScrollbars.min.css') }}" />

    <!-- Select2 -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />

    <!-- SweetAlert -->
    <script src="{{ asset('assets/js/sweet_alert.js') }}"></script>

    <style>
        .error { color: red; font-size: 15px; }
        .table-responsive td, .table-responsive th { font-weight: normal; }

        .swal2-popup {
            max-width: 90vw !important;
            box-sizing: border-box;
            word-wrap: break-word;
            white-space: normal;
            font-size: 1rem;
            padding: 1rem;
        }

        body { overflow-x: hidden; }
        .card, .card-body, .form-control, .form-select {
            max-width: 100%;
            box-sizing: border-box;
        }
    </style>

    @yield('style')
</head>

<body>
    <div id="app">
        <!-- Navbar -->
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container-fluid"><!-- use fluid container to avoid overflow -->
                <a class="navbar-brand" href="{{ url('/') }}">
                    Laravel 11 User Roles and Permissions - TPS RMS
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav me-auto"></ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ms-auto">
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
                            <li><a class="nav-link" href="{{ route('users.index') }}">Manage Users</a></li>
                            <li><a class="nav-link" href="{{ route('roles.index') }}">Manage Role</a></li>
                            <li><a class="nav-link" href="{{ route('products.index') }}">Manage Product</a></li>
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }}
                                </a>
                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
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

        <!-- Main content -->
        <main class="py-4">
            <div class="container-fluid"><!-- fluid to take full width -->
                <div class="row justify-content-center mx-0"><!-- remove negative margin -->
                    <div class="col-12"><!-- full width on all screens -->
                        <div class="card">
                            <div class="card-body">
                                @yield('scrumb')
                                @yield('content')
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>

    </div>
</body>
</html>
