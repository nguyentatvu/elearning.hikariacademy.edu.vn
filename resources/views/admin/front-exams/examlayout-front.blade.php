<!DOCTYPE html>
<html lang="en" dir="{{ (App\Language::isDefaultLanuageRtl()) ? 'rtl' : 'ltr' }}">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="description" content="{{getSetting('meta_description', 'seo_settings')}}">
  <meta name="keywords" content="{{getSetting('meta_keywords', 'seo_settings')}}">
  <meta name="csrf_token" content="{{ csrf_token() }}">
  <link rel="icon" href="{{IMAGE_PATH_SETTINGS.getSetting('site_favicon', 'site_settings')}}" type="image/x-icon" />
  <title>@yield('title') {{ isset($title) ? change_furigana_title($title) : getSetting('site_title','site_settings') }}</title>
  <!-- Bootstrap Core CSS -->
  @yield('header_scripts')
  <link href="{{themes('site/css/main.css')}}" rel="stylesheet">
  <link href="{{themes('css/notify.css')}}" rel="stylesheet">
  <link href="{{themes('css/angular-validation.css')}}" rel="stylesheet">
  <!-- Bootstrap Core CSS -->
  <!--FontAwesome-->
  <link href="{{CSS}}sweetalert.css" rel="stylesheet" type="text/css">
  <link href="{{themes('css/front-exam.css')}}" rel="stylesheet">
  <link href="{{themes('css/plugins/morris.css')}}" rel="stylesheet">
  <link href="{{CSS}}materialdesignicons.css" rel="stylesheet" type="text/css">
  <style type="text/css" media="screen">
  .mt-51{
    width: 100%;
    padding-top: 10px;
  }
  #wrapper1{
    width: 100%;
    padding: 10px;
  }
  .panel-right-sidebar1{
    display: flex;

  }
  .mt-151{
    padding-top: 1px;
  }
  .mt-52 {
    margin-top: 180px;
}
.dis{
  display: none;
}
</style>
</head>
<body ng-app="academia" class="margin1">
  <div class="dis">
    @include('site.header')
  </div>

 @yield('custom_div')
 <?php 
 $class = '';
 if(!isset($right_bar))
  $class = 'no-right-sidebar';
$block_class = '';
if(isset($block_navigation))
  $block_class = 'non-clickable';
?>
<div id="wrapper wrapper1" class="{{$class}} mt-150 mt-151 " >
  <!-- Navigation -->
  <nav role="navigation">
  </nav>
  @if(isset($right_bar))
  <aside class=" top-sidebar mt-50 mt-51 op10" id="rightSidebar"><!--  right-sidebar  -->
    {{-- <button class="sidebat-toggle" id="sidebarToggle" href='javascript:'><i class="mdi mdi-menu"></i></button> --}}
    <?php $right_bar_class_value = ''; 
    if(isset($right_bar_class))
      $right_bar_class_value = $right_bar_class;
    ?>
    <div class="panel panel-right-sidebar {{$right_bar_class_value}} panel-right-sidebar1">
      <?php $data = '';
      if(isset($right_bar_data))
        $data = $right_bar_data;
      ?>
      @include($right_bar_path, array('data' => $data))
    </div>
  </aside>
  @endif
  @yield('content')
</div>
<div class="dis">
    @include('site.footer')
  </div>


<script src="{{themes('site/js/jquery-3.1.1.min.js')}}"></script>
<script src="{{themes('site/js/bootstrap.min.js')}}"></script>
<script src="{{themes('site/js/slider/slick.min.js')}}"></script>
<script src="{{themes('site/js/bootstrap.offcanvas.js')}}"></script>
<script src="{{themes('site/js/jRate.min.js')}}"></script>
<script src="{{themes('site/js/wow.min.js')}}"></script>
<script src="{{themes('site/js/main.js')}}"></script>
<script src="{{themes('js/notify.js')}}"></script>
{{-- <script src="{{JS}}main.js"></script> --}}
<script src="{{JS}}sweetalert-dev.js"></script>
<script src="{{JS}}mousetrap.js"></script>
<script src="{{JS}}landing-js/all.js"></script>
<!-- <script>
  window.history.forward();
  function noBack() { window.history.forward(); }
  function checkKeyCode(evt)
  {
    var evt = (evt) ? evt : ((evt) ? evt : null);
    console.log(evt.keyCode);
    var node = (evt.target) ? evt.target : ((evt.srcElement) ? evt.srcElement : null);
    if(
          evt.keyCode == 123 //F12
          || evt.keyCode==116 
          || evt.keyCode==82 || evt.keyCode==9 || evt.keyCode==18 || evt.keyCode==17 
          || evt.keyCode == 44 //PRNT SCR
          )
    {
      evt.keyCode=0;
      return false
    }
    else if(evt.keyCode==8)
    {
      evt.keyCode=0;
      return false
    }
  }
  document.onkeydown=checkKeyCode;
</script>
  <SCRIPT TYPE="text/javascript"> 
    var message="Sorry, right-click has been disabled"; 
    function clickIE() {if (document.all) {(message);return false;}} 
    function clickNS(e) {if 
      (document.layers||(document.getElementById&&!document.all)) { 
        if (e.which==2||e.which==3) {(message);return false;}}} 
        if (document.layers) 
          {document.captureEvents(Event.MOUSEDOWN);document.onmousedown=clickNS;} 
        else{document.onmouseup=clickNS;document.oncontextmenu=clickIE;} 
        document.oncontextmenu=new Function("return false") 
      </SCRIPT> 
      <SCRIPT TYPE="text/javascript"> 
        function disableselect(e){
          return false
        } 
        function reEnable(){
          return true
        } 
    //if IE4+
    document.onselectstart=new Function ("return false") 
    //if NS6
    if (window.sidebar){
      document.onmousedown=disableselect
      document.onclick=reEnable
    }
  </SCRIPT>
  <script>
    Mousetrap.bind(['ctrl+s', 'ctrl+p', 'ctrl+w', 'ctrl+u'], function(e) {
      if (e.preventDefault) {
        e.preventDefault();
      } else {
          // internet explorer
          e.returnValue = false;
        }
      }); 
  </script> -->
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
          @include('common.alertify')
          @yield('footer_scripts')
          @include('errors.formMessages')
          @yield('custom_div_end')
          {!!getSetting('google_analytics', 'seo_settings')!!}
  </body>
</html>