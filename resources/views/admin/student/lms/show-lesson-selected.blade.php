@extends('admin.layouts.student.studentlayout')



@section('header_scripts')

<link href="{{admin_asset('css/file/application.css')}}" rel="stylesheet">

<style>
  .vjs-current-timel {
    display: block!important;
  }
  .vjs-duration {
    display: block!important;
  }
  .content-wrapper{
    max-width: 100%!important;
  }
  .video-js{
    width: 100%!important;
    height: auto!important;
  }



  #accordian h3 a {
    padding: 0 10px;
    font-size: 15px;
    line-height: 34px;
    display: block;
    color: #232731;
    text-decoration: none;
    position: relative;
  }

  #accordian h3:hover {
    text-shadow: 0 0 1px rgba(255, 255, 255, 0.7);
  }

  i {
    margin-right: 10px;
  }
  #accordian ul{
    padding-left: 5px;
  }
  #accordian li {
    list-style-type: none;
  }

  #accordian ul ul li a,
  #accordian h4 {
    color: #232731;
    text-decoration: none;
    font-size: 13px;
    line-height: 27px;
    display: block;
    padding: 0 15px;
    transition: all 0.15s;
    position: relative;
    font-weight: 500;
  }

  #accordian ul ul li a:hover {
    background: #185384;
    border-left: 3px solid #f48133;
    color: #fafaf4!important;
  }

  #accordian ul ul {
    display: none;
  }

  #accordian li.active>ul {
    display: block;
  }

  #accordian ul ul ul {
    margin-left: 15px;
    border-left: 1px dotted rgba(0, 0, 0, 0.5);
  }

  #accordian ul li ul {
    margin-left: 15px;
    border-left: 1px dotted rgba(0, 0, 0, 0.5);
  }
  #accordian a:not(:only-child):after {
    content: "\f104";
    font-family: fontawesome;
    position: absolute;
    right: 15px;
    top: 0;
    font-size: 14px;
    /*color: #232731;*/
  }

  #accordian .active>a:not(:only-child):after {
    content: "\f107";
  }

  #accordian h3 a:after {
    content: "\f104";
    font-family: fontawesome;
    position: absolute;
    right: 15px;
    font-size: 14px;
  }
  #accordian .active> h3 a:after {
    content: "\f107";
  }
  #accordian ul li a i {
    color: #2196f3;
  }
</style>
@stop

@section('content')


<nav aria-label="breadcrumb">

  <ol class="breadcrumb breadcrumb-custom bg-inverse-info">

    <li class="breadcrumb-item"><a href="/home"><i class="mdi mdi-home menu-icon"></i></a></li>
    <li class="breadcrumb-item"><a href="{{PREFIX.'lms/exam-categories/list'}}"><span>Khóa học</span></a></li>
    <li class="breadcrumb-item active" aria-current="page"><span>{{ $current_series->title }}</span></li>



  </ol>

</nav>

<div class="row">

  <div class="col-md-12">

    <div id="page-wrapper">

      <div class="container-fluid" id="wrapper_new_lecture">

        <div class="content_lecture">

          <div class="btn_show_lecture hidden-xs hidden-sm" style="display: none;">

            <div class="icon_show_lecture" style="-webkit-mask:url({{admin_asset('css/file/chevron-left-24-px@2x.png')}}) no-repeat 50% 50%;-webkit-mask-box-image:url(./files/chevron-left-24-px@2x.png);mask:url(./files/chevron-left-24-px@2x.png) no-repeat 50% 50%;-webkit-mask-size: 100% !important;">

            </div>

            <span style="display: none;">

              BÀI HỌC

            </span>

          </div>

          <div class="show_lecture_course col-md-3 pull-left" style="display: block;">

            <div class="top_show_lecture">

              <div class="wrapper_curriculums">

                <div class="curriculums check_active active">

                  BÀI HỌC

                </div>

              </div>

              <div class="btn_show_hide_lecture pull-right">

                <div class="icon_chevron_left" style="-webkit-mask:url({{admin_asset('css/file/chevron-left-24-px@2x.png')}}) no-repeat 50% 50%;-webkit-mask-box-image:url(./files/chevron-left-24-px@2x.png);mask:url(./files/chevron-left-24-px@2x.png) no-repeat 50% 50%;-webkit-mask-size: 100% !important;">

                </div>

              </div>

            </div>

            <div aria-expanded="true" class="content_show_lecture" style="display: block;">

             <div id="accordian">
               <ul>
                <?php echo $lesson_menu; ?>
              </ul>
            </div>

          </div>

        </div>

        <div class="show_video_course col-md-12 col-xs-12 lecture-player" style="">

          <div class="wrap_content_video" style="height: 1036px;">

          </div>

          <div class="top_video_course_mobile hidden-md hidden-lg">

            <div class="show_navleft">

              <div class="img" style="-webkit-mask:url({{admin_asset('css/file/format-list-bulleted-24-px@2x.png')}}) no-repeat 50% 50%;-webkit-mask-box-image:url({{admin_asset('css/file/format-list-bulleted-24-px@2x.png')}});mask:url({{admin_asset('css/file/format-list-bulleted-24-px@2x.png')}}) no-repeat 50% 50%;-webkit-mask-size: 100% !important;">

              </div>

            </div>


          </div>

          <div class="content_video_course">

            <div class="top_video_course hidden-xs hidden-sm pt-1">

              <div class="name_course" style="margin: 16px; font-weight: 20px">

                {{$current_lesson}}

              </div>

            </div>

            <div id="edm_player_zone" style="">

              <div class="row lecture-player-main no-margin" video="{{$current_video}}">
                <video id="video-js" class="video-js vjs-theme-fantasy">
                  <source src="{{$current_video}}" type='application/x-mpegURL' label='1080' selected='true'/>
                  <source src="{{$current_video}}" type='application/x-mpegURL'
                  label='720'/>
                  <source src="{{$current_video}}" type='application/x-mpegURL'
                  label='480'/>
                  <source src="{{$current_video}}" type='application/x-mpegURL'
                  label='240'/>
                  <p class="vjs-no-js">
                    To view this video please enable JavaScript, and consider upgrading to a
                    web browser that
                    <a href="https://videojs.com/html5-video-support/" target="_blank"
                    >supports HTML5 video</a
                    >
                  </p>
                </video>
              </div>

            </div>

          </div>

        </div>

      </div>

    </div>

  </div>



