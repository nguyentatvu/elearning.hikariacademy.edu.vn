<?php echo 2123; exit; ?>
@extends('admin.layouts.examlayout')
@section('content')
<link href="{{CSS}}animate.css" rel="dns-prefetch"/>
<div id="page-wrapper" class="examform" ng-controller="angExamScript" ng-init="initAngData({{json_encode($bookmarks)}})">
    <div class="container-fluid">
        
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-custom">
                    <div class="panel-heading row">
                        <h1>
                            <span class="text-uppercase pull-left hikari-subject-title">
                            <?php change_furigana_text ($title); ?>
                            </span>
                            <!-- <span id="question_number">
                                
                            </span> -->
                           
                        </h1>
                    </div>
                    <div class="panel-body question-ans-box">
                        {{-- START of questions List --}}
                        <div id="questions_list">
                                <div class="questions">
                                    <div class="col-xs-6">
                                      <div class="hikari-audio">
                                                <!-- Show audio file -->
                                                <?php if ($quiz->type == 1) { ?>
                                                    <?php $image_path = PREFIX.(new App\ImageSettings())->getExamImagePath(); ?>
                                                    <script src="https://code.jquery.com/jquery-2.2.0.js"></script>
                                                    <script type="text/javascript">
                                                    function audioPlayer(){
                                                        var currentSong = 0;
                                                        jQuery("#audioPlayer")[0].src = jQuery("#playlist li a")[0];
                                                        jQuery("#audioPlayer")[0].play();
                                                        jQuery("#playlist li a").click(function(e){
                                                           e.preventDefault(); 
                                                           jQuery("#audioPlayer")[0].src = this;
                                                           jQuery("#audioPlayer")[0].play();
                                                           jQuery("#playlist li").removeClass("current-song");
                                                            currentSong = jQuery(this).parent().index();
                                                            jQuery(this).parent().addClass("current-song");
                                                        });
                                                        jQuery("#audioPlayer")[0].addEventListener("ended", function(){
                                                           currentSong++;
                                                            if(currentSong == jQuery("#playlist li a").length)
                                                                currentSong = 0;
                                                            jQuery("#playlist li").removeClass("current-song");
                                                            jQuery("#playlist li:eq("+currentSong+")").addClass("current-song");
                                                            jQuery("#audioPlayer")[0].src = jQuery("#playlist li a")[currentSong].href;
                                                            jQuery("#audioPlayer")[0].play();
                                                        });
                                                    }
                                                  </script>
                                                  <style>
                                                    #playlist{
                                                        list-style: none;
                                                    }
                                                    #playlist li a{
                                                        color:black;
                                                        text-decoration: none;
                                                    }
                                                    #playlist .current-song a{
                                                        color:blue;
                                                    }
                                                    audio::-webkit-media-controls-mute-button {
                                                        display: none !important;
                                                    }
                                                    audio::-webkit-media-controls-volume-slider {
                                                        display: none !important;
                                                    }
                                                    audio#audioPlayer {
                                                        height: 22px;
                                                    }
                                                </style>
                                                  <div style="pointer-events: none;">
                                                      <audio src="" controls id="audioPlayer" controlsList="nodownload">
                                                      Trình duyệt của bạn không hỗ trợ html5!
                                                     </audio>
                                                  </div>
                                                    <ul id="playlist" style="display: none">
                                                      <?php
                                                        $i_question = 1; 
                                                        foreach($questions as $question) {
                                                        ?>
                                                        <?php
                                                          switch ($i_question) {
                                                              case 1:
                                                                  echo '<li class="current-song"><a href="'.$image_path.'mon1.mp3'.'">'.$image_path.'mon1.mp3'.'</a></li>';
                                                                  break;
                                                              case "7":
                                                                  echo '<li><a href="'.$image_path.'mon2.mp3'.'">'.$image_path.'mon2.mp3'.'</a></li>';
                                                                  break;
                                                              case "13":
                                                                  echo '<li><a href="'.$image_path.'mon3.mp3'.'">'.$image_path.'mon3.mp3'.'</a></li>';
                                                                  break;
                                                              case "16":
                                                                  echo '<li><a href="'.$image_path.'mon4.mp3'.'">'.$image_path.'mon4.mp3'.'</a></li>';
                                                                  break;
                                                              case "20":
                                                                  echo '<li><a href="'.$image_path.'mon5.mp3'.'">'.$image_path.'mon5.mp3'.'</a></li>';
                                                                  break;
                                                          }
                                                          ?>
                                                        <li><a href="<?php  echo $image_path.$question->question_file ?>"><?php  echo $image_path.$question->question_file ?></a></li>
                                                    <?php $i_question++; } ?>
                                                    </ul>
                                                  <script>
                                                    // loads the audio player
                                                    audioPlayer();
                                                  </script> 
                                                <?php } ?>
                                                <!--##### Show audio file -->
                                      </div>
                                      <div class="show_mondai hikari-croll-full" id="hikari-mondaiscroll"></div>
                                    </div>
                                    <div class="col-xs-6" style="position: relative;">
                                      <div class="container-answer hikari-croll-full hikari-w260" id="hikari-croll-full" style="overflow-y: scroll; overflow-x: hidden;">
                                        
                                        <?php  


                                        $subject_topic = array();
                                        $question_plit_topic = array();
                                        $subject_topic_check = array();
                                        $subject_topic_check_parent = array();
                                        foreach ($questions as $key_subject_topic => $value_subject_topic) {
                                          //echo $value_subject_topic->subject_id . '-' . $value_subject_topic->topic_id . '-c'.$value_subject_topic->topics_child_status. 'p'. $value_subject_topic->topics_parent_status . '<br>';
                                            if (array_key_exists($value_subject_topic->subject_id, $subject_topic)) {
                                               if ($subject_topic_check[$value_subject_topic->subject_id] != $value_subject_topic->topic_id) {
                                                  if ($subject_topic_check_parent[$value_subject_topic->subject_id] != $value_subject_topic->topics_child_status) {
                                                    $question_plit_topic[$value_subject_topic->id] = '<hr/>';
                                                    $subject_topic[$value_subject_topic->subject_id] .= '<hr/>' . $value_subject_topic->topics_child_description;
                                                    $subject_topic_check_parent[$value_subject_topic->subject_id] = $value_subject_topic->topics_child_status;
                                                    
                                                  }
                                                  $subject_topic_check[$value_subject_topic->subject_id] = $value_subject_topic->topic_id;

                                              }
                                            } else {
                                              $space_topics = '';
                                              if (!empty($value_subject_topic->topics_parent_description)) {
                                                $space_topics = '<p>&nbsp;</p>';
                                              }
                                              $subject_topic[$value_subject_topic->subject_id] = $value_subject_topic->topics_parent_description . $space_topics . $value_subject_topic->topics_child_description;
                                              $subject_topic_check[$value_subject_topic->subject_id] = $value_subject_topic->topic_id;
                                              $subject_topic_check_parent[$value_subject_topic->subject_id] = 0;
                                            }
                                        }

                                        ?>
                                        <?php 
                                        $index = 1; 
                                        $subject_check = '';
                                        
                                        ?>

                                        @foreach($questions as $question)
                                          <div class="question_div subject_{{$question->subject_id}}" name="question[{{$question->id}}]" id="{{$question->id}}" data-subject="subject_{{$question->subject_id}}"
                                            style="display:none;" value="0">

                                            <div class="question_div_display_left subject_{{$question->subject_id}}_{{$question->id}} subject_{{$question->subject_id}}_left" style="display: none"> 
                                                                                            
                                              <div class="hikari_description_child"><?php echo change_furigana($subject_topic[$question->subject_id]); ?></div>
                                              

                                              <div class="hikair-gach-cheo" style="">
                                                    <img src="/public/images/gach-cheo.png" alt="e-learning-gach" style="width: 100%; height: 100%">
                                              </div>
                                            </div>
                                            
                                            <?php 
                                            if (array_key_exists($question->id, $question_plit_topic)) {
                                                       echo $question_plit_topic[$question->id];
                                            }
                                            if ($quiz->type == 1) {
                                              if ($subject_check != $question->subject_id) { 
                                                $subject_check = $question->subject_id;
                                                $index = 1;
                                                }
                                            }
                                            ?>

                                            <table style="width:98%">
                                              <?php  if(!empty($question->explanation)) { echo '<tr class="hik-table-tr-question">'.change_furigana($question->explanation) . '</tr>'; } ?>
                                              <tr class="hik-table-tr-question">
                                                <?php if ($quiz->type != 1) { ?>
                                                <td class="hik-table-tr-question-number"><span class="question_number"><?php echo $index; ?></span></td>
                                                <?php } ?>
                                                <td class="hik-table-tr-question-question" style="padding-left: 8px;">
                                                     {{ change_furigana($question->question) }}

                                                </td> 
                                              </tr>
                                            </table>

                                            <div class="hikari_question_anwser"></div>
                                            
                                            <?php  $image_path = PREFIX.(new App\ImageSettings())->getExamImagePath(); ?>
                                             @include('admin.student.exams.question_'.$question->question_type, array('question', $question, 'image_path' => $image_path ))

                                          </div>
                                        <?php $index++; ?>
                                        @endforeach
                                      </div>

                                      <span class="hik-trang hik-trang-truoc">
                                        <a href="javascript:void(0);" class="hikari-prenext hikari-pre" onclick="pre_mondai();">
                                          <i class="fa fa-arrow-left"></i> 
                                        </a>
                                      </span>
                                      <span class="hik-trang hik-trang-sau">
                                        <a href="javascript:void(0);" class="hikari-prenext hikari-next" onclick="next_mondai();">
                                                 <i class="fa fa-arrow-right"></i>
                                        </a>
                                      </span>
                                      <style type="text/css">
                                        span.hik-trang.hik-trang-truoc {
                                            position: absolute;
                                            bottom: -15px;
                                            left: 20px;
                                        }
                                        span.hik-trang.hik-trang-sau {
                                            position: absolute;
                                            bottom: -15px;
                                            right: 48px;
                                        }

                                      </style>

                                    </div>
                              </div>
                         </div>
                        {{-- End of questions List --}}
                        <hr>
                        </hr>
                    </div>
                </div>
            </div>
            
             <input type="hidden" name="quiz_id" id="quiz_id" value="{{$quiz->id}}">
            <input type="hidden" name="student_id" id="student_id" value="{{$user->id}}">
        </div>
    </div>
