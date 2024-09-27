@extends('admin.front-exams.examlayout-front')
@section('content')
{{-- @include('student.exams.exam-leftbar-subjects', array('subjects' => $subjects)) --}}
<link href="{{CSS}}animate.css" rel="dns-prefetch"/>
<div id="page-wrapper" class="examform" ng-controller="angExamScript" ng-init="initAngData({{json_encode($bookmarks)}})">
  <div class="container-fluid">
    <div class="row">
    </div>
    <!-- /.row -->
    <!-- /.row -->
    
    <div class="row mt-52">
      <div class="col-md-12">
        <div class="panel panel-custom">
          <div class="panel-heading">
            <div class="pull-right exam-duration">
              @include('student.exams.languages',['quiz'=>$quiz])
            </div>
              <h1>
                <span class="text-uppercase">
                  {{change_furigana($title)}}
                </span>
                : <?php change_furigana('[furi k=#問題# f=#もんだい#]'); ?>
                <span id="question_number">
                  1
                </span>
                / {{ count($questions)}}
              </h1>
            </div>
        <div class="panel-body question-ans-box">
        {{-- START of questions List --}}
        <div id="questions_list">
                <div class="questions">
                    <div class="col-xs-6 question-palette700">
                      <div class="show_mondai"></div>
                    </div>
                    <div class="col-xs-6 question-palette700" style="border-left: 1px solid #ccc;">
                      <?php $index = 1; ?>
                      @foreach($questions as $question)
                        <div class="question_div subject_{{$question->subject_id}}" name="question[{{$question->id}}]" id="{{$question->id}}" data-subject="subject_{{$question->subject_id}}"
                          style="display:none;" value="0">
                          <div class="question_div_display_left subject_{{$question->subject_id}}_{{$question->id}} subject_{{$question->subject_id}}_left" style="display: none"> 
                            <div class="hikari_description_parent"><?php echo change_furigana($question->topics_parent_description); ?></div>
                            <div class="hikari_description_child"><?php echo change_furigana($question->topics_child_description); ?></div>
                            <div class="hikari_explanation"><?php echo change_furigana($question->explanation); ?></div>
                          </div>
                        <div class="hikari_question">                                  
                          <div class="language_l1"><span class="question_number"><?php echo $index; ?></span> {{ change_furigana($question->question) }} </div>
                        </div>
                        <div class="hikari_question_anwser"></div>
                          <?php  $image_path = PREFIX.(new App\ImageSettings())->getExamImagePath(); ?>
                          @include('student.exams.question_'.$question->question_type, array('question', $question, 'image_path' => $image_path ))
                        </div>
                      <?php $index++; ?>
                      @endforeach
                    </div>
              </div>
                          </div>
                          {{-- End of questions List --}}
                          <hr>
                          <!-- <div class="row">
                            <div class="col-md-12">
                              <button class="btn btn-lg btn-success button prev" type="button">
                                <i class="mdi mdi-chevron-left ">
                                </i>
                                <?php change_furigana('[furi k=#前# f=#まえ#]へ'); ?>
                              </button>
                            <button class="btn btn-lg btn-success button next" type="button">
                              <?php change_furigana('[furi k=#次# f=#つぎ#]へ'); ?>
                              <i class="mdi mdi-chevron-right">
                              </i>
                            </button> -->
                            <!-- <a href="javascript:void(0);" class="btn btn-lg btn-danger button finish finish_exam pull-right" type="button"><?php change_furigana('[furi k=#試験完了# f=#しけんかんりょう#]'); ?></a> -->
                          
                        </div>
                      </div>
                    </hr>
                  </div>
                </div>
              </div>
              
            </div>
          </div>
        </div>
        <!-- /#page-wrapper -->
        @stop
        @section('footer_scripts')
        @include('front-exams.scripts.js-scripts')
        @include('common.editor')
        <script type="text/javascript">
          /**
           * intilizetimer(hours, minutes, seconds)
           * This method will set the values to defaults
           */
           $(document).ready(function () {
             intilizetimer(0,{{ $time_minutes }},1); 
              // intilizetimer(5,20,0);
            });
           $(document).ready(function () {
              subject = $('.question_div').attr('data-subject');
              $('.'+subject).fadeIn();
              mondai = $('.'+subject+'_left').html();
              $('.show_mondai').html(mondai);
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
          // alertify.success('Ok') 
        }
        else{
        }
      });
  });
</script>
@stop