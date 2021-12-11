<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->currentLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="mobile-web-app-capable" content="yes">

        <meta name="theme-color" content="#282828">
        <meta name="msapplication-navbutton-color" content="#282828">

        <meta name="msapplication-config" content="" />

        <title>{{ config('app.name', 'manage.yourShares') }}</title>
        
        <link rel="icon" type="image/x-icon" href="" />
        <link rel="icon" href="">
        <link rel="apple-touch-icon" href="">

        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <!-- Styles -->
        <link href="{{ asset('css/app.css') }}" rel="stylesheet">

        @env('production')
        <script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
        @endenv

    </head>
    <body>
        <div id="app"></div>

        <noscript>This site requires JavaScript to run. Please enable it.</noscript>

        <!-- Scripts -->
        <script src="{{ asset('js/app.js') }}"></script>
    </body>
</html>
