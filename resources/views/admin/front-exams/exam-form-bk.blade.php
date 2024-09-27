@extends('admin.front-exams.examlayout-front')
@section('content')
{{-- @include('admin.student.exams.exam-leftbar-subjects', array('subjects' => $subjects)) --}}
<link href="{{CSS}}animate.css" rel="dns-prefetch"/>
<div id="page-wrapper" class="examform" ng-controller="angExamScript" ng-init="initAngData({{json_encode($bookmarks)}})">
  <div class="container-fluid">
    <div class="row">
    </div>
    <!-- /.row -->
    <!-- /.row -->
    {!! Form::open(array('url' => URL_FRONTEND_FINISH_EXAM.$quiz->slug, 'method' => 'POST', 'id'=>'onlineexamform')) !!}
    <div class="row">
      <div class="col-md-12">
        <div class="panel panel-custom">
          <div class="panel-heading">
            <div class="pull-right exam-duration">
              @include('admin.student.exams.languages',['quiz'=>$quiz])
            </div>
            <h1>
              <span class="text-uppercase">
                {{change_furigana($title)}}
              </span>
              : <!-- {{getPhrase('question')}} --><?php change_furigana('[furi k=#問題# f=#もんだい#]'); ?>
              <span id="question_number">
                1
              </span>
              / {{ count($questions)}}
            </h1>
          </div>
          <?php    
          $image_path = PREFIX.(new App\ImageSettings())->getExamImagePath(); 
          ?>
          <div class="panel-body question-ans-box">
           <!-- Show audio file -->
           <?php if ($title == '[furi k=#聴解# f=#ちょうかい#]') { ?>
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
        </style>
        <div style="pointer-events: none;">
          <audio src="" controls id="audioPlayer" controlsList="nodownload">
            Sorry, your browser doesn't support html5!
          </audio>
        </div>
        <ul id="playlist" style="display: none">
         <?php 
         foreach($questions as $question) {
           ?>
           <li class="current-song"><a href="<?php  echo $image_path.$question->question_file ?>"><?php  echo $image_path.$question->question_file ?></a></li>
           <?php } ?>
              <!-- <li><a href="http://elearning.hikariacademy.edu.vn/public/uploads/exams/282question.mp3">Severe Tire Damage</a></li>
                <li><a href="http://elearning.hikariacademy.edu.vn/public/uploads/exams/283question.mp3">Broken Reality</a></li> -->
              </ul>
              <script>
          // loads the audio player
          audioPlayer();
        </script> 
        <?php } ?>
        <!--##### Show audio file -->
        {{-- START of questions List --}}
        <div id="questions_list">
          <?php 
          $questionHasVideo = FALSE; 
          ?>
          <?php $index = 1; ?>
          @foreach($questions as $question)
          <?php if(!$questionHasVideo)
          {
            if($question->question_type=='video')
              $questionHasVideo = TRUE;
          } ?>
          <div class="question_div subject_{{$question->subject_id}}" name="question[{{$question->id}}]" id="{{$question->id}}" 
            style="display:none;" value="0">
            <input type="hidden" name="time_spent[{{$question->id}}]" id="time_spent_{{$question->id}}" value="0">
            <div class="questions">
              <div class="col-xs-6 " style="min-height: 550px; border-right: 2px solid;">

               <div class="hikari_description_parent"><?php echo change_furigana($question->topics_parent_description); ?></div>
               <div class="hikari_description_child"><?php echo change_furigana($question->topics_child_description); ?></div>
               <div class="hikari_explanation"><?php echo change_furigana($question->explanation); ?></div>
             </div>
             <div class="col-xs-6">
              <div class="hikari_question">                                  
                <div class="language_l1"><span><?php echo $index; ?></span> {{ change_furigana($question->question) }} </div>
              </div>
                @if($question->question_l2) 
                @if($question->question_type == 'radio' || $question->question_type == 'checkbox' || $question->question_type == 'blanks' || $question->question_type == 'match')
                <span class="language_l2" style="display: none;"> {!! $question->question_l2 !!}   </span>
                @else
                <span class="language_l2" style="display: none;"> {!! $question->question !!}   </span>
                @endif
                @else
                <span class="language_l2" style="display: none;"> {!! $question->question !!}   </span>
                @endif
                <div class="row">
                  <div class="col-md-8 text-center">
                    @if($question->question_type!='audio' && $question->question_type !='video')
                    @if($question->question_file)
                    <!-- <img class="image " src="{{$image_path.$question->question_file}}" style="max-height:200px;"> -->
                                          <!-- <audio controls>
                                            <source src="{{$image_path.$question->question_file}}" type="audio/ogg">
                                            <source src="{{$image_path.$question->question_file}}" type="audio/mpeg">
                                            <source src="{{$image_path.$question->question_file}}" type="audio/wav">
                                          Your browser does not support the audio element.
                                        </audio> -->
                                        @endif
                                        @endif
                                      </div>
                                        <!-- <div class="col-md-4">
                                         <span class="pull-right"> {{$question->marks}} 点</span>
                                       </div> -->
                                     </div>
                                     <div class="option-hints pull-right default" data-placement="left" data-toggle="tooltip" ng-show="hints" title="{{ $question->hint }}">
                                      <i class="mdi mdi-help-circle">
                                      </i>
                                    </div>
                                  </div>
                                </div>
                                <hr>
                                <?php  
                                $image_path = PREFIX.(new App\ImageSettings())->getExamImagePath(); 
                                ?>
                                @include('admin.student.exams.question_'.$question->question_type, array('question', $question, 'image_path' => $image_path ))
                              </hr>
                            </div>
                            <?php $index++; ?>
                            @endforeach
                          </div>
                          {{-- End of questions List --}}
                          <hr>
                          <div class="row">
                            <div class="col-md-12">
                              <button class="btn btn-lg btn-success button prev" type="button">
                                <i class="mdi mdi-chevron-left ">
                                </i>
                                <!-- {{getPhrase('previous')}} --><?php change_furigana('[furi k=#前# f=#まえ#]へ'); ?>
                              </button>
                            <!-- <button class="btn btn-lg btn-dark button next" id="markbtn" type="button">
                              {{getPhrase('mark_for_review')}} & {{getPhrase('next')}}
                            </button> -->
                            <button class="btn btn-lg btn-success button next" type="button">
                              <!-- {{ getPhrase('next')}} --><?php change_furigana('[furi k=#次# f=#つぎ#]へ'); ?>
                              <i class="mdi mdi-chevron-right">
                              </i>
                            </button>
                            <!-- <button class="btn btn-lg btn-dark button clear-answer" type="button">
                              {{getphrase('clear_answer')}}
                            </button> -->
                            <a href="javascript:void(0);" class="btn btn-lg btn-danger button finish finish_exam pull-right" type="button"><!-- {{getPhrase('finish')}} --> <?php change_furigana('[furi k=#試験完了# f=#しけんかんりょう#]'); ?></a>
                          </button>
                        </div>
                      </div>
                    </hr>
                  </div>
                </div>
              </div>
              {!! Form::close() !!}
            </div>
          </div>
        </div>
        <!-- /#page-wrapper -->
        @stop
        @section('footer_scripts')
        @include('admin.front-exams.scripts.js-scripts')
        @include('admin.common.editor')
        <!--JS Control-->
        @if($questionHasVideo)
        @include('admin.common.video-scripts')
        @endif
        <script type="text/javascript">
/**
 * intilizetimer(hours, minutes, seconds)
 * This method will set the values to defaults
 */
 $(document).ready(function () {
   intilizetimer(0,{{ $time_minutes }},1); 
    // intilizetimer(5,20,0);
  });
 function languageChanged(language_value)
 {
  if(language_value=='language_l2')
  {
    $('.language_l1').hide();
    $('.language_l2').show();
  }
  else {
    $('.language_l2').hide();
    $('.language_l1').show(); 
  }
}
</script>
<script>
  $('.finish_exam').click(function(){
    alertify.set({ labels: {
      ok     : "はい",
      cancel : "いいえ"
    } });
    alertify.confirm('試験を終了します。よろしでしょうか？',
      function(e){ 
        if(e){
          $("#onlineexamform").submit();
          alertify.success('Ok') 
        }
        else{
        }
      });
  });
</script>
@stop