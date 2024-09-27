<!DOCTYPE html>
<html lang="en">
<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>@yield('title') {{ isset($title) ? $title : getSetting('site_title','site_settings') }}</title>
  <meta name="csrf_token" content="{{ csrf_token() }}">
  @yield('header_scripts')
  <!-- plugins:css -->
  <link rel="stylesheet" href="/Themes/themeone/assets/student/vendors/mdi/css/materialdesignicons.min.css">
  <link rel="stylesheet" href="/Themes/themeone/assets/student/vendors/css/vendor.bundle.base.css">
  <!-- endinject -->
  <!-- Plugin css for this page -->
  <!-- End plugin css for this page -->
  <!-- inject:css -->
  <link href="{{admin_asset('font-awesome/css/font-awesome.min.css')}}" rel="stylesheet">
  <link rel="stylesheet" href="/Themes/themeone/assets/student/css/horizontal-layout/style.css">
  <link href="{{admin_asset('css/sweetalert.css')}}" rel="stylesheet">
  <link href="{{admin_asset('css/sb-admin-site.css')}}" rel="stylesheet">
  <!-- endinject -->
  <link rel="shortcut icon" href="/public/uploads/settings/favicon.png" />
  <!-- Global site tag (gtag.js) - Google Analytics -->
  <script async src="https://www.googletagmanager.com/gtag/js?id=UA-149456320-1"></script>
  <script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());
    gtag('config', 'UA-149456320-1');
  </script>
</head>
<body>
  <div class="container-scroller">
    @include('admin.site.header')
    <div class="container-fluid page-body-wrapper">
      <div class="main-panel">
        <div class="content-wrapper">
                   @yield('content')
        </div>
        @include('admin.site.footer')
      </div>
      <!-- main-panel ends -->
    </div>
    <!-- page-body-wrapper ends -->
  </div>
  <script src="{{admin_asset('js/jquery-1.12.1.min.js')}}"></script>
  <script src="/Themes/themeone/assets/student/vendors/js/vendor.bundle.base.js"></script>
  <script src="/Themes/themeone/assets/student/js/template.js"></script>
  <script src="/Themes/themeone/assets/student/js/dashboard.js"></script>
  <script src="{{admin_asset('js/main.js')}}"></script>
  <script src="{{admin_asset('js/sweetalert-dev.js')}}"></script>
  <style type="text/css">
  	.main-panel {
  	    background: #fff;
  	    width: 100%;
  	}
  	.panel.panel-primary {
	    border: 1px solid #347ab6;
	}
	.panel.panel-danger {
	    border: 1px solid #f2dede;
	}
  </style>
  <script>
	var csrfToken = $('[name="csrf_token"]').attr('content');
      setInterval(refreshToken, 600000); // 1 hour 
      function refreshToken(){
      	$.get('refresh-csrf').done(function(data){
              csrfToken = data; // the new token
          });
      }
      setInterval(refreshToken, 600000); // 1 hour 
  </script>
  @include('admin.common.alertify')
  @yield('footer_scripts')
  @include('admin.errors.formMessages')
  @yield('custom_div_end')
</body>
</html>