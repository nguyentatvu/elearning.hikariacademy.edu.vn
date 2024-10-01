<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Hikari Elearning - Detail</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
    <link href="{{ asset('/css/bootstrap.css') }}" rel="stylesheet">
    <link href="{{ asset('/css/app.css') }}" rel="stylesheet">
    @yield('styles')

    <style>
        html, body {
            overflow-x: hidden;
        }

        .layout-wrapper {
            position: relative;
        }

        header {
            width: 100%;
            z-index: 99999;
            background: #ffffff;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
        }

        .main-wrapper {
            position: relative;
            margin-top: 90px;
        }

        footer {
            position: fixed;
            width: 100%;
            bottom: 0;
            left: 0;
            right: 0;
        }

    </style>
</head>

<body>
    <div class="layout-wrapper">
        <header>
            @include('client.layouts.header-study')
        </header>

        <div class="main-content">
            <div id="main-wrapper">
                @yield('content')
            </div>
        </div>

        @component('client.components.common-component')
        @endcomponent
        @component('client.components.auth-modal')
        @endcomponent

        <div class="loading-overlay">
            <div class="loading-spinner"></div>
        </div>

        <footer>
            @include('client.layouts.footer-study')
        </footer>
    </div>

    <script src="{{ asset('js/app.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset('js/client/common.js') }}"></script>
    @yield('scripts')
</body>

</html>
