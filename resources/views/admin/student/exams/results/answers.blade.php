@extends($layout)
<?php
$title = '';
switch ($exam_record->type) {
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
?>
@section('header_scripts')
    <style>
    </style>
@stop
@section('content')
  <div id="page-wrapper" class="answer-sheet" ng-controller="angExamScript" >
            <div class="container-fluid">
                <!-- Page Heading -->
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-custom bg-inverse-info">
                        <li class="breadcrumb-item"><a href="{{PREFIX}}"><i class="mdi mdi-home menu-icon"></i></a></li>
                        <li class="breadcrumb-item"><a href="{{URL_STUDENT_EXAM_ATTEMPTS_FINISH.$user_details->slug}}">Lịch sử bài thi</a></li>
                        <li class="breadcrumb-item"><a href="{{URL_STUDENT_EXAM_ATTEMPTS_FINISH.$user_details->slug}}">{{$de_thi}}</a></li>
                        <li class="breadcrumb-item active" aria-current="page"><span>{{ $title }}</span></li>
                    </ol>
                </nav>
                <!-- /.statistic -->
                <div class="panel panel-custom">
                    <div class="panel-heading">
                        <h1><?php echo $de_thi . ': ' . $title; ?>
                        <!-- <span class="result-pf-text"> {{getPhrase('result').': '.$result_record->exam_status}} </span>
                              <span class="pull-right">
                                @include('student.exams.languages',['quiz'=>$exam_record])
                            </span> -->
                        </h1> 
                    </div>
                    <?php 
                    $submitted_answers = [];
                    $answers = (array)json_decode($result_record->answers);
                    foreach ($answers as $key => $value) {
                        $submitted_answers[$key] = $value;
                    }
                    $correct_answer_questions = [];
                    $correct_answer_questions = (array) 
                                                json_decode($result_record->correct_answer_questions);
                    $time_spent_correct_answers = 
                            getArrayFromJson($result_record->time_spent_correct_answer_questions);
                    $time_spent_wrong_answers = getArrayFromJson($result_record->time_spent_wrong_answer_questions);
                    $time_spent_not_answers = getArrayFromJson($result_record->time_spent_not_answered_questions);
                    // print_r($time_spent_correct_answers);
                    $question_number = 0;
                    $so_cau = 1;
                   ?>
                    @foreach($questions as $question)
                           <?php 
                           $question_number++;
                                $user_answers   = FALSE;
                                $time_spent     = array();
                                //Pull User Answers for this question
                                if(array_key_exists($question->id, $submitted_answers)) {
                                    $user_answers = $submitted_answers[$question->id];
                                }
                                 //Pull Timing details for this question for correct answers
                                if(array_key_exists($question->id, $time_spent_correct_answers)) 
                                    $time_spent = $time_spent_correct_answers[$question->id];
                                 //Pull Timing details for this question for wrong answers
                                if(array_key_exists($question->id, $time_spent_wrong_answers)) 
                                    $time_spent = $time_spent_wrong_answers[$question->id];
                                 //Pull Timing details for this question which are not answered
                                if(array_key_exists($question->id, $time_spent_not_answers)) 
                                    $time_spent = $time_spent_not_answers[$question->id];
                            ?> 
                    <!-- <div class="panel-body question-ans-box" id="{{$question->id}}"  style="display:none;"> -->
                        <div class="panel-body question-ans-box" id="{{$question->id}}">
                        <?php 
                            $question_type = $question->question_type;
                            $subject_record = array();
                            foreach ($subjects as $subject) {
                                if($subject->id == $question->subject_id) {
                                    $subject_record = $subject;
                                    break;
                                }
                            }
                             $inject_data = array(
                                        'question'      => $question,
                                        'user_answers'  => $user_answers,
                                        'subject'      => $subject_record,
                                        'question_number' => $question_number,
                                        'time_spent'    => $time_spent,   
                                    );
                             if ($category == 1) {
                               switch ($question_number) {
                                 case 1:
                                 $so_cau = 1;
                                 break;
                                 case 7:
                                 $so_cau = 1;
                                 break;
                                 case 13:
                                 $so_cau = 1;
                                 break;
                                 case 16:
                                 $so_cau = 1;
                                 break;
                                 case 20:
                                 $so_cau = 1;
                                 break;
                               }
                             }
                             if ($category == 3) {
                               switch ($question_number) {
                                 case 1:
                                 $so_cau = 1;
                                 break;
                                 case 7:
                                 $so_cau = 1;
                                 break;
                                 case 13:
                                 $so_cau = 1;
                                 break;
                                 case 16:
                                 $so_cau = 1;
                                 break;
                                 case 20:
                                 $so_cau = 1;
                                 break;
                               }
                             }
                             if ($category == 4) {
                               switch ($question_number) {
                                 case 1:
                                 $so_cau = 1;
                                 break;
                                 case 10:
                                 $so_cau = 1;
                                 break;
                                 case 16:
                                 $so_cau = 1;
                                 break;
                                 case 25:
                                 $so_cau = 1;
                                 break;
                                 case 30:
                                 $so_cau = 1;
                                 break;
                               }
                             }
                             if ($category == 5) {
                               switch ($question_number) {
                                 case 1:
                                 $so_cau = 1;
                                 break;
                                 case 8:
                                 $so_cau = 1;
                                 break;
                                 case 14:
                                 $so_cau = 1;
                                 break;
                                 case 19:
                                 $so_cau = 1;
                                 break;
                               }
                             }
                        ?>
                        @include('student.exams.results.question-metainfo',array('meta'=> $inject_data, 'cau_so'=>$so_cau))
                        @include('student.exams.results.radio-answers', $inject_data)
                        @if($question->explanation)
                        <div class="answer-status-container" style="display: none;">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="question-status">
                                        <strong>{{getPhrase('explanation')}}: </strong>
                                           <span class="language_l1"> {!! $question->explanation!!}</span>
                                           @if(isset($question->explanation_l2))
                                           <span class="language_l2" style="display: none;"> {!! $question->explanation_l2!!}</span>
                                           @else
                                           <span class="language_l2" style="display: none;"> {!! $question->explanation!!}</span>
                                           @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                    <?php $so_cau++; ?>
                    @endforeach
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="d_but">
                                        <button class="btn btn-lg btn-success button prev" type="button">
                                            <i class="mdi mdi-chevron-left ">
                                            </i>
                                            <?php change_furigana('[furi k=#前# f=#まえ#]へ'); ?>
                                        </button>
                                        <button class="btn btn-lg btn-success button next" type="button">
                                            <?php change_furigana('[furi k=#次# f=#つぎ#]へ'); ?>
                                            <i class="mdi mdi-chevron-right">
                                            </i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </hr>
                </div>
                <!-- /.row -->
            </div>
            <!-- /.container-fluid -->
        </div>
@stop
@section('footer_scripts')
@include('student.exams.results.scripts.js-scripts')
<script>
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
@stop