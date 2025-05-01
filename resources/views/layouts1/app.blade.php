<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Persephoneresto') }}</title>

    <script src="{{ mix('js/app.js') }}" defer></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Montserrat:wght@300;400;700&display=swap" rel="stylesheet">

    <link href="{{ mix('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/custom1.css') }}" rel="stylesheet">
    <link href="{{ asset('font/css/all.min.css') }}" rel="stylesheet">
</head>

<body>
    <div id="app">
        @include('layouts1.navbar', ['menus' => $menus])
        <main class="py-4">
            @yield('content')
        </main>
        @include('layouts1.footer')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @yield('script') {{-- Tambahkan ini --}}
</body>

</html>