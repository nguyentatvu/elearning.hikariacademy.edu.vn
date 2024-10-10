<?php

namespace App\Http\Controllers;

use App\ExamScore;
use App\Logger;
use App\QuestionBank;
use App\Quiz;
use App\QuizResult;
use App\QuizResultfinish;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Input;
use Spipu\Html2Pdf\Exception\ExceptionFormatter;
use Spipu\Html2Pdf\Exception\Html2PdfException;
use Spipu\Html2Pdf\Html2Pdf;

class MockExamController extends Controller
{
    public function __construct() {
        $this->middleware('auth');
    }

    public function getInstruction($slug) {
        $record = Quiz::getRecordWithSlug($slug);

        $instruction_page = $record->instructions_page_id
            ? \App\Instruction::find($record->instructions_page_id)
            : null;

        $data = [
            'instruction_data'   => $instruction_page->content ?? '',
            'instruction_title'  => $instruction_page->title ?? '',
            'record'             => $record,
            'active_class'       => 'exams',
            'title'              => 'Hướng dẫn',
            'block_navigation'   => true
        ];

        return view('client.mock-exam.exam-instruction', $data);
    }

    /**
     * Start mock exam
     *
     * @param  string $slug
     * @return \Illuminate\Http\Response
     */
    public function startExam(string $slug)
    {
        $quiz = Quiz::getRecordWithSlug($slug);

        // RETRIEVE EXAM SERIES DATA IN ONE QUERY
        $examseries_data = DB::table('examseries_data')
            ->where('quiz_id', $quiz->id)
            ->join('examseries', 'examseries_data.examseries_id', '=', 'examseries.id')
            ->select('examseries_data.examseries_id', 'examseries.slug')
            ->first();

        if (!$examseries_data) {
            abort(404, 'Exam series not found');
        }

        $user = Auth::user();
        $any_resume_exam = false;
        $time = convertToHoursMins($quiz->dueration);
        $current_state = null;

        // PREPARE QUESTIONS IF NO RESUME EXAM
        $prepared_records = !$any_resume_exam ? (object) $quiz->prepareQuestions($quiz->getQuestions()) : null;

        // REFORMAT CURRENT STATE IF IT EXISTS
        if ($current_state) {
            $current_state = array_map('intval', $current_state);
        }

        // PREPARE DATA FOR VIEW
        $data = [
            'time_hours' => makeNumber($time['hours'], 2, '0', 'left'),
            'time_minutes' => $quiz->dueration,
            'time_seconds' => makeNumber($time['seconds'], 2, '0', 'left'),
            'atime_hours' => makeNumber($time['hours'], 2, '0', 'left'),
            'atime_minutes' => $quiz->dueration,
            'atime_seconds' => makeNumber($time['seconds'], 2, '0', 'left'),
            'quiz' => $quiz,
            'user' => $user,
            'active_class' => 'exams',
            'title' => change_furigana_title($quiz->title),
            'right_bar' => true,
            'block_navigation' => true,
            'examseries_slug' => $examseries_data->slug,
            'current_state' => $current_state,
            'current_question_id' => null,
            'questions' => $prepared_records->questions ?? [],
            'subjects' => $prepared_records->subjects ?? [],
            'bookmarks' => array_pluck($prepared_records->questions ?? [], 'id'),
            'right_bar_path' => 'client.mock-exam.components.exam-right-bar',
            'right_bar_data' => [
                'questions' => $prepared_records->questions ?? [],
                'current_state' => $current_state,
                'quiz' => $quiz,
                'time_hours' => makeNumber($time['hours'], 2, '0', 'left'),
                'time_minutes' => $quiz->dueration,
                'atime_hours' => makeNumber($time['hours'], 2, '0', 'left'),
                'atime_minutes' => $quiz->dueration,
            ]
        ];

        return view('client.mock-exam.exam-form', $data);
    }

    /**

     * After the exam complets the data will be submitted to this method

     * @param Request $request [description]

     * @param [type] $slug [description]

     * @return [type] [description]

     */

