@extends('client.app-exam')

@section('header_scripts')
    <link href="{{ asset('/css/custom/mock-exam/animate.css') }}" rel="stylesheet">
@stop

@section('content')
<link href="{{ asset('/css/custom/mock-exam/animate.css') }}" rel="stylesheet">
<div id="page-wrapper" class="examform" ng-controller="angExamScript"
    ng-init="initAngData({{ json_encode($bookmarks) }})">
    <div class="container-fluid">
        <div class="row">
            <div class="">
                <div class="panel panel-custom">
                    <div class="panel-body question-ans-box">
                        {{-- Start of questions List --}}
                        <div id="questions_list">
                            <?php
                                $subject_edit = [];
                                foreach ($subjects as $key_subject => $value_subject) {
                                    $subject_edit[$value_subject->id] = $value_subject->subject_code;
                                }

                                $subject_topic = [];
                                $subject_topic_check = [];
                                $question_plit_topic = [];
                                $subject_topic_check_parent = [];
                                $subject_stt = 0;

                                switch ($quiz->category_id) {
                                    case '1':
                                        $i_subject = 1;
                                        $subject_topic = [];
                                        foreach ($subjects as $key_subject => $value_subject) {
                                            $subject_topic[$value_subject['id']] = $value_subject['subject_code'];

                                            switch ($i_subject) {
                                                case '7':
                                                    $subject_topic[$value_subject['id']] .= $questions[41]->topics_child_description;
                                                    break;
                                                case '9':
                                                    $subject_topic[$value_subject['id']] .= '<p>(1)</p>' . $questions[49]->topics_child_description;
                                                    $subject_topic[$value_subject['id']] .= '<hr/><p>(2)</p>' . $questions[52]->topics_child_description;
                                                    $subject_topic[$value_subject['id']] .= '<hr/><p>(3)</p>' . $questions[55]->topics_child_description;
                                                    break;
                                                case '10':
                                                    $subject_topic[$value_subject['id']] .= $questions[59]->topics_child_description;
                                                    break;
                                                case '11':
                                                    $subject_topic[$value_subject['id']] .= $questions[63]->topics_child_description;
                                                    break;
                                                case '12':
                                                    $subject_topic[$value_subject['id']] .= $questions[66]->topics_child_description;
                                                    break;
                                                case '13':
                                                    $subject_topic[$value_subject['id']] .= $questions[70]->topics_child_description;
                                                    break;
                                            }

                                            $i_subject++;
                                        }
                                        $question_plit_topic = [];
                                        if ($quiz->type != 1) {
                                            $question_plit_topic[$questions[49]->id] = '<p style="padding-bottom: 15px;">(1)</p>';
                                            $question_plit_topic[$questions[52]->id] = '<hr/><p style="padding-bottom: 15px;">(2)</p>';
                                            $question_plit_topic[$questions[55]->id] = '<hr/><p style="padding-bottom: 15px;">(3)</p>';
                                        }
                                        break;

                                    case '2':
                                        $i_subject = 1;
                                        $subject_topic = [];
                                        foreach ($subjects as $key_subject => $value_subject) {
                                            $subject_topic[$value_subject['id']] = $value_subject['subject_code'];

                                            switch ($i_subject) {
                                                case '9':
                                                    $subject_topic[$value_subject['id']] .= $questions[50]->topics_child_description;
                                                    break;
                                                case '11':
                                                    $subject_topic[$value_subject['id']] .= '<p>(1)</p>' . $questions[60]->topics_child_description;
                                                    $subject_topic[$value_subject['id']] .= '<hr/><p>(2)</p>' . $questions[63]->topics_child_description;
                                                    $subject_topic[$value_subject['id']] .= '<hr/><p>(3)</p>' . $questions[66]->topics_child_description;
                                                    break;
                                                case '12':
                                                    $subject_topic[$value_subject['id']] .= $questions[69]->topics_child_description;
                                                    break;
                                                case '13':
                                                    $subject_topic[$value_subject['id']] .= $questions[71]->topics_child_description;
                                                    break;
                                                case '14':
                                                    $subject_topic[$value_subject['id']] .= $questions[74]->topics_child_description;
                                                    break;
                                            }

                                            $i_subject++;
                                        }
                                        $question_plit_topic = [];
                                        if ($quiz->type != 1) {
                                            $question_plit_topic[$questions[59]->id] = '<p style="padding-bottom: 15px;">(1)</p>';
                                            $question_plit_topic[$questions[62]->id] = '<hr/><p style="padding-bottom: 15px;">(2)</p>';
                                            $question_plit_topic[$questions[65]->id] = '<hr/><p style="padding-bottom: 15px;">(3)</p>';
                                        }
                                        break;

                                    case '3':
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
                                                    $subject_topic[$value_subject_topic->subject_id] = $subject_edit[$value_subject_topic->subject_id] . '<p class="subject_stt subject_stt_' . $value_subject_topic->subject_id . '">(1)</p>' . $value_subject_topic->topics_child_description;
                                                } else {
                                                    $subject_topic[$value_subject_topic->subject_id] = $subject_edit[$value_subject_topic->subject_id] . $value_subject_topic->topics_child_description;
                                                }
                                                $subject_topic_check[$value_subject_topic->subject_id] = $value_subject_topic->topic_id;
                                                $subject_topic_check_parent[$value_subject_topic->subject_id] = 0;
                                            }
                                        }
                                        break;

                                    case '4':
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
                                                    $subject_topic[$value_subject_topic->subject_id] = $subject_edit[$value_subject_topic->subject_id] . '<p class="subject_stt subject_stt_' . $value_subject_topic->subject_id . '">(1)</p>' . $value_subject_topic->topics_child_description;
                                                } else {
                                                    $subject_topic[$value_subject_topic->subject_id] = $subject_edit[$value_subject_topic->subject_id] . $value_subject_topic->topics_child_description;
                                                }
                                                $subject_topic_check[$value_subject_topic->subject_id] = $value_subject_topic->topic_id;
                                                $subject_topic_check_parent[$value_subject_topic->subject_id] = 0;
                                            }
                                        }

                                        break;

                                    case '5':
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
                                                    $subject_topic[$value_subject_topic->subject_id] = $subject_edit[$value_subject_topic->subject_id] . '<p class="subject_stt subject_stt_' . $value_subject_topic->subject_id . '">(1)</p>' . $value_subject_topic->topics_child_description;
                                                } else {
                                                    $subject_topic[$value_subject_topic->subject_id] = $subject_edit[$value_subject_topic->subject_id] . $value_subject_topic->topics_child_description;
                                                }
                                                $subject_topic_check[$value_subject_topic->subject_id] = $value_subject_topic->topic_id;
                                                $subject_topic_check_parent[$value_subject_topic->subject_id] = 0;
                                            }
                                        }

                                        break;
                                }
                            ?>
                            <style type="text/css">
                                .subject_stt {
                                    display: none;
                                }

                                .subject_stt_{{ $subject_stt }} {
                                    display: block;
                                }
                            </style>
                            <div class="questions">
                                <div class="question-section col-xs-6">
                                    <div class="show_mondai hikari-croll-full" id="hikari-mondaiscroll">
                                        @php $i_subject_topic = 1; @endphp
                                        @foreach ($subject_topic as $key_subject_topic => $value_subject_topic)
                                            <div class="mondai_subject_show" id="mondai_subject_{{ $key_subject_topic }}"
                                                style="padding-right: 8px; {{ $i_subject_topic !== 1 ? 'display:none' : '' }}">
                                                {!! change_furigana($value_subject_topic) !!}
                                            </div>
                                        @php $i_subject_topic++; @endphp
                                        @endforeach
                                    </div>
                                </div>
                                <div class="answer-section col-xs-6" style="position: relative;">
                                    <div class="container-answer hikari-croll-full hikari-w260" id="hikari-croll-full"
                                        style="overflow-y: scroll; overflow-x: hidden;">
                                        <?php
                                            $index = 1;
                                            $subject_check = '';
                                            $style_row = '';
                                            $number_questions = 1;
                                            $count_questions = count($questions);
                                        ?>

                                        @if ($quiz->category_id == 1)
                                        @if ($quiz->type == 1)
                                        @foreach ($questions as $question)
                                            <div class="question_div subject_{{ $question->subject_id }}"
                                                name="question[{{ $question->id }}]" id="{{ $question->id }}"
                                                data-subject="subject_{{ $question->subject_id }}" style="display:none;"
                                                value="0">

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
                                                    @if ($number_questions <= 35) <?php if (!empty($question->explanation)) {
                                                        echo '<div class="hik-table-tr-question">' .
                                                            change_furigana($question->explanation) . '</div>';
                                                        }
                                                        ?>
                                                        <tr class="hik-table-tr-question">
                                                            <td class="hik-table-tr-question-number"><span
                                                                    class="question_number">
                                                                    <?php echo $index; ?>
                                                                </span><span style="display: none">{{ $question->question
                                                                    }}</span>
                                                            </td>
                                                            <td class="hik-table-tr-question-question">
                                                                {{ change_furigana(trim($question->question)) }}
                                                            </td>
                                                        </tr>
                                                        @endif

                                                        @if ($number_questions == 36)
                                                        <tr class="hik-table-tr-question">
                                                            <td class="hik-table-tr-question-number" style="width: 52px;"><span
                                                                    class="question_number">3</span></td>
                                                            <td class="hik-table-tr-question-question">
                                                                {{ change_furigana($question->explanation) }}
                                                            </td>
                                                        </tr>

                                                        <tr class="hik-table-tr-question">
                                                            <td class="hik-table-tr-question-number" style="width: 52px;">
                                                                <span>質問1</span>
                                                            </td>
                                                            <td class="hik-table-tr-question-question">
                                                                {{ change_furigana(trim($question->question)) }}
                                                            </td>
                                                        </tr>
                                                        @endif

                                                        @if ($number_questions == 37)
                                                        <tr class="hik-table-tr-question">
                                                            <td class="hik-table-tr-question-number" style="width: 52px;">
                                                                <span>質問2</span>
                                                            </td>
                                                            <td class="hik-table-tr-question-question">
                                                                {{ change_furigana(trim($question->question)) }}
                                                            </td>
                                                        </tr>
                                                    @endif
                                                </table>

                                                <div class="hikari_question_anwser"></div>
                                                <?php $image_path = PREFIX . (new App\ImageSettings())->getExamImagePath(); ?>
                                                @include(
                                                    'client.mock-exam.questions.question_' . $question->question_type,
                                                    ['question', $question, 'image_path' => $image_path])
                                                <?php
                                                    if ($number_questions == $count_questions) {
                                                        //Finish append js after final
                                                        echo '<div id="submit_div" style="text-align: center; padding-top: 10px;"></div>';
                                                    }
                                                    $number_questions++;
                                                ?>
                                            </div>
                                            <?php $index++; ?>
                                        @endforeach

                                        <!-- else phần thi từ vựng - ngữ pháp - đọc hiểu -->
                                        @else
                                        @foreach ($questions as $question)
                                        <div class="question_div subject_{{ $question->subject_id }}"
                                            name="question[{{ $question->id }}]" id="{{ $question->id }}"
                                            data-subject="subject_{{ $question->subject_id }}" style="display:none;"
                                            value="0">
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
                                                <?php if (!empty($question->explanation)) {
                                                    $stt_explanation = '';
                                                    switch ($index) {
                                                        case 46:
                                                            $stt_explanation = '<p>(1)</p>';
                                                            break;
                                                        case 47:
                                                            $stt_explanation = '<p>(2)</p>';
                                                            break;
                                                        case 48:
                                                            $stt_explanation = '<p>(3)</p>';
                                                            break;
                                                        case 49:
                                                            $stt_explanation = '<p>(4)</p>';
                                                            break;
                                                    }
                                                    echo '<div class="hik-table-tr-question">' . $stt_explanation . '</div>';
                                                    echo '<div class="hik-table-tr-question">' . change_furigana($question->explanation) . '</div>';
                                                }
                                                ?>
                                                <tr class="hik-table-tr-question">
                                                    <td class="hik-table-tr-question-number"><span class="question_number">
                                                            <?php echo $index; ?>
                                                        </span><span style="display: none">{{ $question->question }}</span>
                                                    </td>
                                                    <td class="hik-table-tr-question-question">
                                                        {{ change_furigana(trim($question->question)) }}
                                                    </td>
                                                </tr>
                                            </table>
                                            <div class="hikari_question_anwser"></div>
                                            <?php $image_path = PREFIX . (new App\ImageSettings())->getExamImagePath(); ?>
                                            @include(
                                                'client.mock-exam.questions.question_' . $question->question_type,
                                                ['question', $question, 'image_path' => $image_path])
                                            <?php
                                                if ($number_questions == $count_questions) {
                                                    //Finish append js after final
                                                    echo '<div id="submit_div" style="text-align: center; padding-top: 10px;"></div>';
                                                }
                                                $number_questions++;
                                            ?>
                                        </div>
                                        <?php $index++; ?>
                                        @endforeach

                                        @endif
                                        <!-- endif phần N1 -->
                                        @endif

                                        @if ($quiz->category_id == 2)
                                        @if ($quiz->type == 1)
                                        @foreach ($questions as $question)
                                        <div class="question_div subject_{{ $question->subject_id }}"
                                            name="question[{{ $question->id }}]" id="{{ $question->id }}"
                                            data-subject="subject_{{ $question->subject_id }}" style="display:none;"
                                            value="0">

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
                                                @if ($number_questions <= 30) @if (!empty($question->explanation))
                                                    <div class="hik-table-tr-question">
                                                        {{ change_furigana($question->explanation) }}</div>
                                                    @endif
                                                    <tr class="hik-table-tr-question">
                                                        <td class="hik-table-tr-question-number"><span
                                                                class="question_number">
                                                                <?php echo $index; ?>
                                                            </span></td>
                                                        <td class="hik-table-tr-question-question">
                                                            {{ change_furigana(trim($question->question)) }}
                                                        </td>
                                                    </tr>
                                                    @endif

                                                    @if ($number_questions == 31)
                                                    <tr class="hik-table-tr-question">
                                                        <td class="hik-table-tr-question-number" style="width: 70px;"><span
                                                                class="question_number">3</span></td>
                                                        <td class="hik-table-tr-question-question">
                                                            {{ change_furigana(trim($question->explanation)) }}
                                                        </td>
                                                    </tr>
                                                    <tr class="hik-table-tr-question">
                                                        <td class="hik-table-tr-question-number" style="width: 70px">
                                                            <span>質問1</span>
                                                        </td>
                                                        <td class="hik-table-tr-question-question">
                                                            {{ change_furigana(trim($question->question)) }}
                                                        </td>
                                                    </tr>
                                                    @endif


                                                    @if ($number_questions == 32)
                                                    <tr class="hik-table-tr-question">
                                                        <td class="hik-table-tr-question-number" style="width: 70px;">
                                                            <span>質問2</span>
                                                        </td>
                                                        <td class="hik-table-tr-question-question">
                                                            {{ change_furigana(trim($question->question)) }}
                                                        </td>
                                                    </tr>
                                                @endif
                                            </table>

                                            <div class="hikari_question_anwser"></div>
                                            <?php $image_path = PREFIX . (new App\ImageSettings())->getExamImagePath(); ?>
                                            @include(
                                                'client.mock-exam.questions.question_' . $question->question_type,
                                                ['question', $question, 'image_path' => $image_path])
                                            <?php
                                                if ($number_questions == $count_questions) {
                                                    //Finish append js after final
                                                    echo '<div id="submit_div" style="text-align: center; padding-top: 10px;"></div>';
                                                }
                                                $number_questions++;
                                            ?>
                                        </div>
                                        <?php $index++; ?>
                                        @endforeach

                                        <!-- else phần thi từ vựng - ngữ pháp - đọc hiểu -->
                                        @else
                                        @foreach ($questions as $question)
                                        <div class="question_div subject_{{ $question->subject_id }}"
                                            name="question[{{ $question->id }}]" id="{{ $question->id }}"
                                            data-subject="subject_{{ $question->subject_id }}" style="display:none;"
                                            value="0">
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
                                                <?php if (!empty($question->explanation)) {
                                                    $stt_explanation = '';

                                                    switch ($index) {
                                                        case 55:
                                                            $stt_explanation = '<p>(1)</p>';
                                                            break;
                                                        case 56:
                                                            $stt_explanation = '<p>(2)</p>';
                                                            break;
                                                        case 57:
                                                            $stt_explanation = '<p>(3)</p>';
                                                            break;
                                                        case 58:
                                                            $stt_explanation = '<p>(4)</p>';
                                                            break;
                                                        case 59:
                                                            $stt_explanation = '<p>(5)</p>';
                                                            break;
                                                    }

                                                    echo '<div class="hik-table-tr-question">' . $stt_explanation . '</div>';
                                                    echo '<div class="hik-table-tr-question">' . change_furigana($question->explanation) . '</div>';
                                                }
                                                ?>
                                                <tr class="hik-table-tr-question">
                                                    <td class="hik-table-tr-question-number"><span class="question_number">
                                                            <?php echo $index; ?>
                                                        </span><span style="display: none">{{ $question->question }}</span>
                                                    </td>
                                                    <td class="hik-table-tr-question-question">
                                                        {{ change_furigana(trim($question->question)) }}
                                                    </td>
                                                </tr>
                                            </table>
                                            <div class="hikari_question_anwser"></div>
                                            <?php $image_path = PREFIX . (new App\ImageSettings())->getExamImagePath(); ?>
                                            @include(
                                            'client.mock-exam.questions.question_' . $question->question_type,
                                            ['question', $question, 'image_path' => $image_path]
                                            )
                                            <?php
                                                if ($number_questions == $count_questions) {
                                                    //Finish append js after final
                                                    echo '<div id="submit_div" style="text-align: center; padding-top: 10px;"></div>';
                                                }
                                                $number_questions++;
                                            ?>
                                        </div>
                                        <?php $index++; ?>
                                        @endforeach

                                        @endif
                                        <!-- endif phần N2 -->
                                        @endif

                                        @if ($quiz->category_id == 3 || $quiz->category_id == 4 || $quiz->category_id == 5)

                                        @foreach ($questions as $question)
                                        <div class="question_div subject_{{ $question->subject_id }}"
                                            name="question[{{ $question->id }}]" id="{{ $question->id }}"
                                            data-subject="subject_{{ $question->subject_id }}" style="display:none;"
                                            value="0">

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
                                                <?php if (!empty($question->explanation)) {
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
                                                    echo '<div class="hik-table-tr-question">' . $stt_explanation . '</div>';
                                                    echo '<div class="hik-table-tr-question">' . change_furigana($question->explanation) . '</div>';
                                                }
                                                ?>
                                                <tr class="hik-table-tr-question">
                                                    <td class="hik-table-tr-question-number"><span class="question_number">
                                                            <?php echo $index; ?>
                                                        </span><span style="display: none">{{ $question->question }}</span>
                                                    </td>
                                                    <td class="hik-table-tr-question-question">
                                                        {{ change_furigana(trim($question->question)) }}
                                                    </td>
                                                </tr>
                                            </table>
                                            <div class="hikari_question_anwser"></div>
                                            <?php $image_path = PREFIX . (new App\ImageSettings())->getExamImagePath(); ?>
                                            @include(
                                                'client.mock-exam.questions.question_' . $question->question_type,
                                                ['question', $question, 'image_path' => $image_path])
                                            <?php
                                                if ($number_questions == $count_questions) {
                                                    //Finish append js after final
                                                    echo '<div id="submit_div" style="text-align: center; padding-top: 10px;"></div>';
                                                }
                                                $number_questions++;
                                            ?>
                                        </div>
                                        <?php $index++; ?>
                                        @endforeach

                                        @endif

                                    </div>
                                    <a href="javascript:void(0);" class="hikari-prenext hikari-pre" onclick="pre_mondai();">
                                        <span class="hik-trang hik-trang-truoc">
                                            <i class="fa fa-arrow-left"></i>
                                        </span>
                                    </a>
                                    <a href="javascript:void(0);" class="hikari-prenext hikari-next"
                                        onclick="next_mondai();">
                                        <span class="hik-trang hik-trang-sau">
                                            <i class="fa fa-arrow-right"></i>
                                        </span>
                                    </a>

                                </div>
                            </div>
                        </div>
                        {{-- End of questions List --}}
                        {{-- <hr> --}}
                    </div>
                </div>
            </div>
            <input type="hidden" name="quiz_id" id="quiz_id" value="{{ $quiz->id }}">
            <input type="hidden" name="student_id" id="student_id" value="{{ $user->id }}">
            <input type="hidden" id="slug_id" value="{{ $quiz->slug }}">
            <input type="hidden" id="dueration" value="{{ $quiz->dueration }}">
        </div>
    </div>
