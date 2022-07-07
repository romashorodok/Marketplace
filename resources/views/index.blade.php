<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        <link href="{{ asset('css/app.css') }}" rel="stylesheet">

{{--        <script type="text/javascript" src="{{ asset('js/app.js') }}" defer></script>--}}
    </head>
    <body class="min-vh-100 d-flex justify-content-center align-items-center">
        <div id="app">
            @yield('content')
{{--            <example-component></example-component>--}}
        </div>

    </body>
</html>