    public function finishExam(Request $request, $slug) {

        $quiz = Quiz::getRecordWithSlug($slug);

        $examseries_data = DB::table('examseries_data')

            ->where('quiz_id', '=', $quiz->id)

            ->first();

        $examseries_id = $examseries_data->examseries_id;

        $examseries_table = DB::table('examseries')

            ->where('id', '=', $examseries_id)

            ->first();

        $category_id = $examseries_table->category_id;

        $examseries_title = $examseries_table->title;

        $user_record = Auth::user();

        $input_data = Input::all();

        $answers = array();

        $time_spent = $request->time_spent;

        //Remove _token key from answers data prepare the list of answers at one place

        foreach ($input_data as $key => $value) {

            if ($key == '_token' || $key == 'time_spent')

                continue;

            $answers[$key] = $value;
        }

        //Get the list of questions and prepare the list at one place

        //This is to find the unanswered questions

        //List the unanswered questions list at one place

        $questions = DB::table('questionbank_quizzes')->select('questionbank_id', 'subject_id')

            ->where('quize_id', '=', $quiz->id)

            ->get();

        $subject = [];

        $time_spent_not_answered = [];

        $not_answered_questions = [];

        foreach ($questions as $q) {

            $subject_id = $q->subject_id;

            if (! array_key_exists($q->subject_id, $subject)) {

                $subject[$subject_id]['subject_id'] = $subject_id;

                $subject[$subject_id]['correct_answers'] = 0;

                $subject[$subject_id]['wrong_answers'] = 0;

                $subject[$subject_id]['not_answered'] = 0;

                $subject[$subject_id]['time_spent'] = 0;

                $subject[$subject_id]['time_to_spend'] = 0;

                $subject[$subject_id]['time_spent_correct_answers'] = 0;

                $subject[$subject_id]['time_spent_wrong_answers'] = 0;
            }

            if (! array_key_exists($q->questionbank_id, $answers)) {

                $subject[$subject_id]['not_answered'] += 1;

                $not_answered_questions[] = $q->questionbank_id;

                $time_spent_not_answered[$q->questionbank_id]['time_to_spend'] = 0;

                $time_spent_not_answered[$q->questionbank_id]['time_spent'] = $time_spent[$q->questionbank_id];

                $subject[$subject_id]['time_spent'] += $time_spent[$q->questionbank_id];
            }
        }

        $result = $this->processAnswers($answers, $subject, $time_spent, $quiz->negative_mark);

        $result['not_answered_questions'] = json_encode($not_answered_questions);


        $result['time_spent_not_answered_questions'] = '';

        $result = (object) $result;

        $answers = json_encode($answers);

        $record = new QuizResult();

        $record->quiz_id = $quiz->id;

        $record->user_id = Auth::user()->id;

        $record->marks_obtained = $result->marks_obtained;

        $record->total_marks = $quiz->total_marks;

        $record->percentage = '';

        $exam_status = 'pending';

        $record->exam_status = $exam_status;

        $record->answers = $answers;

        $record->subject_analysis = $result->subject_analysis;

        $record->correct_answer_questions = $result->correct_answer_questions;

        $record->wrong_answer_questions = $result->wrong_answer_questions;

        $record->not_answered_questions = $result->not_answered_questions;

        $record->time_spent_correct_answer_questions = '';

        $record->time_spent_wrong_answer_questions = '';

        $record->time_spent_not_answered_questions = '';

        $record->slug = getHashCode();

        $record->time_total_answers = $request->time;

        $id_save_quiz_result = $record->save();

        $content = '';

        /* ################### Test insert quizresultfinish */

        // Get ID examseries for check insert or update

        $examseries_data = DB::table('examseries_data')

            ->where('quiz_id', '=', $quiz->id)

            ->first();

        $examseries_id = $examseries_data->examseries_id;

        $examseries_table = DB::table('examseries')

            ->where('id', '=', $examseries_id)

            ->first();

        // Get quizresultfinish

        $quizresultfinish_data = DB::table('quizresultfinish')

            ->where('examseri_id', '=', $examseries_id)

            ->where('user_id', '=', Auth::user()->id)

            ->orderBy('id', 'desc')

            ->first();

        // Nếu chưa fish

        $data['finish'] = 0;


        $finish_current = 1;

        switch ($quiz->type) {

            case '2':

                $title_quiz = 'TỪ VỰNG';

                $finish_current = 1;

                break;

            case '3':

                $title_quiz = 'NGỮ PHÁP - ĐỌC HIỂU';

                $finish_current = 2;

                break;

            case '1':

                $title_quiz = 'NGHE HIỂU';

                $finish_current = 3;

                break;
        }

        /*Làm lại*/

        if ($finish_current == 1) {

            $record_resultfinish = new QuizResultfinish();

            $ip_info = ip_info('Visitor', "Location");

            if ($ip_info) {

                $record_resultfinish->country_code = $ip_info['country_code'];

                $record_resultfinish->country = $ip_info['country'];

                $record_resultfinish->city = $ip_info['city'];

                $record_resultfinish->state = $ip_info['state'];

                $record_resultfinish->ip = $ip_info['ip'];
            }



            $record_resultfinish->user_id = Auth::user()->id;

            $record_resultfinish->examseri_id = $examseries_id;

            $record_resultfinish->quiz_1 = $quiz->id;

            $record_resultfinish->quiz_1_mark = $record->marks_obtained;

            $record_resultfinish->quiz_1_analysis = $record->subject_analysis;

            $record_resultfinish->finish = 1;



            $check_exam_free = DB::table('exam_free')

                ->where('exam' . $category_id . '_1', '=', $examseries_id)

                ->where('start_date', '<', \Carbon\Carbon::now())->where('end_date', '>', \Carbon\Carbon::now())

                ->first();

            if ($check_exam_free) {

                $record_resultfinish->exam_free_id = $check_exam_free->id;
            }



            $useragent = $_SERVER['HTTP_USER_AGENT'];

            $iPad = stripos($useragent, "iPad");

            $iPhone = stripos($useragent, "iPhone");

            $Android = stripos($useragent, "Android");

            $iOS = stripos($useragent, "iOS");

            //-- You can add billion devices



            $DEVICE = ($iPad || $iPhone || $Android || $iOS);



            if ($DEVICE) {

                $record_resultfinish->is_device = 1;

                if ($iOS) {

                    $record_resultfinish->device = 'iOS';
                }

                if ($iPad) {

                    $record_resultfinish->device = 'iPad';
                }

                if ($iPhone) {

                    $record_resultfinish->device = 'iPhone';
                }

                if ($Android) {

                    $record_resultfinish->device = 'Android';
                }

            }



            $result_fisnish_id = $record_resultfinish->save();



            if ($result_fisnish_id) {

                $record->quizresultfinish_id = $record_resultfinish->id;

                $record->save();
            }

            $return_redirect = route('mypage.mock-exam.detail', $examseries_table->slug);

            flash('Bạn đã thi: ' . $title_quiz, '', 'success');

            return redirect($return_redirect);
        } elseif ($finish_current == 2) {

            $quiz_current_id = 'quiz_' . $finish_current;

            $quiz_current_mark = 'quiz_' . $finish_current . '_mark';

            $quiz_current_analysis = 'quiz_' . $finish_current . '_analysis';

            $record_resultfinish = QuizResultfinish::find($quizresultfinish_data->id);

            $record_resultfinish->$quiz_current_id = $quiz->id;

            $record_resultfinish->$quiz_current_mark = $record->marks_obtained;

            $record_resultfinish->$quiz_current_analysis = $record->subject_analysis;

            $record_resultfinish->finish = $finish_current;

            $result_fisnish_id = $record_resultfinish->save();

            //add result table after insert QuizResultfinish

            $record->quizresultfinish_id = $record_resultfinish->id;

            $record->save();

            $return_redirect = route('mypage.mock-exam.detail', $examseries_table->slug);

            flash('Bạn đã thi: ' . $title_quiz, '', 'success');

            return redirect($return_redirect);
        } elseif ($finish_current == 3) {

            $quiz_current_id = 'quiz_' . $finish_current;

            $quiz_current_mark = 'quiz_' . $finish_current . '_mark';

            $quiz_current_analysis = 'quiz_' . $finish_current . '_analysis';

            $record_resultfinish = QuizResultfinish::find($quizresultfinish_data->id);

            $record_resultfinish->$quiz_current_id = $quiz->id;

            $record_resultfinish->$quiz_current_mark = $record->marks_obtained;

            $record_resultfinish->$quiz_current_analysis = $record->subject_analysis;

            $record_resultfinish->finish = $finish_current;

            $result_fisnish_id = $record_resultfinish->save();

            //add result table after insert QuizResultfinish

            $record->quizresultfinish_id = $record_resultfinish->id;

            $record->save();

            $get_result = QuizResultfinish::where('id', '=', $quizresultfinish_data->id)->first();

            $result_quiz_1 = $get_result->quiz_1_mark;

            $result_quiz_2 = $get_result->quiz_2_mark;

            $result_quiz_3 = $get_result->quiz_3_mark;



            /*Sau khi tính hoàn thành 3 bài thi, Tính điểm theo từng Cấp N*/

            /*Tính điểm theo công thức N3*/

            $total_result = 0;



            if ($quiz->category_id == 1) {



                $result_quiz_1_analysis = json_decode($get_result->quiz_1_analysis);

                // Sum mondai 1~3 part 2

                $result_mondai_1_7 = 0;

                $i_result_quiz_2_analysis = 1;

                /*Tách mondai 1~3 quizz 2*/

                foreach ($result_quiz_1_analysis as $record_analysis) {

                    switch ($i_result_quiz_2_analysis) {

                        case '1':

                            $result_mondai_1_7 += $record_analysis->correct_answers;

                            break;

                        case '2':

                            $result_mondai_1_7 += $record_analysis->correct_answers;

                            break;

                        case '3':

                            $result_mondai_1_7 += $record_analysis->correct_answers;

                            break;

                        case '4':

                            $result_mondai_1_7 += $record_analysis->correct_answers * 2;

                            break;

                        case '5':

                            $result_mondai_1_7 += $record_analysis->correct_answers;

                            break;

                        case '6':

                            $result_mondai_1_7 += $record_analysis->correct_answers;

                            break;

                        case '7':

                            $result_mondai_1_7 += $record_analysis->correct_answers * 2;

                            break;
                    }

                    $i_result_quiz_2_analysis++;
                }

                $quiz_1_total = round($result_mondai_1_7 * (60 / 56));

                $quiz_2_total = round($result_quiz_1 - $result_mondai_1_7);

                $quiz_3_total = round($result_quiz_3 * (60 / 57));
            }



            /*Tính điểm theo công thức N3 (category = 2)*/

            if ($quiz->category_id == 2) {
                $result_quiz_1_analysis = json_decode($get_result->quiz_1_analysis);

                // Sum mondai 1~3 part 2

                $result_mondai_1_9 = 0;

                $i_result_quiz_2_analysis = 1;

                /*Tách mondai 1~3 quizz 2*/

                foreach ($result_quiz_1_analysis as $record_analysis) {

                    switch ($i_result_quiz_2_analysis) {

                        case '1':

                            $result_mondai_1_9 += $record_analysis->correct_answers;

                            break;

                        case '2':

                            $result_mondai_1_9 += $record_analysis->correct_answers;

                            break;

                        case '3':

                            $result_mondai_1_9 += $record_analysis->correct_answers;

                            break;

                        case '4':

                            $result_mondai_1_9 += $record_analysis->correct_answers;

                            break;

                        case '5':

                            $result_mondai_1_9 += $record_analysis->correct_answers;

                            break;

                        case '6':

                            $result_mondai_1_9 += $record_analysis->correct_answers * 2;

                            break;

                        case '7':

                            $result_mondai_1_9 += $record_analysis->correct_answers;

                            break;

                        case '8':

                            $result_mondai_1_9 += $record_analysis->correct_answers;

                            break;

                        case '9':

                            $result_mondai_1_9 += $record_analysis->correct_answers;

                            break;
                    }

                    $i_result_quiz_2_analysis++;
                }

                $quiz_1_total = round($result_mondai_1_9 * (60 / 59));

                $quiz_2_total = round(($result_quiz_1 - $result_mondai_1_9) * (60 / 54));

                $quiz_3_total = round($result_quiz_3 * (60 / 56));
            }



            /*Tính điểm theo công thức N3 (category = 3)*/

            if ($quiz->category_id == 3) {



                $result_quiz_2_analysis = json_decode($get_result->quiz_2_analysis);

                // Sum mondai 1~3 part 2

                $result_mondai_1_3 = 0;

                $i_result_quiz_2_analysis = 1;

                /*Tách mondai 1~3 quizz 2*/

                foreach ($result_quiz_2_analysis as $record_analysis) {

                    if ($i_result_quiz_2_analysis <= 3) {
                        $result_mondai_1_3 += $record_analysis->correct_answers;
                    }

                    $i_result_quiz_2_analysis++;
                }

                $quiz_1_total = round(($result_quiz_1 + $result_mondai_1_3) * (60 / 58));

                $quiz_2_total = round($result_quiz_2 - $result_mondai_1_3);

                $quiz_3_total = round($result_quiz_3 * (60 / 62));
            }

            /*Tính điểm theo công thức N4 (category = 4)*/

            if ($quiz->category_id == 4) {

                $result_quiz_2_analysis = json_decode($get_result->quiz_2_analysis);

                // Sum mondai 1~3 part 2

                $result_mondai_1_3 = 0;

                $i_result_quiz_2_analysis = 1;

                /*Tách mondai 1~3 quizz 2*/

                foreach ($result_quiz_2_analysis as $record_analysis) {

                    if ($i_result_quiz_2_analysis <= 3) {
                        $result_mondai_1_3 += $record_analysis->correct_answers;
                    }

                    $i_result_quiz_2_analysis++;
                }

                $quiz_1_total = round(($result_quiz_1 + $result_quiz_2) * (120 / 99));

                $quiz_2_total = 0;

                $quiz_3_total = round($result_quiz_3 * (60 / 63));
            }

            /*Tính điểm theo công thức N5 (category = 5)*/

            if ($quiz->category_id == 5) {

                $result_quiz_2_analysis = json_decode($get_result->quiz_2_analysis);

                // Sum mondai 1~3 part 2

                $result_mondai_1_3 = 0;

                $i_result_quiz_2_analysis = 1;

                /*Tách mondai 1~3 quizz 2*/

                foreach ($result_quiz_2_analysis as $record_analysis) {

                    if ($i_result_quiz_2_analysis <= 3) {
                        $result_mondai_1_3 += $record_analysis->correct_answers;
                    }

                    $i_result_quiz_2_analysis++;
                }

                $quiz_1_total = round(($result_quiz_1 + $result_quiz_2) * (120 / 83));

                $quiz_2_total = 0;

                $quiz_3_total = round($result_quiz_3 * (60 / 55));
            }

            $total_result = $quiz_1_total + $quiz_2_total + $quiz_3_total;

            $record_resultfinish = QuizResultfinish::find($quizresultfinish_data->id);

            //Save marks

            $record_resultfinish->quiz_1_total = $quiz_1_total;

            $record_resultfinish->quiz_2_total = $quiz_2_total;

            $record_resultfinish->quiz_3_total = $quiz_3_total;

            $record_resultfinish->total_marks = $total_result;



            $status_thi = 0;
            $check_pass = new ExamScore();
            $record_resultfinish->status = $check_pass->checkPassingExam(
                $quiz->category_id,
                $quiz_1_total,
                $quiz_2_total,
                $quiz_3_total
            );

            $record_resultfinish->save();

            $data['finish'] = 1;

            // Sau khi thi xong, tư động gửi kết quả và chứng nhận (pdf) về email của học viên
            $ketqua = $record_resultfinish->status == 1 ? 'ĐẠT' : 'CHƯA ĐẠT';
            $ketquaEng = $record_resultfinish->status == 1 ? 'PASSED' : 'FAILED';
            $ketquaJap = $record_resultfinish->status == 1 ? '合格' : '不合格';
            $data['name'] = $user_record->name;
            $data['date_of_birth'] = '';
            $date = new \DateTime();
            $data['start_date_en'] = date_format($date, 'F d\, Y');
            $data['start_date_ja'] = date_format($date, 'Y\年m\月d\日');
            $data['status_en'] = $ketquaEng;
            $data['status_ja'] = $ketquaJap;
            $data['level'] = $quiz->category_id;
            $data['address'] = $user_record->address;
            $data['quiz1'] = $record_resultfinish->quiz_1_total;
            $data['quiz2'] = $record_resultfinish->quiz_2_total;
            $data['quiz3'] = $record_resultfinish->quiz_3_total;
            $data['total'] = $record_resultfinish->total_marks;
            $layoutPDF = $quiz->category_id <= 3
                ? 'client.mock-exam.pdf.finish-results-exam'
                : 'client.mock-exam.pdf.finish-results-exam2';
            $renderedView = view($layoutPDF, $data)->render();
            $htmldata = Blade::compileString($renderedView);

            // PDF
            try {
                // Tạo thư mục nếu chưa tồn tại
                $uploadPath = public_path('uploads/certificate');
                if (!File::exists($uploadPath)) {
                    File::makeDirectory($uploadPath,
                        0755,
                        true
                    );
                }

                $filename = 'CER_' . $user_record->username . '_' . time() . '.pdf';
                $path = $uploadPath . '/' . $filename;

                $html2pdf = new Html2Pdf('P', 'A4', 'ja', true, 'UTF-8', array(5, 5, 5, 8));
                $html2pdf->setDefaultFont('cid0jp');
                $html2pdf->writeHTML($htmldata, false);

                // Kiểm tra quyền ghi file
                if (!is_writable($uploadPath)) {
                    throw new Exception("Directory is not writable");
                }

                $html2pdf->output($path, 'F');
            } catch (Html2PdfException $e) {
                $html2pdf->clean();
                $log = new Logger(env('MAIL_LOG_PATH'));
                $log->putLog('An error occurred (pdf): ' . $e->getMessage());
            } catch (Exception $e) {
                $log = new Logger(env('MAIL_LOG_PATH'));
                $log->putLog('An error occurred: ' . $e->getMessage());
            }

            // EMAIL TEMPATE
            $mail_template = $quiz->category_id <= 3 ? 'thongbaoketquathi' : 'thongbaoketquathi';
            try {
                sendEmail($mail_template, array(
                    'name' => $user_record->name,
                    'title' => $examseries_title,
                    'start_date' => date("d-m-Y"),
                    'quiz1' => $record_resultfinish->quiz_1_total,
                    'quiz2' => $record_resultfinish->quiz_2_total,
                    'quiz3' => $record_resultfinish->quiz_3_total,
                    'total' => $record_resultfinish->total_marks,
                    'status' => $ketqua,
                    'attachment' => $path,
                    'to_email' => Auth::user()->email
                ));
            } catch (\Exception $e) {
                $log = new Logger(env('MAIL_LOG_PATH'));
                $log->putLog('An error occurred (mail): ' . $e->getMessage());
            }

            $return_redirect = route('mock-exam.finish-exam-result', $record_resultfinish->id);

            flash('Bạn đã thi: ' . $title_quiz, '', 'success');

            return redirect($return_redirect);
        }

        $topperStatus = false;

        $data['isUserTopper'] = $topperStatus;

        $data['rank_details'] = FALSE;

        $data['quiz'] = $quiz;

        $data['active_class'] = 'exams';

        $data['record_resultfinish'] = $record_resultfinish ?? null;

        $data['examseries_category'] = $examseries_table->category_id;

        $data['examseries'] = $examseries_table;

        $data['title'] = change_furigana_title($quiz->title);

        $data['record'] = $record;

        $data['user'] = $user_record;

        $data['toppers'] = [];

        $data['block_navigation'] = TRUE;

        $data['examseries_slug'] = $examseries_table->slug;

        return view('client.mock-exam.results', $data);
    }

