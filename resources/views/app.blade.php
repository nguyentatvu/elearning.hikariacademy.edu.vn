<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<link href="{{ asset('/css/app.css') }}" rel="stylesheet">
@yield('styles')
<body>
    <header id="header">
        @include('layouts.header')
    </header>
    <div id="app">
        @yield('content')
    </div>
    <footer id="footer">
        @include('layouts.footer')
    </footer>
    <script src="{{ asset('js/app.js') }}"></script>
    @yield('scripts')
</body>
</html>
