<!DOCTYPE html>
<html lang="en" dir="{{ (App\Language::isDefaultLanuageRtl()) ? 'rtl' : 'ltr' }}">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="{{getSetting('meta_description', 'seo_settings')}}">
    <meta name="keywords" content="{{getSetting('meta_keywords', 'seo_settings')}}">
    <meta name="csrf_token" content="{{ csrf_token() }}">

    <title>@yield('title') {{ isset($title) ? $title : getSetting('site_title','site_settings') }}</title>

    @yield('header_scripts')

    <link href="{{ asset('/css/custom/mock-exam/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('/css/custom/mock-exam/sweetalert.css') }}" rel="stylesheet">
    <link href="{{ asset('/css/custom/mock-exam/exam.css') }}" rel="stylesheet">
    <link href="{{ asset('/css/custom/mock-exam/materialdesignicons.css') }}" rel="stylesheet">
    <link href="{{ asset('/css/custom/mock-exam/checkbox.css') }}" rel="stylesheet">
    <link href="{{ asset('/css/custom/mock-exam/font-awesome.min.css') }}" rel="stylesheet">
    <link href="{{ asset('/css/custom/mock-exam/bootstrap-datepicker.min.css') }}" rel="stylesheet">

    <!-- Alertify -->
    <link href="{{ asset('/css/custom/mock-exam/alertify.core.css') }}" rel="stylesheet">
    <link href="{{ asset('/css/custom/mock-exam/alertify.default.css') }}" rel="stylesheet">
    <script src="{{ asset('/js/client/mock-exam/alertify.js')}}"></script>

    <!-- JQuery -->
    <script src="{{ asset('/js/client/mock-exam/jquery-1.12.1.min.js')}}"></script>
</head>

<body ng-app="academia">
    @yield('custom_div')

    <?php
        $class = !isset($right_bar) ? 'no-right-sidebar' : '';
        $block_class = isset($block_navigation) ? 'non-clickable' : '';
    ?>

    <!-- Wrapper -->
    <div id="wrapper wrapper1" class="{{$class}}">
        @if(isset($right_bar))
            <div class="top-sidebar hikari-top-exam con container-fluid hikari-w150" id="hikari-top-exam"
                style="padding: 0px 10px">
                <?php $right_bar_class_value = '';
                    if(isset($right_bar_class))
                        $right_bar_class_value = $right_bar_class;
                    ?>
                <div class="top-sidebar-list">
                    <?php $data = '';
                        if(isset($right_bar_data))
                            $data = $right_bar_data;
                    ?>
                    @include($right_bar_path, array('data' => $data))
                </div>
            </div>
        @endif
        @yield('content')
    </div>
    <!-- /Wrapper -->

    <!-- Scripts -->
    <script src="{{ asset('/js/client/mock-exam/bootstrap.min.js')}}"></script>
    <script src="{{ asset('/js/client/mock-exam/main.js')}}"></script>
    <script src="{{ asset('/js/client/mock-exam/sweetalert-dev.js')}}"></script>
    <script src="{{ asset('/js/client/mock-exam/zoom.js')}}"></script>
    <script src="{{ asset('/js/plugins/mousetrap.js')}}"></script>

    <!-- Disable Back and Keys -->
    <script>
        window.history.forward();
        function noBack() { window.history.forward(); }

        function checkKeyCode(evt) {
            evt = evt || window.event;
            console.log(evt.keyCode);
            if ([123, 116, 82, 9, 18, 17, 44, 8].includes(evt.keyCode)) {
                evt.preventDefault();
                return false;
            }
        }

        document.onkeydown = checkKeyCode;
    </script>

    <!-- Disable Right-click and Selection -->
    <script type="text/javascript">
        var message = "Sorry, right-click has been disabled";

        function clickIE() { if (document.all) { alert(message); return false; } }
        function clickNS(e) { if (document.layers || document.getElementById && !document.all) { if (e.which == 2 || e.which == 3) { alert(message); return false; } } }
        if (document.layers) {
            document.captureEvents(Event.MOUSEDOWN);
            document.onmousedown = clickNS;
        } else {
            document.onmouseup = clickNS;
            document.oncontextmenu = clickIE;
        }

        document.oncontextmenu = function() { return false; };
        document.onselectstart = function() { return false; };

        if (window.sidebar) {
            document.onmousedown = disableselect;
            document.onclick = reEnable;
        }

        function disableselect(e) { return false; }
        function reEnable() { return true; }
    </script>

    <!-- Disable Print Screen and Shortcuts -->
    <script>
        Mousetrap.bind(['ctrl+s', 'ctrl+p', 'ctrl+w', 'ctrl+u'], function(e) {
            e.preventDefault ? e.preventDefault() : (e.returnValue = false);
        });
    </script>

    <!-- Fullscreen Function -->
    <script>
        function fullScreen() {
            var el = document.documentElement;
            var rfs = el.requestFullScreen || el.webkitRequestFullScreen || el.mozRequestFullScreen || el.msRequestFullScreen;

            if (rfs) {
                rfs.call(el);
            } else if (typeof window.ActiveXObject !== "undefined") {
                var wscript = new ActiveXObject("WScript.Shell");
                if (wscript) wscript.SendKeys("{F11}");
            }
        }
    </script>

    <!-- CSRF Token Refresh -->
    <script>
        var csrfToken = $('[name="csrf_token"]').attr('content');
        setInterval(refreshToken, 600000); // 1 hour

        function refreshToken() {
            $.get('refresh-csrf').done(function(data) {
                csrfToken = data; // the new token
            });
        }
    </script>

    @yield('footer_scripts')
    @include('client.mock-exam.components.formMessages')
    @yield('custom_div_end')

    {!! getSetting('google_analytics', 'seo_settings') !!}
</body>

</html>