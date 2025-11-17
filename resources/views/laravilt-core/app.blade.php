<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ \Laravilt\Support\Utilities\Translator::direction() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @stack('styles')
</head>
<body class="{{ session('theme', 'light') }}">
    <div id="laravilt-app">
        @yield('content')
    </div>

    @stack('scripts')
</body>
</html>
