@php
// $question_plit_topic = array();
// if ($quiz->type != 1) {
//   $question_plit_topic[$questions[49]->id] = '<p style="padding-bottom: 15px;">(1)</p>';
//   $question_plit_topic[$questions[52]->id] = '<hr/><p style="padding-bottom: 15px;">(2)</p>';
//   $question_plit_topic[$questions[55]->id] = '<hr/><p style="padding-bottom: 15px;">(3)</p>';
// }
  $index = 1; 
  $subject_check = '';
  $style_row = '';
  $number_questions = 1;
  // $count_questions = count($questions);

  $quiz = $data['quiz'];
  $questions = $data['questions'];

@endphp

@if ($quiz->category_id == 3 || $quiz->category_id == 4 || $quiz->category_id == 5)
  
  @foreach($questions as $question)
    <div class="question_div subject_{{$question->subject_id}}" name="question[{{$question->id}}]" id="{{$question->id}}" data-subject="subject_{{$question->subject_id}}"
      value="0">
    @php 
      // if (array_key_exists($question->id, $question_plit_topic)) {
      //  echo $question_plit_topic[$question->id];
      // }
      if ($quiz->type == 1) {
        if ($subject_check != $question->subject_id) { 
          $subject_check = $question->subject_id;
          $index = 1;
        }
      }
    @endphp
    <table style="width:98%">
      @php  
      if(!empty($question->explanation)) {
        $stt_explanation = '';
        if ($quiz->category_id == 3) {
          switch ($index) {
            case 24:
            $stt_explanation = '<p>(1)</p>';
            break;
            case 25:
            $stt_explanation = '<p>(2)</p>';
            break;
            case 26:
            $stt_explanation = '<p>(3)</p>';
            break;
            case 27:
            $stt_explanation = '<p>(4)</p>';
            break;
        } 
   
        }
        if ($quiz->category_id == 4) {
          switch ($index) {
            case 26:
            $stt_explanation = '<p>(1)</p>';
            break;
            case 27:
            $stt_explanation = '<p>(2)</p>';
            break;
            case 28:
            $stt_explanation = '<p>(3)</p>';
            break;
            case 29:
            $stt_explanation = '<p>(4)</p>';
            break;
        } 
      }
      if ($quiz->category_id == 5) {
          switch ($index) {
            case 27:
            $stt_explanation = '<p>(1)</p>';
            break;
            case 28:
            $stt_explanation = '<p>(2)</p>';
            break;
            case 29:
            $stt_explanation = '<p>(3)</p>';
            break;
        } 
      }
      echo '<div class="hik-table-tr-question">'. $stt_explanation . '</div>';
      echo '<div class="hik-table-tr-question">' . change_furigana($question->explanation) . '</div>'; 
      } 
      @endphp
      <tr class="hik-table-tr-question">
        <td class="hik-table-tr-question-number"><span class="question_number"><?php echo $index; ?></span><span style="display: none">{{$question->question}}</span></td>
        <td class="hik-table-tr-question-question">
         {{ change_furigana( trim($question->question)) }}
        </td> 
      </tr>
    </table>
    <div class="hikari_question_anwser"></div>
    @php  
      $image_path = PREFIX.(new App\ImageSettings())->getExamImagePath(); 
    @endphp
    @include('admin.student.exams.question_'.$question->question_type, array('question', $question, 'image_path' => $image_path ))
    @php
      // if ($number_questions == $count_questions) {
                                                        
      // }
      $number_questions++;
    @endphp
    </div>
    @php
      $index++; 
    @endphp
  @endforeach
@endif

