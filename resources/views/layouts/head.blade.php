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
    </style>

    @yield('style')
</head>
