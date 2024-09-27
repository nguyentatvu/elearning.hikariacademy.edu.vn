<!DOCTYPE html>
<html lang="en" dir="{{ (App\Language::isDefaultLanuageRtl()) ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="google" content="notranslate"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="{{getSetting('meta_description', 'seo_settings')}}">
    <meta name="keywords" content="{{getSetting('meta_keywords', 'seo_settings')}}">
    <meta name="csrf_token" content="{{ csrf_token() }}">
    <link rel="icon" href="/public/uploads/settings/favicon.png" type="image/x-icon" />
    <title>@yield('title') {{ isset($title) ? $title : getSetting('site_title','site_settings') }}</title>
    <!-- Bootstrap Core CSS -->
    @yield('header_scripts')
    
    <link href="{{admin_asset('font-awesome/css/font-awesome.min.css')}}" rel="stylesheet">
    <link href="{{admin_asset('css/exercise/bundle.min.css')}}" rel="stylesheet">
    <link href="{{admin_asset('css/exercise/audit.css')}}" rel="stylesheet">
    <link href="{{admin_asset('css/sweetalert.css')}}" rel="stylesheet">
</head>
<body ng-app="academia">

<div id="wrapper" class="{{$class}}">
    @yield('content')

</div>


<!-- /#wrapper -->
<!-- jQuery -->

<script src="{{admin_asset('js/jquery-1.12.1.min.js')}}"></script>
<script src="{{admin_asset('js/exercise/bootstrap.min.js')}}"></script>


@yield('footer_scripts')
@include('admin.errors.formMessages')
@yield('custom_div_end')

</body>
</html>