    /**
     * After the exam completes, the data will be submitted to this method
     *
     * @param  string $result_id
     * @return \Illuminate\Http\Response
     */
    public function finishExamResult(string $result_id)
    {
        $user_id = Auth::user()->id;

        // Fetch quiz result and exam series in a single query with join
        $record_resultfinish = DB::table('quizresultfinish')
            ->where('id', $result_id)
            ->where('user_id', $user_id)
            ->first();

        if (!$record_resultfinish) {
            // Handle case where record is not found
            return redirect()->back()->with('error', 'Result not found');
        }

        $examseries_table = DB::table('examseries')
            ->where('id', $record_resultfinish->examseri_id)
            ->first();

        if (!$examseries_table) {
            // Handle case where exam series is not found
            return redirect()->back()->with('error', 'Exam series not found');
        }

        // Prepare data for the view
        $data = [
            'quiz' => [],
            'finish' => 1,
            'active_class' => 'exams',
            'record_resultfinish' => $record_resultfinish,
            'examseries_title' => $examseries_table->title,
            'examseries' => $examseries_table,
            'examseries_category' => $examseries_table->category_id,
            'title' => 'Kết quả thi online',
            'record' => '',
            'user' => '',
            'toppers' => [],
            'block_navigation' => true,
            'examseries_slug' => $examseries_table->slug
        ];
        return view('client.mock-exam.results-exam', $data);
    }

