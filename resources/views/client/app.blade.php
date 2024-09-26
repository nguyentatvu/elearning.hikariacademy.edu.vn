<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Hikari elearning</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    <link href="{{ asset('/css/bootstrap.css') }}" rel="stylesheet">
    <link href="{{ asset('/css/app.css') }}" rel="stylesheet">
    @yield('styles')

    <style>
        header#header {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            z-index: 99999;
            background: #ffffff;
        }

        aside#sidebar {
            position: sticky;
            top: 60px;
            left: 0;
            width: 120px;
            height: 100%;
            z-index: 1000;
            background: #ffffff;
        }

        .main-content {
            margin-top: 60px;
            position: relative;
        }
    </style>
</head>

<body>
    <div class="layout-wrapper">
        <header id="header">
            @include('client.layouts.header')
        </header>
        <div class="d-flex">
            @if (strpos(request()->path(), 'mypage') === false)
                <aside class="sidebar" id="sidebar" style="height: 100%">
                    @include('client.layouts.sidebar')
                </aside>
            @endif
            <div class="container-fluid main-content">
                <div id="main-wrapper">
                    @yield('content')
                </div>
                @component('client.components.auth-modal') @endcomponent
                <div class="loading-overlay">
                    <div class="loading-spinner"></div>
                </div>
            </div>
        </div>
    </div>

    <footer id="footer">
        @include('client.layouts.footer')
    </footer>

    <script src="{{ asset('js/app.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset('js/client/common.js') }}"></script>
    @yield('scripts')
    <script></script>
</body>

</html>