</div>
@stop
@section('footer_scripts')
@include('client.mock-exam.components.js-scripts')
@include('client.mock-exam.components.editor')
<?php
    $finish = '';
    if (checkRole(getUserGrade(5)) || checkRole(getUserGrade(6))) {
        $finish = 'finish finish_exam';
    }
    ?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.22.2/moment.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
            $('.hik-trang-truoc').hide();
            //Zoom image
            $("img").wrap('<div data-zoom data-zoom-max="1"></div>');
        });

        function pre_mondai() {
            var page_pre = Number($('.hikari-page.active').attr('data-page')) - 1;
            var mondai_pre = $('.page_' + page_pre).attr('data-mondai');
            showSubjectQuestion(mondai_pre);
            $('.hikari-page').removeClass('active');
            $('.hikari-page.' + mondai_pre).addClass('active');
            $('.hikari-page.' + mondai_pre).addClass('hikari_active');
            if (page_pre == 1) {
                $('.hik-trang-truoc').hide();
            } else {
                $('.hik-trang-truoc').show();
            }
            $('.hik-trang-sau').show();
        }

        function next_mondai() {
            var page_next = Number($('.hikari-page.active').attr('data-page')) + 1;
            var mondai_next = $('.page_' + page_next).attr('data-mondai');
            showSubjectQuestion(mondai_next);
            $('.hikari-page').removeClass('active');
            $('.hikari-page.' + mondai_next).addClass('active');
            $('.hikari-page.' + mondai_next).addClass('hikari_active');
            var number_page = $('ul.subject-page li').length;
            if (page_next == number_page) {
                $('.hik-trang-sau').hide();
                $('div#submit_div').html(
                    '<span class="submit-question" style="text-align: center;"><a href="javascript:void(0);" class="btn btn-lg btn-danger button <?php echo $finish; ?>" type="button" style="padding: 10px;">NỘP BÀI</a></span>'
                    );
            } else {
                $('.hik-trang-sau').show();
            }
            $('.hik-trang-truoc').show();
        }
        $(window).on('beforeunload', function(e) {
            saveFormData();
        });

        function saveFormData() {
            $("#onlineexamform").submit();
        }
        $(document).ready(function() {
            current_hours = {{ $time_hours }};
            current_minutes = {{ $time_minutes }};
            current_seconds = {{ $time_seconds }};

            @if ($current_question_id)
                resumeSetup('{{ $current_question_id }}');
            @endif
            $('input').click(function() {
                qn = parseInt($(this).attr('name'));
                saveResumeExamData(qn);
            });
            initTimer(false);
        });

        function clearLocalStorage() {
            const slugKey = '{{ $quiz->slug }}';
            window.localStorage.removeItem(slugKey);
            const slug = document.getElementById('slug_id').value;
            window.localStorage.removeItem("AUDIO_KEY");
        }

        function initTimer(isError) {
            // Check local stogate
            const slug = '{{ $quiz->slug }}';
            const localStorage = window.localStorage.getItem(slug);

            if (!localStorage) {
                const currentTime = moment().format("YYYY-MM-DD HH:mm:ss");
                const dueration = document.getElementById('dueration').value;
                // Accepts numbers from 0 to 59. If the range is exceeded, it will bubble up to the hour.
                const endTime = moment().add(dueration, 'minutes').format('YYYY-MM-DD HH:mm:ss');
                console.log("dueration: " + dueration);
                console.log("endTime: " + endTime);
                const object = {
                    "START_TIME": currentTime,
                    "DURATION": dueration,
                    "END_TIME": endTime,
                    "SLUG": slug,
                    "ANSWER": [],
                };
                window.localStorage.setItem(slug, JSON.stringify(object));
                intilizetimer(current_hours, current_minutes, current_seconds);

            } else {
                const data = JSON.parse(localStorage);
                const audioStorage = window.localStorage.getItem("AUDIO_KEY");

                const endTime = moment(data.END_TIME);

                const currentDate = moment().format("YYYY-MM-DD");
                // Nếu thời gian hiện tại lớn hơn thời gian end của bài thi
                // Check if a moment is after another moment. The first argument will be parsed as a moment, if not already so.
                const isAfter = moment(currentDate).isAfter(data.END_TIME);
                //  console.log("isAfter: "+ isAfter);
                if (isAfter) {
                    // Đã hết time làm bài
                    console.log('Đã hết time làm bài ')
                } else {
                    // Tính thời gian đã sử dụng để thi
                    const currentTime = moment().format("YYYY-MM-DD HH:mm:ss");
                    const momentTime = moment(currentTime);
                    const diffInMinutes = momentTime.diff(data.START_TIME, 'minutes');
                    const minutes = data.DURATION;
                    if (diffInMinutes >= minutes) {
                        // Đã hết time làm bài
                        console.log('Đã hết time làm bài ')
                    } else {
                        // Tính thời gian còn lại
                        const audioStorage = window.localStorage.getItem("AUDIO_KEY");
                        let momentStartTime = data.START_TIME;

                        // Nếu có thời gian lỗi, cộng thêm vào lúc start time
                        if (audioStorage) {
                            const audioData = JSON.parse(audioStorage);
                            const secondError = audioData.SECONDS_ERROR;
                            momentStartTime = moment(data.START_TIME).add(secondError, 'seconds').format(
                                'YYYY-MM-DD HH:mm:ss');
                        }

                        const duration = moment.duration(momentTime.diff(momentStartTime));
                        const minutes = duration.minutes();
                        let minutesEnd = Number(data.DURATION) - Number(minutes);
                        const currentSeconds = momentTime.seconds();

                        let seconds = 60 - duration.seconds();
                        minutesEnd = minutesEnd - 1;

                        // Nếu cùng thời gian thì giảm 1
                        const formatMin = minutesEnd.toString().length < 2 ? `0${minutesEnd.toString()}` : minutesEnd
                            .toString();
                        const formatSecond = seconds.toString().length < 2 ? `0${seconds.toString()}` : seconds.toString();
                        $('#mins').text(formatMin);
                        $('#seconds').text(formatSecond);
                        console.log(`Time con lai: ${formatMin}:${formatSecond}`);
                        intilizetimer(0, minutesEnd, seconds);

                        // Check lại đáp án trước đó
                        if (!isError) {
                            const answer = data.ANSWER || [];
                            if (answer && answer.length > 0) {
                                answer.forEach(element => {
                                    let index = element.data.findIndex(x => x == true);
                                    $(`input[name="${element.id}[]"][value="${Number(index) + 1}"]`).prop("checked",
                                        true);
                                });
                            }
                        }
                    }
                }
            }
        }

        function resumeSetup(current_question_id) {
            DIV_REFERENCE.first().hide();
            current_question_number = $('#' + current_question_id).attr('data-current-question');
            $('#question_number').html(current_question_number);
            $('#' + current_question_id).fadeIn(300);
        }
        /**
         * intilizetimer(hours, minutes, seconds)
         * This method will set the values to defaults
         */
        document.onmousedown = disableclick;

        function disableclick(event) {
            if (event.button == 2) {
                return false;
            }
        }
        $(document).ready(function() {
            subject = $('.question_div').attr('data-subject');
            $('.' + subject).fadeIn();
            mondai = $('.' + subject + '_left').html();
            $('.show_mondai').html(mondai);
            gach_cheo();
            $('#hikari-morong-button').on('click', function() {
                // 拡張 = mở rộng
                if ($(this).attr('data-display') == 1) {
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
                    $(this).attr('data-display', 0);
                    $(this).html('<i class="fa fa-arrow-up"></i>');
                    gach_cheo();
                } else {
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
                    $(this).attr('data-display', 1);
                    gach_cheo();
                }
            });
        });

        function gach_cheo() {
            var height_description_mondai = $(".hikari_description_child").height();
            var height_showmondai = $(".show_mondai").height();
            var gach_cheo = height_showmondai - height_description_mondai - 30;
            $(".hikair-gach-cheo").css({
                "height": gach_cheo + "px",
                "width": "100%"
            });
        }
</script>
<script>
    $('body').on('click', '.finish', function() {
            alertify.set({
                labels: {
                    ok: "Có",
                    cancel: "Không"
                }
            });
            alertify.confirm('Bạn có chắc chắn muốn nộp bài ngay không?',
                function(e) {
                    if (e) {
                        // Remore local stogage
                        clearLocalStorage();

                        $("#onlineexamform").submit();
                        $("body.ng-scope").hide();
                        $(window).off('beforeunload');
                    } else {}
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