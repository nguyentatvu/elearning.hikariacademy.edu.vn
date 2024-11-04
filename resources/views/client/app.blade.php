<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Hikari Elearning</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
    <link href="{{ asset('/css/bootstrap.css') }}" rel="stylesheet">
    <link href="{{ asset('/css/app.css') }}" rel="stylesheet">
    @yield('styles')

    <style>
        body {
            overflow: hidden;
        }

        header#header {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            z-index: 99999;
            background: #ffffff;
            border: 1px solid #e6e6e6;
            padding: 2px 0;
            box-shadow: rgba(0, 0, 0, 0.09) 0px 1px 5px;
        }

        .main-content {
            position: relative;
            overflow: scroll;
            overflow-x: hidden;
        }

        .main-content .content {
            min-height: 100vh;
        }
    </style>
</head>

<body>
    <div class="layout-wrapper">
        @if (!Request::is('detail*'))
            <header id="header">
                @include('client.layouts.header')
            </header>
        @else
            <header id="header">
                @include('client.layouts.header-study')
            </header>
        @endif

        <div class="main-content">
            <div class="d-flex content">
                @if (!Request::is('detail*') && !Request::is('mypage*'))
                    <aside class="sidebar" id="sidebar">
                        @include('client.layouts.sidebar')
                    </aside>
                @endif
                <div class="container-fluid">
                    <div id="main-wrapper">
                        @yield('content')
                    </div>
                    @component('client.components.common-component')
                    @endcomponent
                    <div class="loading-overlay">
                        <div class="loading-spinner"></div>
                    </div>
                </div>
            </div>
            <footer id="footer">
                @if (!Request::is('detail*'))
                    @include('client.layouts.footer')
                @else
                    @include('client.layouts.footer-study')
                @endif
            </footer>
        </div>
    </div>
    <script src="{{ asset('js/app.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset('js/client/common.js') }}"></script>
    <script>
        $(document).ready(function() {
            let isHidden = false;

            // Helper function to get the outer height of an element or return 0 if not found
            const getOuterHeight = selector => $(selector).outerHeight() || 0;

            const adjustLayout = () => {
                const windowHeight = $(window).height(); // jQuery method for consistency
                const headerHeight = getOuterHeight('#header');
                const sideBarHeight = getOuterHeight('#sidebar');
                const footerHeight = getOuterHeight('#footer');

                const contentHeight = windowHeight - headerHeight;
                // Check if the screen width is for mobile or tablet
                if (window.matchMedia('(max-width: 1024px)').matches) {
                    const contentHeight = windowHeight - headerHeight - sideBarHeight;
                    $('.main-footer').css({
                        'padding-bottom': sideBarHeight + 10,
                    });
                }

                if ($('.layout-my-page').length == 0) {
                    $('.main-content').css({
                        'margin-top': headerHeight,
                        'height': contentHeight,
                    });
                } else {
                    const navigateBackHeight = $('.navigate-back').outerHeight() || 0;
                    const contentHeight = windowHeight - headerHeight;

                    $('.main-content').css({
                        'margin-top': headerHeight + 'px',
                        'height': `calc(100vh - ${headerHeight}px)`,
                    });
                }
            };

            function openModalStreak() {
                $('#modalLoginStreak').modal('show');
            }

            @if (Auth::check())
                if (!localStorage.getItem("firstLoginShown")) {
                    openModalStreak();

                    localStorage.setItem("firstLoginShown", "true");
                }
                $('.owned-login-streak').on('click', function() {
                    openModalStreak();
                })
            @else
                localStorage.removeItem("firstLoginShown");
            @endif

            adjustLayout();
            $(window).resize(adjustLayout); // Adjust layout on window resize
        });
    </script>
    @if (Auth::check())
        @include('client.components.streak');
    @endif
    @yield('scripts')
</body>

</html>