</div>

</div>



<!-- /#page-wrapper -->

@stop





@section('footer_scripts')
<script src="https://vjs.zencdn.net/7.8.4/video.js"></script>
<link href="https://vjs.zencdn.net/7.8.4/video-js.css" rel="stylesheet" />
<link href="https://unpkg.com/@silvermine/videojs-quality-selector/dist/css/quality-selector.css" rel="stylesheet">
<link href="https://unpkg.com/@videojs/themes@1/dist/fantasy/index.css" rel="stylesheet"/>
<script src="https://unpkg.com/@silvermine/videojs-quality-selector/dist/js/silvermine-videojs-quality-selector.min.js"></script>

<script>



  $(document).ready(function () {

    var width,height;

    window.onresize = window.onload = function() {

      width = this.innerWidth;

      height = this.innerHeight;

      if (width > 768) {

        $(".btn_show_hide_lecture").click(function () {

          $(".content_lecture > .show_lecture_course").css("display", "none"),

          $(".content_lecture > .btn_show_lecture").css("display", "block"),

          $(".content_lecture > .show_video_course").css({ "margin-left": "128px", width: "calc(100% - 270px)" }),

          0 == $(".show_comment_course:visible").length && $(".content_lecture > .show_video_course").css({ "margin-left": "91px", width: "calc(100% - 182px)" });

        });

        $(".content_lecture > .btn_show_lecture").click(function () {

          $(".content_lecture > .show_lecture_course").css("display", "block"),

          $(".content_lecture > .btn_show_lecture").css("display", "none"),

          $(".content_lecture > .show_video_course").css({ "margin-left": "256px", width: "calc(100% - 270px)" }),

          0 == $(".show_comment_course:visible").length && $(".content_lecture > .show_video_course").css({ "margin-left": "256px", width: "calc(100% - 270px)" });

        });

        $(".btn_show_lecture > .icon_show_lecture").hover(

          function () {

            $(".btn_show_lecture > span").css("display", "block");

          },

          function () {

            $(".btn_show_lecture > span").css("display", "none");

          }

          );

      } else {

        $(".btn_show_hide_lecture").click(function () {

          $(".content_lecture > .show_lecture_course").animate({ left: -300 }, "slow"), $(".content_lecture > .btn_show_lecture").css("display", "block"), $(".show_video_course > .wrap_content_video").css("display", "none");

        });

        $(".btn_show_lecture > .icon_show_lecture").hover(

          function () {

            $(".btn_show_lecture > span").css("display", "block");

          },

          function () {

            $(".btn_show_lecture > span").css("display", "none");

          }

          );

        $(".content_lecture > .btn_show_lecture").click(function () {

          $(".content_lecture > .show_lecture_course").css("display", "block"),

          $(".content_lecture > .btn_show_lecture").css("display", "none"),

          $(".content_lecture > .show_video_course").css({ "margin-left": "256px", width: "calc(100% - 270px)" }),

          0 == $(".show_comment_course:visible").length && $(".content_lecture > .show_video_course").css({ "margin-left": "256px", width: "calc(100% - 270px)" });

        });

        $(".top_video_course_mobile > .show_navleft").click(function () {

          $(".content_lecture > .show_lecture_course").animate({ left: 0 }, "slow"), $(".show_video_course > .wrap_content_video").css("display", "block");

        });

        $(".top_video_course_mobile > .show_navright").click(function () {

          $(".menu_info > span.comment").click(), $("html,body").animate({ scrollTop: $(".menu_info > span.comment").offset().top }, "slow");

        });

        $(".show_video_course > .wrap_content_video").click(function () {

          $(".content_lecture > .show_lecture_course").animate({ left: -300 }, "slow"), $(".content_lecture > .show_comment_course").animate({ right: -256 }, "slow"), $(".show_video_course > .wrap_content_video").css("display", "none");

        });

      }



    }



  });

  $(document).ready(function (){
    var options, myPlayer;

    options = {
    // poster: "{{$current_poster}}",
    controls: true,
    preload: 'auto',
    //autoplay: true,
    controlBar: {
      children: [
      'playToggle',
      'currentTimeDisplay',
      'progressControl',
      'volumePanel',
      'durationDisplay',
      'fullscreenToggle',
      ],
    },
  };

  myPlayer = videojs('video-js', options);
  $('.vjs-current-time').css('display','block');
  var previousTime = 0;
  @if(isset($current_time))
  myPlayer.currentTime({{(int)$current_time}})
  previousTime = {{(int)$current_time}};
  @endif

  

// $('#exercise').append("<a target='_blank' href='{{$url_excer}}' class='btn-rounded btn btn-info button'>Bài tập</a>");
@if(!isset($viewed_video))
myPlayer.on('seeking', function() {
  if (this.currentTime() > previousTime) {
    this.currentTime(previousTime);
  }
});
var time = 0;
myPlayer.on('timeupdate',  function() {
  if (!this.seeking()){
    previousTime =  Math.max(previousTime, myPlayer.currentTime());
    let temp = this.currentTime();
    let temp2 = time+10;
    if(temp > temp2){
      time = temp;
      callAjax("{{$contentslug}}",time);
    }
  }
});
@endif



myPlayer.on('ended', async function() {
  myPlayer.pause();
  swal({
    title: "Success",
    text: "Complete video",
    type: "success",
    timer: 2000,
    showConfirmButton: false
  });
  await finishTimeVideo('{{ $contentslug }}', 1);
  await location.reload();
    // $('#exercise').empty();
    // $('#exercise').append("<a target='_blank' href='{{$url_excer}}' class='btn-rounded btn btn-info button'>Bài tập</a>");
  })
})




  function callAjax(slug,currentTime){
    $.ajax({
      headers: {
        'X-CSRF-TOKEN':'{{csrf_token()}}'
      },
      url : '{{route('updateTimeVideo')}}',
      type : "post",
      data: {slug : slug, currentTime : currentTime},
    });
  }

  function finishTimeVideo(slug, rewardPoint = 0) {
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        url: '{{ route('finishTimeVideo') }}',
        type: "post",
        data: {
            slug: slug,
            rewardPoint: rewardPoint
        },
    });
  }

  function loadExercise(slug){
    $.ajax({
      headers: {
        'X-CSRF-TOKEN':'{{csrf_token()}}'
      },
      url : '{{route('updateTimeVideo')}}',
      type : "post",
      data: {slug : slug},
      success: function (data) {
        console.log(data)
      // $('#exercise').empty();
      // $('#exercise').append("<a target='_blank' href='{{$url_excer}}' class='btn-rounded btn btn-info button'>Bài tập</a>");
    }
  });
  }



  $(document).ready(function() {
    $("#accordian a").click(function() {
      let link = $(this);
      let closest_ul = link.closest("ul");
      let parallel_active_links = closest_ul.find(".active")
      let closest_li = link.closest("li");
      let link_status = closest_li.hasClass("active");
      let count = 0;

      closest_ul.find("ul").slideUp(function() {
        if (++count === closest_ul.find("ul").length)
          parallel_active_links.removeClass("active");
      });

      if (!link_status) {
        closest_li.children("ul").slideDown();
        closest_li.addClass("active");
      }
    })


    let _elmentActive = $('li.lesson_active');
    if (_elmentActive.find('ul').length === 0){
      _elmentActive.parent().parent().children().children().css('color','white');
      _elmentActive.parent().parent().addClass("active");
      _elmentActive.parent().parent().parent().parent().addClass("active");
      _elmentActive.children().addClass('active-content');
      _elmentActive.parent().parent().children('a:first').addClass("active-content");
      _elmentActive.parent().parent().parent().parent().find('h3 a').addClass("active-content");
      _elmentActive.parent().slideDown();
    }


  })

</script>

@stop
