<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="google" content="notranslate"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="csrf_token" content="{{ csrf_token() }}">
    <link rel="icon" href="/public/uploads/settings/favicon.png" type="image/x-icon" />
    <title>
    Hikari Academy -  @yield('title') {{ isset($title) ? $title : getSetting('site_title','site_settings') }}
    </title>
    @yield('header_scripts')
    <link href="{{admin_asset('css/ihover.min.css')}}" rel="stylesheet">
   <link href="{{admin_asset('site/css/main.css')}}" rel="stylesheet">
   <link href="{{admin_asset('css/notify.css')}}" rel="stylesheet">
   <link href="{{admin_asset('css/angular-validation.css')}}" rel="stylesheet">
    <link href="{{admin_asset('css/sweetalert.css')}}" rel="stylesheet">
    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-149456320-1"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());
      gtag('config', 'UA-149456320-1');
    </script>

</head>
<body ng-app="academia">
    <!-- Navigation -->
    <!-- include('site.header') -->
    @yield('content')
    <!-- include('site.footer') -->
    <!-- jQuery -->
      <script src="{{admin_asset('site/js/wow.min.js')}}"></script>
      <script>
        wow = new WOW(
          {
            animateClass: 'animated',
            offset:       100,
            callback:     function(box) {
              console.log("WOW: animating <" + box.tagName.toLowerCase() + ">")
            }
          }
        );
        wow.init();
        document.getElementById('moar').onclick = function() {
          var section = document.createElement('section');
          section.className = 'section--purple wow fadeInDown';
          this.parentNode.insertBefore(section, this);
        };
      </script>
      <script src="{{admin_asset('site/js/jquery-3.1.1.min.js')}}"></script>
      <script src="{{admin_asset('site/js/bootstrap.min.js')}}"></script>
      <script src="{{admin_asset('site/js/slider/slick.min.js')}}"></script>
      <script src="{{admin_asset('site/js/bootstrap.offcanvas.js')}}"></script>
      <script src="{{admin_asset('site/js/jRate.min.js')}}"></script>
      <script src="{{admin_asset('site/js/wow.min.js')}}"></script>
      <script src="{{admin_asset('site/js/main.js')}}"></script>
      <script src="{{admin_asset('js/notify.js')}}"></script>
      <script src="{{admin_asset('js/sweetalert-dev.js')}}"></script>
        @include('admin.errors.formMessages')
      <script>
        function showSubscription(use_first = ''){
        if(use_first == 'yes'){
        var user_email  = $("#email").val();
        }
        else{
        var user_email  = $("#email1").val();
        }
      var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
      if(!re.test(user_email))
      {
          showMessage('Sorry','Please enter a valid email','error');
          return;
      }
     else{
         $.ajax({
                url      : '{{ URL_SAVE_SUBSCRIPTION_EMAIL }}',
                type     : 'post',
                data: {
                useremail    : user_email,
                '_token'     : $('[name="csrf_token"]').attr('content')
                },
                success: function( response ){
                    var email_staus  = $.parseJSON(response);
                     if(email_staus.status == 'existed'){
                        showMessage('Ok','You are already subscribed','info');
                     }
                     else{
                        showMessage('Success','You are subscription was successfull','success'); 
                     }
                 }
            });
           var mytext  = ''  
           $("#email").val(mytext);
           $("#email1").val(mytext);
    }
  function showMessage(title,msg,type){
// console.log(u_title);
   $(function(){
            PNotify.removeAll();
            new PNotify({
                title: title,
                text: msg,
                type: type,
                delay: 2000,
                shadow: true,
                width: "300px",
                animate: {
                            animate: true,
                            in_class: 'fadeInLeft',
                            out_class: 'fadeOutRight'
                        }
                });
        });
  }
}
      </script>
   @yield('footer_scripts')
    
</body>
</html>