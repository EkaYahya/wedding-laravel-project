<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Digital Wedding</title>
    <link rel="shortcut icon" href="{{ asset('favicon.svg') }}" type="image/svg+xml">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">


    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Font Awesome -->
    <script src="https://kit.fontawesome.com/811cf44b8d.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/webcam-easy@1.0.5/dist/webcam-easy.min.js"></script>
    <style>
        /* Layout Optimization for Mobile */
        body {
            max-width: 768px;
            margin: 0 auto;
            overflow-x: hidden;
        }
    </style>
</head>
<body class="bg-gray-100 font-sans antialiased">
    <div id="app">
        <!-- Main Content -->
        <main> <!-- Extra space for navbar -->
            @include('components.navbarTop')
            @yield('content')
        </main>

        <!-- Include Navbar -->
        @unless(Route::currentRouteName() === 'login')
            @include('components.navbar')
        @endunless

    </div>
</body>
</html>