    /**

     * Listing method

     * @return Illuminate\Database\Eloquent\Collection

     */

    public function ajaxRate(Request $request) {
        $inser_rate = DB::table('examseries_rate')->insert(

            ['dethi' => $request->dethi, 'giaodien' => $request->giaodien, 'thaotac' => $request->thaotac, 'amthanh' => $request->amthanh, 'tocdo' => $request->tocdo, 'user_id' => Auth::user()->id, 'gopy' => $request->gopy, 'examseries_id' => $request->exam, 'quizresultfinish_id' => $request->examfinish]

        );

        if ($inser_rate) {

            $res = array('code' => 200, 'status' => true, 'message' => 'Thêm đánh giá thành công');
        } else {

            $res = array('code' => 400, 'status' => false, 'message' => 'Có lỗi khi thêm đánh giá');
        }

        return json_encode($res);
    }

    /**

     * Returns the specific question record based on question_id

     * @param  [type] $question_id [description]

     * @return [type]              [description]

     */

    function getQuestionRecord($question_id)

    {

        return QuestionBank::where('id', '=', $question_id)->first();
    }

    /**

     * This below method process the submitted answers based on the

     * provided answers and quiz questions

     * @param  [type] $answers [description]

     * @return [type]          [description]

     */

    public function processAnswers($answers, $subject, $time_spent, $negative_mark = 0) {

        $obtained_marks     = 0;

        $correct_answers    = 0;

        $obtained_negative_marks = 0;

        $corrent_answer_question            = [];

        $wrong_answer_question              = [];

        $time_spent_correct_answer_question = [];

        $time_spent_wrong_answer_question   = [];

        foreach ($answers as $key => $value) {

            if (is_numeric($key)) {

                $question_record  = $this->getQuestionRecord($key);

                $question_type    = $question_record->question_type;

                $actual_answer    = $question_record->correct_answers;

                $subject_id       = $question_record->subject_id;

                if (! array_key_exists($subject_id, $subject)) {

                    $subject[$subject_id]['subject_id']       = $subject_id;

                    $subject[$subject_id]['correct_answers']  = 0;

                    $subject[$subject_id]['wrong_answers']    = 0;

                    $subject[$subject_id]['time_spent_correct_answers']    = 0;

                    $subject[$subject_id]['time_spent_wrong_answers']    = 0;

                    $subject[$subject_id]['time_spent']       = 0;
                }

                $subject[$subject_id]['time_spent']       += $time_spent[$question_record->id];

                $subject[$subject_id]['time_to_spend']    += $question_record->time_to_spend;

                switch ($question_type) {

                    case 'radio':

                        if ($value[0] == $actual_answer) {

                            $correct_answers++;

                            $obtained_marks                 += $question_record->marks;

                            $corrent_answer_question[]       = $question_record->id;

                            $subject[$subject_id]['correct_answers'] += 1;

                            $subject[$subject_id]['time_spent_correct_answers'] += $time_spent[$question_record->id];

                            $time_spent_correct_answer_question[$question_record->id]['time_to_spend']

                            = $question_record->time_to_spend;

                            $time_spent_correct_answer_question[$question_record->id]['time_spent']

                            = $time_spent[$question_record->id];
                        } else {

                            $wrong_answer_question[]          = $question_record->id;

                            $subject[$subject_id]['wrong_answers'] += 1;

                            $obtained_marks                   -= $negative_mark;

                            $obtained_negative_marks          += $negative_mark;

                            $subject[$subject_id]['time_spent_wrong_answers']

                            += $time_spent[$question_record->id];

                            $time_spent_wrong_answer_question[$question_record->id]['time_to_spend']

                            = $question_record->time_to_spend;

                            $time_spent_wrong_answer_question[$question_record->id]['time_spent']

                            = $time_spent[$question_record->id];
                        }

                        break;

                    case 'checkbox':

                        $actual_answer = json_decode($actual_answer);

                        $i = 0;

                        $flag = 1;

                        foreach ($value as $answer_key => $answer_value) {

                            if (isset($actual_answer[$answer_key])) {

                                if (
                                    $actual_answer[$answer_key]->answer !=

                                    $answer_value
                                ) {

                                    $flag = 0;
                                    break;
                                }
                            } else {

                                $flag = 0;
                                break;
                            }
                        }

                        if ($flag) {

                            $correct_answers++;

                            $obtained_marks += $question_record->marks;

                            $corrent_answer_question[] = $question_record->id;

                            $subject[$subject_id]['correct_answers'] += 1;

                            $subject[$subject_id]['time_spent_correct_answers']

                            += $time_spent[$question_record->id];

                            $time_spent_correct_answer_question[$question_record->id]['time_to_spend']

                            = $question_record->time_to_spend;

                            $time_spent_correct_answer_question[$question_record->id]['time_spent']

                            = $time_spent[$question_record->id];
                        } else {

                            $wrong_answer_question[]          = $question_record->id;

                            $subject[$subject_id]['wrong_answers'] += 1;

                            $subject[$subject_id]['time_spent_wrong_answers']

                            += $time_spent[$question_record->id];

                            $obtained_marks                   -= $negative_mark;

                            $obtained_negative_marks          += $negative_mark;

                            $time_spent_wrong_answer_question[$question_record->id]['time_to_spend']

                            = $question_record->time_to_spend;

                            $time_spent_wrong_answer_question[$question_record->id]['time_spent']

                            = $time_spent[$question_record->id];
                        }

                        break;

                    case 'blanks':

                        $actual_answer = json_decode($actual_answer);

                        $i = 0;

                        $flag = 1;

                        foreach ($actual_answer as $answer) {

                            if (strcasecmp(

                                trim($answer->answer),

                                trim($value[$i++])
                            ) != 0) {

                                $flag = 0;
                                break;
                            }
                        }

                        if ($flag) {

                            $correct_answers++;

                            $obtained_marks += $question_record->marks;

                            $corrent_answer_question[] = $question_record->id;

                            $subject[$subject_id]['correct_answers'] += 1;

                            $subject[$subject_id]['time_spent_correct_answers']

                            += $time_spent[$question_record->id];

                            $time_spent_correct_answer_question[$question_record->id]['time_to_spend']

                            = $question_record->time_to_spend;

                            $time_spent_correct_answer_question[$question_record->id]['time_spent']

                            = $time_spent[$question_record->id];
                        } else {

                            $wrong_answer_question[] = $question_record->id;

                            $subject[$subject_id]['wrong_answers'] += 1;

                            $subject[$subject_id]['time_spent_wrong_answers']

                            += $time_spent[$question_record->id];

                            $obtained_marks                   -= $negative_mark;

                            $obtained_negative_marks          += $negative_mark;

                            $time_spent_wrong_answer_question[$question_record->id]['time_to_spend']

                            = $question_record->time_to_spend;

                            $time_spent_wrong_answer_question[$question_record->id]['time_spent']

                            = $time_spent[$question_record->id];
                        }

                        break;

                    case ($question_type == 'para'  ||

                    $question_type == 'audio' ||

                    $question_type == 'video'

                    ):

                        $actual_answer = json_decode($actual_answer);

                        $indidual_marks = $question_record->marks / $question_record->total_correct_answers;

                        $i = 0;

                        $flag = 0;

                        foreach ($value as $answer_key => $answer_value) {

                            if ($actual_answer[$answer_key]->answer == $answer_value) {

                                $flag = 1;

                                $obtained_marks += $indidual_marks;
                            }
                        }

                        if ($flag) {

                            $correct_answers++;

                            $corrent_answer_question[] = $question_record->id;

                            $subject[$subject_id]['correct_answers'] += 1;

                            $subject[$subject_id]['time_spent_correct_answers']

                            += $time_spent[$question_record->id];

                            $time_spent_correct_answer_question[$question_record->id]['time_to_spend']

                            = $question_record->time_to_spend;

                            $time_spent_correct_answer_question[$question_record->id]['time_spent']

                            = $time_spent[$question_record->id];
                        } else {

                            $wrong_answer_question[] = $question_record->id;

                            $subject[$subject_id]['wrong_answers'] += 1;

                            $subject[$subject_id]['time_spent_wrong_answers']

                            += $time_spent[$question_record->id];

                            $obtained_marks                   -= $negative_mark;

                            $obtained_negative_marks          += $negative_mark;

                            $time_spent_wrong_answer_question[$question_record->id]['time_to_spend']

                            = $question_record->time_to_spend;

                            $time_spent_wrong_answer_question[$question_record->id]['time_spent']

                            = $time_spent[$question_record->id];
                        }

                        break;

                    case 'match':

                        $actual_answer = json_decode($actual_answer);

                        $indidual_marks = $question_record->marks / $question_record->total_correct_answers;

                        $i = 0;

                        $flag = 0;

                        foreach ($actual_answer as $answer) {

                            if ($answer->answer == $value[$i++]) {

                                $flag = 1;

                                $obtained_marks += $indidual_marks;
                            }
                        }

                        if ($flag) {

                            $correct_answers++;

                            $corrent_answer_question[] = $question_record->id;

                            $subject[$subject_id]['correct_answers'] += 1;

                            $subject[$subject_id]['time_spent_correct_answers']

                            += $time_spent[$question_record->id];

                            $time_spent_correct_answer_question[$question_record->id]['time_to_spend']

                            = $question_record->time_to_spend;

                            $time_spent_correct_answer_question[$question_record->id]['time_spent']

                            = $time_spent[$question_record->id];
                        } else {

                            $wrong_answer_question[] = $question_record->id;

                            $subject[$subject_id]['wrong_answers'] += 1;

                            $subject[$subject_id]['time_spent_wrong_answers']

                            += $time_spent[$question_record->id];

                            $obtained_marks                   -= $negative_mark;

                            $obtained_negative_marks          += $negative_mark;

                            $time_spent_wrong_answer_question[$question_record->id]['time_to_spend']

                            = $question_record->time_to_spend;

                            $time_spent_wrong_answer_question[$question_record->id]['time_spent']

                            = $time_spent[$question_record->id];
                        }

                        break;
                }
            }
        }


        return array(

            'total_correct_answers' => $correct_answers,

            'marks_obtained'        => $obtained_marks,

            'negative_marks'        => $obtained_negative_marks,

            'subject_analysis'      => json_encode($subject),

            'correct_answer_questions' => json_encode($corrent_answer_question),

            'wrong_answer_questions' => json_encode($wrong_answer_question),

            'time_spent_correct_answer_questions' => json_encode($time_spent_correct_answer_question),

            'time_spent_wrong_answer_questions' => json_encode($time_spent_wrong_answer_question),

        );
    }

}