</div>
@stop
@section('footer_scripts')
@include('admin.student.exams.scripts.js-scripts')
@include('admin.common.editor')
<script type="text/javascript">
    $(document).ready(function () {
        $('.hik-trang-truoc').hide();
    });

    function pre_mondai() {
      var page_pre = Number($('.hikari-page.active').attr('data-page')) - 1;
      var mondai_pre = $('.page_'+ page_pre).attr('data-mondai');
      
      showSubjectQuestion(mondai_pre);
      $('.hikari-page').removeClass('active');
      $('.hikari-page.'+mondai_pre).addClass('active');
      $('.hikari-page.'+mondai_pre).addClass('hikari_active');
      if (page_pre == 1) {
        $('.hik-trang-truoc').hide();
      } else {
        $('.hik-trang-truoc').show();
      }
      $('.hik-trang-sau').show();

    }

    function next_mondai() {
      var page_next = Number($('.hikari-page.active').attr('data-page')) + 1;
      var mondai_next = $('.page_'+ page_next).attr('data-mondai');

      showSubjectQuestion(mondai_next);
      $('.hikari-page').removeClass('active');
      $('.hikari-page.'+mondai_next).addClass('active');
      $('.hikari-page.'+mondai_next).addClass('hikari_active');

      var number_page = $('ul.subject-page li').length;
      if (page_next == number_page) {
        $('.hik-trang-sau').hide();
      } else {
        $('.hik-trang-sau').show();
      }
      $('.hik-trang-truoc').show();
      
    }


   

    $(window).on('beforeunload', function(e) { 
       saveFormData();
       //return 'Bạn có chắc chắn muốn thoát?'; 
    });

    function saveFormData () {
     
      $("#onlineexamform").submit();
    }

    

    $(document).ready(function () {
        current_hours = {{ $time_hours }}; 
        current_minutes = {{ $time_minutes }}; 
        current_seconds = {{ $time_seconds }}; 
         intilizetimer(current_hours, current_minutes, current_seconds); 
         @if($current_question_id)
            resumeSetup('{{$current_question_id}}');
         @endif
         $('input').click(function(){
            qn = parseInt($(this).attr('name'));
             saveResumeExamData(qn);
         });
        // intilizetimer(5,20,0);
    });

    function resumeSetup(current_question_id) {
        DIV_REFERENCE.first().hide();
        current_question_number = $('#'+current_question_id).attr('data-current-question');
        $('#question_number').html(current_question_number);
        $('#'+current_question_id).fadeIn(300);
    }
  /**
   * intilizetimer(hours, minutes, seconds)
   * This method will set the values to defaults
   */
      document.onmousedown=disableclick;
       function disableclick(event)
      {
        if(event.button==2)
         {
           return false;    
         }
      }
    $(document).ready(function () {
            subject = $('.question_div').attr('data-subject');
            $('.'+subject).fadeIn();
            mondai = $('.'+subject+'_left').html();
            $('.show_mondai').html(mondai);
            gach_cheo();
            $('#hikari-morong-button').on('click',function(){
                // 拡張 = mở rộng
                if ($(this).text()=='拡張'){
                  $('.hikari-croll-full').removeClass('hikari-w260');
                  $('.hikari-croll-full').addClass('hikari-w400');
                  $('#hikari-top-exam').removeClass('hikari-w150');
                  $('#hikari-top-exam').addClass('hikari-w300');
                  $('#question-palette').removeClass('hikari-w80');
                  $('#question-palette').addClass('hikari-w180');
                  $('#question-palette1').removeClass('cl1');
                  $('#question-palette1').addClass('cl4');
                  $('#question-palette2').removeClass('cl2');
                  $('#question-palette2').addClass('cl5');
                  $(this).html('<i class="fa fa-arrow-up"></i>圧縮');
                  gach_cheo();
                } else{
                  $(this).html('<i class="fa fa-arrow-down"></i>拡張');
                  $('#hikari-top-exam').removeClass('hikari-w300');
                  $('#hikari-top-exam').addClass('hikari-w150');
                  $('#question-palette').removeClass('hikari-w180');
                  $('#question-palette').addClass('hikari-w80');
                  $('.hikari-croll-full').removeClass('hikari-w400');
                  $('.hikari-croll-full').addClass('hikari-w260');
                  $('#question-palette1').removeClass('cl4');
                  $('#question-palette1').addClass('cl1');
                  $('#question-palette2').removeClass('cl5');
                  $('#question-palette2').addClass('cl2');
                  gach_cheo();
                }
            });

            
         });


 
    function gach_cheo() {
      var height_description_mondai = $(".hikari_description_child").height();
      var height_showmondai = $(".show_mondai").height();
      var gach_cheo = height_showmondai - height_description_mondai - 30;
      $(".hikair-gach-cheo").css({"height": gach_cheo+"px" ,"width":"100%"});
    }

</script>
<script>
$('.finish').click(function(){
  alertify.set({ labels: {
      ok     : "はい",
      cancel : "いいえ"
  } });
  alertify.confirm('<?php echo change_furigana_text('[furi k=#試験# f=#しけん#]'); ?>を<?php echo change_furigana_text('[furi k=#終了# f=#しゅうりょう#]'); ?>します。よろしいでしょうか？',
    function(e){ 
      if(e){
        $(window).off('beforeunload');
        $("#onlineexamform").submit();
        // return false;

      }
      else{
      }
  });
});


</script>
@stop