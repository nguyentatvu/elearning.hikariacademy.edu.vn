@php
  $quiz = $data['quiz'];
  $questions = $data['questions'];
  $subjects = $data['subjects'];
  $image_path = PREFIX.(new App\ImageSettings())->getExamImagePath(); 
  $index = 1;
@endphp
<!doctype html>
<html><head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<title>PDF</title>
<style>
  @font-face{
      font-family: ipag;
      font-style: normal;
      font-weight: normal;
      src:url('{{ storage_path('fonts/ipam.ttf')}}');
  }
  body {
  font-family: ipag;
  }
  table {
      width: 100%;
      table-layout: fixed;
  }
  table td {
      word-wrap: break-word;         
      overflow-wrap: break-word;     
  }
  /*.td-question{
      width: 4%;
      vertical-align: top;
  }
  .p-question{
      padding-left: 8px;border: 1px solid black
  }*/
</style>
</head><body>
  @php  
    $subject_edit = array();
    foreach ($subjects as $key_subject => $value_subject) {
      $subject_edit[$value_subject->id] = $value_subject->subject_code;
    }
    $subject_topic = array();
    $subject_topic_check = array();
    $question_plit_topic = array();
    $subject_topic_check_parent = array();
    $subject_stt = 0;
    switch ($quiz->category_id) {
      case 1:
        foreach ($questions as $key_subject_topic => $value_subject_topic) {
            if (array_key_exists($value_subject_topic->subject_id, $subject_topic)) {
             if ($subject_topic_check[$value_subject_topic->subject_id] != $value_subject_topic->topic_id) {
              if ($subject_topic_check_parent[$value_subject_topic->subject_id] != $value_subject_topic->topics_child_status) {

                  $subject_topic[$value_subject_topic->subject_id] .= $value_subject_topic->topics_child_description;
                
              $question_plit_topic[$value_subject_topic->id] = '';
              $subject_topic_check_parent[$value_subject_topic->subject_id] = $value_subject_topic->topics_child_status;
              $subject_stt = $value_subject_topic->subject_id;
            }
            $subject_topic_check[$value_subject_topic->subject_id] = $value_subject_topic->topic_id;
            }
          } else {
            $space_topics = '';
            if (!empty($value_subject_topic->topics_parent_description)) {
              //$space_topics = '<div style="width: 100%">&nbsp;</div>';
            }

              $subject_topic[$value_subject_topic->subject_id] = $subject_edit[$value_subject_topic->subject_id] . $value_subject_topic->topics_child_description;

            $subject_topic_check[$value_subject_topic->subject_id] = $value_subject_topic->topic_id;
            $subject_topic_check_parent[$value_subject_topic->subject_id] = 0;
            }
          }
        break;
      case 2:
        foreach ($questions as $key_subject_topic => $value_subject_topic) {
            if (array_key_exists($value_subject_topic->subject_id, $subject_topic)) {
             if ($subject_topic_check[$value_subject_topic->subject_id] != $value_subject_topic->topic_id) {
              if ($subject_topic_check_parent[$value_subject_topic->subject_id] != $value_subject_topic->topics_child_status) {

                  $subject_topic[$value_subject_topic->subject_id] .= $value_subject_topic->topics_child_description;
                
              $question_plit_topic[$value_subject_topic->id] = '';
              $subject_topic_check_parent[$value_subject_topic->subject_id] = $value_subject_topic->topics_child_status;
              $subject_stt = $value_subject_topic->subject_id;
            }
            $subject_topic_check[$value_subject_topic->subject_id] = $value_subject_topic->topic_id;
            }
          } else {
            $space_topics = '';
            if (!empty($value_subject_topic->topics_parent_description)) {
              //$space_topics = '<div style="width: 100%">&nbsp;</div>';
            }

              $subject_topic[$value_subject_topic->subject_id] = $subject_edit[$value_subject_topic->subject_id] . $value_subject_topic->topics_child_description;

            $subject_topic_check[$value_subject_topic->subject_id] = $value_subject_topic->topic_id;
            $subject_topic_check_parent[$value_subject_topic->subject_id] = 0;
            }
          }
        break;
      case 3:
        foreach ($questions as $key_subject_topic => $value_subject_topic) {
            if (array_key_exists($value_subject_topic->subject_id, $subject_topic)) {
             if ($subject_topic_check[$value_subject_topic->subject_id] != $value_subject_topic->topic_id) {
              if ($subject_topic_check_parent[$value_subject_topic->subject_id] != $value_subject_topic->topics_child_status) {
                  $subject_topic[$value_subject_topic->subject_id] .= '<div style="width: 100%">(2)</div>' . $value_subject_topic->topics_child_description;
                  $question_plit_topic[$value_subject_topic->id] = '<div style="width: 100%"></div>';
              $subject_topic_check_parent[$value_subject_topic->subject_id] = $value_subject_topic->topics_child_status;
              $subject_stt = $value_subject_topic->subject_id;
            }
            $subject_topic_check[$value_subject_topic->subject_id] = $value_subject_topic->topic_id;
          }
        } else {
          $space_topics = '';
          if (!empty($value_subject_topic->topics_parent_description)) {
            //$space_topics = '<div>&nbsp;</div>';
          }
          $subject_topic[$value_subject_topic->subject_id] = $subject_edit[$value_subject_topic->subject_id] . $value_subject_topic->topics_child_description;
          $subject_topic_check[$value_subject_topic->subject_id] = $value_subject_topic->topic_id;
          $subject_topic_check_parent[$value_subject_topic->subject_id] = 0;
          }
        }
        break;
      case 4:
        foreach ($questions as $key_subject_topic => $value_subject_topic) {
            if (array_key_exists($value_subject_topic->subject_id, $subject_topic)) {
             if ($subject_topic_check[$value_subject_topic->subject_id] != $value_subject_topic->topic_id) {
              if ($subject_topic_check_parent[$value_subject_topic->subject_id] != $value_subject_topic->topics_child_status) {

                  $subject_topic[$value_subject_topic->subject_id] .= $value_subject_topic->topics_child_description;
                
              $question_plit_topic[$value_subject_topic->id] = '';
              $subject_topic_check_parent[$value_subject_topic->subject_id] = $value_subject_topic->topics_child_status;
              $subject_stt = $value_subject_topic->subject_id;
            }
            $subject_topic_check[$value_subject_topic->subject_id] = $value_subject_topic->topic_id;
            }
          } else {
            $space_topics = '';
            if (!empty($value_subject_topic->topics_parent_description)) {
              //$space_topics = '<div style="width: 100%">&nbsp;</div>';
            }

              $subject_topic[$value_subject_topic->subject_id] = $subject_edit[$value_subject_topic->subject_id] . $value_subject_topic->topics_child_description;

            $subject_topic_check[$value_subject_topic->subject_id] = $value_subject_topic->topic_id;
            $subject_topic_check_parent[$value_subject_topic->subject_id] = 0;
            }
          }
        break;

    }
    //dd($subject_topic);
  @endphp

  @if ($quiz->category_id)
    @foreach($questions as $question)
      {{-- In Mondai tiêu đề --}}
      @if(array_key_exists($question->subject_id, $subject_topic))
        <div style="width:98%">
          {!! change_furigana_pdf($subject_topic[$question->subject_id]) !!}
        </div>
        @php
          unset($subject_topic[$question->subject_id]);
        @endphp
      @endif
      {{-- In diễn giải --}}
      @if(!empty($question->explanation))
        @php  
              $stt_explanation = '';
              if ($quiz->category_id == 3) {
                switch ($index) {
                  case 24:
                  $stt_explanation = '<div style="width:100%">(1)</div>';
                  break;
                  case 25:
                  $stt_explanation = '<div style="width:100%">(2)</div>';
                  break;
                  case 26:
                  $stt_explanation = '<div style="width:100%">(3)</div>';
                  break;
                  case 27:
                  $stt_explanation = '<div style="width:100%">(4)</div>';
                  break;
              } 
              }
              if ($quiz->category_id == 4) {
                switch ($index) {
                  case 26:
                  $stt_explanation = '<div style="width:100%">(1)</div>';
                  break;
                  case 27:
                  $stt_explanation = '<div style="width:100%">(2)</div>';
                  break;
                  case 28:
                  $stt_explanation = '<div style="width:100%">(3)</div>';
                  break;
                  case 29:
                  $stt_explanation = '<div style="width:100%">(4)</div>';
                  break;
              } 
              }
              if ($quiz->category_id == 5) {
                  switch ($index) {
                    case 27:
                    $stt_explanation = '<div style="width:100%">(1)</div>';
                    break;
                    case 28:
                    $stt_explanation = '<div style="width:100%">(2)</div>';
                    break;
                    case 29:
                    $stt_explanation = '<div style="width:100%">(3)</div>';
                    break;
                } 
              }
            @endphp
        <table style="width:100%">
          <tr><td>{!! change_furigana_pdf($question->explanation) !!}</td></tr>
        </table>
      @endif
      {{--@end In diễn giải --}}
      {{-- In câu hỏi --}}
      <table style="width:100%">
        <tr><td style="width:4%"><div style="padding: 4px 6px;  border: 1px solid #000;">{{$index}}<div></span></td><td>{!! change_furigana_pdf($question->question) !!}</td></tr>
      </table>
      {{-- In câu trả lời --}}
      @php
        $index++;
        $answers = json_decode($question->answers);
      @endphp
      @if($question->question_show_type == 0)
        <table style="width:98%">
            <?php $i = 1;?>
            @foreach($answers as $answer)
                <?php echo ($i == 1 || $i == 3)?"<tr>":''; ?><td style="width: 3%"><span> <?php echo $i; ?></span></td><td style="width: 47%; padding-left: 10px;">
            {!! change_furigana_pdf($answer->option_value) !!}
            @if($answer->has_file)
              {{-- <img src="{{$image_path.$answer->file_name}}" style="width:150px"> --}}
            @endif
          </td><?php if ($question->total_answers == $i) {
              echo "</tr>";
              break;
          }?><?php echo ($i == 2 || $i == 4) ? "</tr>":''; ?>
              <?php $i++; ?>
            @endforeach
        </table>
      @endif
      @if($question->question_show_type == 1)
        <table style="width:98%">
            <?php $i = 1;?>
            @foreach($answers as $answer)
                <tr><td style="width: 3%"><span><?php echo $i; ?></span></td>
                    <td style="width: 97%; padding-left: 10px;">
                      {!! change_furigana_pdf($answer->option_value) !!}
                      @if($answer->has_file)
                        {{-- <img src="{{$image_path.$answer->file_name}}" width="150"> --}}
                      @endif
                    </td></tr>
              @php 
                $i++;
              @endphp            
            @endforeach
        </table>
      @endif
    @endforeach 
  @endif
</body></html>
