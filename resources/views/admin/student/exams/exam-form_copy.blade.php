@extends('admin.layouts.examlayout')
@section('content')
<link href="{{CSS}}animate.css" rel="dns-prefetch"/>
<div id="page-wrapper" class="examform" ng-controller="angExamScript" ng-init="initAngData({{json_encode($bookmarks)}})">
  <div class="container-fluid">
    <div class="row">
      <div class="">
        <div class="panel panel-custom">
          <!-- <div class="panel-heading row">
            <span class="text-uppercase pull-left hikari-subject-title" style="">
              <?php  
              switch ($quiz->type) {
                case '2':
                $title = 'TỪ VỰNG';
                break;
                case '3':
                $title = 'NGỮ PHÁP - ĐỌC HIỂU';
                break;
                case '1':
                $title = 'NGHE HIỂU';
                break;
              }
              echo $title;
              ?>
            </span>
            <?php  $image_path = PREFIX.(new App\ImageSettings())->getExamImagePath(); ?>
            include('student.exams.exam-form-mp3', array($questions))
          </div> -->
          <div class="panel-body question-ans-box">
            {{-- START of questions List --}}
            <div id="questions_list">
              <?php  
              $subject_edit = array();
              foreach ($subjects as $key_subject => $value_subject) {
                $subject_edit[$value_subject->id] = $value_subject->subject_code;
              }
              $subject_topic = array();
              $subject_topic_check = array();
              $question_plit_topic = array();
              $subject_topic_check_parent = array();
              $subject_stt = 0;
              foreach ($questions as $key_subject_topic => $value_subject_topic) {
                if (array_key_exists($value_subject_topic->subject_id, $subject_topic)) {
                 if ($subject_topic_check[$value_subject_topic->subject_id] != $value_subject_topic->topic_id) {
                  if ($subject_topic_check_parent[$value_subject_topic->subject_id] != $value_subject_topic->topics_child_status) {
                    if ($quiz->category_id == 3) {
                      $subject_topic[$value_subject_topic->subject_id] .= '<hr/><p>(2)</p>' . $value_subject_topic->topics_child_description;
                    } else {
                      $subject_topic[$value_subject_topic->subject_id] .= $value_subject_topic->topics_child_description;
                    }
                    $question_plit_topic[$value_subject_topic->id] = '<hr/>';
                    $subject_topic_check_parent[$value_subject_topic->subject_id] = $value_subject_topic->topics_child_status;
                    $subject_stt = $value_subject_topic->subject_id;
                  }
                  $subject_topic_check[$value_subject_topic->subject_id] = $value_subject_topic->topic_id;
                }
              } else {
                $space_topics = '';
                if (!empty($value_subject_topic->topics_parent_description)) {
                  $space_topics = '<p>&nbsp;</p>';
                }
                if ($quiz->category_id == 3) {
                  $subject_topic[$value_subject_topic->subject_id] = $subject_edit[$value_subject_topic->subject_id] . '<p class="subject_stt subject_stt_'.$value_subject_topic->subject_id.'">(1)</p>' .$value_subject_topic->topics_child_description;
                } else {
                  $subject_topic[$value_subject_topic->subject_id] = $subject_edit[$value_subject_topic->subject_id] . $value_subject_topic->topics_child_description;
                }
                $subject_topic_check[$value_subject_topic->subject_id] = $value_subject_topic->topic_id;
                $subject_topic_check_parent[$value_subject_topic->subject_id] = 0;
              }
            }
            ?>
            <style type="text/css">
            .subject_stt {
              display: none;
            }
            .subject_stt_<?php echo $subject_stt;?>  {
              display: block;
            }
          </style>
          <div class="questions">
            <div class="col-xs-6">
             <div class="show_mondai hikari-croll-full" id="hikari-mondaiscroll">       
              <?php 
              $i_subject_topic = 1;
              foreach ($subject_topic as $key_subject_topic => $value_subject_topic) { ?>
              <div class="mondai_subject_show" id="mondai_subject_<?php echo $key_subject_topic; ?>" style="<?php echo  $i_subject_topic!=1? 'display:none':'';?>" >
                <?php echo change_furigana($value_subject_topic) ?>
              </div>
              <?php 
              $i_subject_topic++; 
            } 
            ?>      
          </div>          
          <!-- <div class="show_mondai hikari-croll-full" id="hikari-mondaiscroll"></div> -->
        </div>
        <div class="col-xs-6" style="position: relative;">
          <div class="container-answer hikari-croll-full hikari-w260" id="hikari-croll-full" style="overflow-y: scroll; overflow-x: hidden;">
            <?php 
            $index = 1; 
            $subject_check = '';
            $style_row = '';
            $number_questions = 1;
            $count_questions = count($questions);
            $subject_edit = array();
            foreach ($subjects as $key_subject => $value_subject) {
              $subject_edit[$value_subject->id] = $value_subject->subject_code;
            }
            ?>

            @if ($quiz->category_id == 1)
              @if ($quiz->type == 1)
                @foreach($questions as $question)
                    <div class="question_div subject_{{$question->subject_id}}" name="question[{{$question->id}}]" id="{{$question->id}}" data-subject="subject_{{$question->subject_id}}"
                    style="display:none;" value="0">

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
                      @if ($number_questions <= 35)
                      <?php  if(!empty($question->explanation)) {
                        echo '<div class="hik-table-tr-question">' . change_furigana($question->explanation) . '</div>'; 
                      } 
                      ?>
                      <tr class="hik-table-tr-question">
                        <td class="hik-table-tr-question-number"><span class="question_number"><?php echo $index; ?></span><span style="display: none">{{$question->question}}</span></td>
                        <td class="hik-table-tr-question-question">
                         {{ change_furigana( trim($question->question)) }}
                       </td> 
                     </tr>
                     @endif
                    
                    @if ($number_questions == 36)

                      <tr class="hik-table-tr-question">
                         <td class="hik-table-tr-question-number"><span class="question_number">3</span></td>
                         <td class="hik-table-tr-question-question">
                          {{ change_furigana( trim($question->explanation)) }}
                        </td> 
                      </tr>
                      
                      <tr class="hik-table-tr-question">
                         <td class="hik-table-tr-question-number"><span >(1)</span></td>
                         <td class="hik-table-tr-question-question">
                          {{ change_furigana( trim($question->question)) }}
                        </td> 
                      </tr>

                    @endif


                     @if ($number_questions == 37) 
                        <tr class="hik-table-tr-question">
                           <td class="hik-table-tr-question-number"><span>(2)</span></td>
                           <td class="hik-table-tr-question-question">
                            {{ change_furigana( trim($question->question)) }}
                          </td> 
                        </tr>
                     @endif


                   </table>

                   <div class="hikari_question_anwser"></div>
                     <?php  $image_path = PREFIX.(new App\ImageSettings())->getExamImagePath(); ?>
                     @include('admin.student.exams.question_'.$question->question_type, array('question', $question, 'image_path' => $image_path ))
                     <?php
                     if ($number_questions == $count_questions) {
                          //Finish append js after final
                      echo '<div id="submit_div" style="text-align: center; padding-top: 10px;"></div>';
                    }
                    $number_questions++;
                    ?>
                  </div>
                  <?php
                  $index++; 
                  ?>
                  @endforeach
                

                @else

                @endif

              @endif

              @if ($quiz->category_id == 3 || $quiz->category_id == 4 || $quiz->category_id == 5)


              @endif


                                  
          </div>
                      <a href="javascript:void(0);" class="hikari-prenext hikari-pre" onclick="pre_mondai();">
                        <span class="hik-trang hik-trang-truoc">
                          <i class="fa fa-arrow-left"></i> 
                        </span>
                      </a>
                      <a href="javascript:void(0);" class="hikari-prenext hikari-next" onclick="next_mondai();">
                        <span class="hik-trang hik-trang-sau">
                         <i class="fa fa-arrow-right"></i>
                       </span>
                     </a>
                     
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
          <?php 
          $finish = '';  
          if(checkRole(getUserGrade(5)) || checkRole(getUserGrade(6)))
                {
                      $finish = 'finish finish_exam';      
                } 
          ?>
          <script type="text/javascript">
            $(document).ready(function () {
              $('.hik-trang-truoc').hide();
              //Zoom image
              $( "img" ).wrap( '<div data-zoom data-zoom-max="1"></div>' );
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
                $('div#submit_div').html('<span class="submit-question" style="text-align: center;"><a href="javascript:void(0);" class="btn btn-lg btn-danger button <?php echo $finish; ?>" type="button" style="padding: 10px;">NỘP BÀI</a></span>');
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
      //var url =  '{{URL_STUDENT_EXAM_SERIES_VIEW_ITEM.$examseries_slug}}';
      //localStorage.setItem('redirect_url', '{{URL_STUDENT_EXAM_SERIES_VIEW_ITEM.$examseries_slug}}');
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
                if ( $(this).attr('data-display') == 1 ){
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
                  $(this).attr('data-display',0);
                  $(this).html('<i class="fa fa-arrow-up"></i>');
                  gach_cheo();
                } else{
                  $(this).html('<i class="fa fa-arrow-down"></i>');
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
                  $(this).attr('data-display',1);
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
  $('body').on('click', '.finish', function(){
    alertify.set({ labels: {
      ok     : "Có",
      cancel : "Không"
    } });
    alertify.confirm('Bạn có chắc chắn muốn nộp bài ngay không?',
      function(e){ 
        if(e){
          $("#onlineexamform").submit();
          $("body.ng-scope").hide();
          $(window).off('beforeunload');
        }
        else{
        }
      });
  });
</script>
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
@stop