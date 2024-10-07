<?php


namespace App\Http\Controllers;

use \App;

use Illuminate\Http\Request;

use Illuminate\Support\Collection;

use App\Http\Requests;

use App\Quiz;

use App\Subject;

use App\QuestionBank;

use App\QuestionBankTable;

use App\QuizCategory;

use App\QuizResult;

use App\QuizResultfinish;

use App\QuizQuestions;

use App\EmailTemplate;

use App\ExamSeriesfree;

use Yajra\DataTables\DataTables;

use App\EmailSettings;

use DB;

use Auth;

use App\User;

use Input;

use Exception;

use Dompdf\Options;

use App\Logger;

use App\ExamScore;

// require 'vendor/html2pdf/autoload.php';

use Spipu\Html2Pdf\Html2Pdf;

use Spipu\Html2Pdf\Exception\Html2PdfException;

use Spipu\Html2Pdf\Exception\ExceptionFormatter;

class StudentQuizController extends Controller

{

  public function __construct()

  {

    $this->middleware('auth');

  }

     /**

     * Listing method

     * @return Illuminate\Database\Eloquent\Collection

     */

     public function ajax_rate(Request $request) {

         $data = $request->all();

         $inser_rate = DB::table('examseries_rate')->insert(

            ['dethi'=>$request->dethi, 'giaodien'=>$request->giaodien,'thaotac'=>$request->thaotac, 'amthanh'=> $request->amthanh, 'tocdo'=> $request->tocdo, 'user_id'=> Auth::user()->id, 'gopy'=>$request->gopy, 'examseries_id'=>$request->exam, 'quizresultfinish_id'=>$request->examfinish]

        );





        if ($inser_rate) {

          $res = array('code'=>200, 'status'=>true, 'message'=>'Thêm đánh giá thành công');

        } else {

          $res = array('code'=>400, 'status'=>false, 'message'=>'Có lỗi khi thêm đánh giá');

        }



        return json_encode($res);

     }

     public function test_result()

     {

        $data['active_class']       = 'exams';

        $data['title']              = 'Test kết quả'; //getPhrase('quiz_categories');

        $data['finish'] = 1;

        $data['examseries_category'] = 3;

        $data['record_resultfinish'] = 3;

        $data['layout']              = getLayout();

        $user = Auth::user();

      // return view('student.exams.categories', $data);

        $view_name = 'admin.student.exams.results-test';

        return view($view_name, $data);

      }

     public function index()

     {

      if(checkRole(getUserGrade(2)))

      {

        return back();

      }

      $data['active_class']       = 'exams';

        $data['title']              = 'Luyện thi'; //getPhrase('quiz_categories');

        $data['categories']         = [];

        $user = Auth::user();

        $interested_categories      = null;

        if($user->settings)

        {

          $interested_categories =  json_decode($user->settings)->user_preferences;

        }

        if($interested_categories) {

          if(count($interested_categories->quiz_categories))

            $data['categories']         = QuizCategory::

          whereIn('id',(array) $interested_categories->quiz_categories)

          ->paginate(getRecordsPerPage());

        }

        $data['layout']              = getLayout();

        $user = Auth::user();

      // return view('student.exams.categories', $data);

        $view_name = 'admin.student.exams.categories';

        return view($view_name, $data);

      }

      public function ajax_log(Request $request) {

              $data = $request->all();

              $username = $request->username;

              $stt_question = $request->stt_question;

              $link_audio = $request->link_audio;

              $res=array('status' =>true,'errors' =>array(),'readfileStatus' =>true,'aborted' =>false);

                if ($res['readfileStatus'] == false) {

                  $res['errors'][] = 'readfile failed';

                  $res['status'] = false;

                } else {

                  $res['errors'][] = 'readfile ok';

                  $res['status'] = true;

                }

                if (connection_aborted()) {

                  $res['errors'][] = 'Hủy kết nối';

                  $res['aborted'] = true;

                  $res['status'] = false;

                }

                $ok = false;

                /*$fh = fopen('/domains/elearning.hikariacademy.edu.vn/public_html/vn/log/'.$username.'-question'.$stt_question.'-time'. date('Ymd_His'), 'w');

                if ($fh) {

                    $ok = true;

                    if (!fwrite($fh, var_export($res, true))) {

                      $ok = false;

                    }

                    if (!fclose($fh)) {

                      $ok = false;

                    }

                }*/

              return true;

      }

    /**

     * List the categories available

     * @param  [type] $slug [description]

     * @return [type]       [description]

     */

    public function exams($slug='')

    {

      $category = FALSE;

      //if($slug)

      //$category = QuizCategory::getRecordWithSlug($slug);

      $user = Auth::user();

      $interested_categories      = null;

      if ($slug)

      {

        if ($slug!='all')

        {

          $category = QuizCategory::getRecordWithSlug($slug);

              //check student quiz category

          if ($category)

          {

            if ($user->settings)

            {

              $interested_categories =  json_decode($user->settings)->user_preferences;

            }

          }

        }

        else

        {

          $user = Auth::user();

          $role = getRole($user->id);

          if ($role === 'student')

          {

            if ($user->settings)

            {

              $interested_categories =  json_decode($user->settings)->user_preferences;

            }

          }

          else if($role === 'parent')

          {

            $child = User::select(['settings'])->where('parent_id', '=', $user->id)->first();

            if ($child)

            {

              if ($child->settings)

              {

                $interested_categories =  json_decode($child->settings)->user_preferences;

              }

            }

          }

          else

            redirect(URL_STUDENT_EXAM_CATEGORIES);

        }

        if ($interested_categories)

        {

          if (count($interested_categories->quiz_categories))

          {

            if($category){

              if (!in_array($category->id, $interested_categories->quiz_categories))

                return redirect(URL_STUDENT_EXAM_CATEGORIES);

            }

          }

        }

        else

          return redirect(URL_STUDENT_EXAM_CATEGORIES);

      }

      $data['category']         = $category;

      $data['active_class']     = 'exams';

      $data['title']            = 'Tất cả bài thi';//getphrase('all_exams');

      if($category)

        $data['title']            = $category->category;

      $data['layout']           = getLayout();

        // return view('student.exams.list', $data);

      $view_name = 'admin.student.exams.list';

      return view($view_name, $data);

    }

    /**

     * Displays the instructions before start of the exam

     * @param  [type] $slug [description]

     * @return [type]       [description]

     */

    public function instructions($slug)

    {

      $instruction_page = '';

      $record = Quiz::getRecordWithSlug($slug);

      // if($isValid = $this->isValidRecord($record))

      //   return redirect($isValid);

      if($record->instructions_page_id)

        $instruction_page = App\Instruction::where('id',$record->instructions_page_id)->first();

      $data['instruction_data'] = '';

      if($instruction_page){

        $data['instruction_data'] = $instruction_page->content;

        $data['instruction_title'] = $instruction_page->title;

      }

      $data['record']           = $record;

      $data['active_class']     = 'exams';

      $data['layout']           = getLayout();

      $data['title']          = 'Hướng dấn'; //$record->title;

      $data['block_navigation']          = TRUE;

      $view_name = 'admin.student.exams.instructions';

      return view($view_name, $data);

    }

    /**

     * This method manages session based on provided key [exam_started, exam_completed, check]

     * @param  [type] $key [description]

     * @return [type]      [description]

     */

    public function examSession($key)

    {

      switch ($key) {

        case 'exam_started':

        session()->put($key, '1');

        break;

        case 'exam_completed':

        session()->forget('exam_started');

        break;

        case 'check':

        if(session()->get('exam_started') == null) {

          return TRUE;

        }

        return FALSE; break;

      }

      return;

    }

    /**

     * The Exam will start from this method

     * @param  [type] $slug [description]

     * @return [type]       [description]

     */



    public function startExamAnswers($slug, $result_slug)

    {

      $result_record = QuizResult::getRecordWithSlug($result_slug);



      //dd($result_record);

      $quiz                = Quiz::getRecordWithSlug($slug);

      $examseries_data = DB::table('examseries_data')

      ->where('quiz_id', '=', $quiz->id)

      ->first();

      $examseries_id = $examseries_data->examseries_id;

      $examseries_table = DB::table('examseries')

      ->where('id', '=', $examseries_id)

      ->first();

      $user                = Auth::user();

      $current_state       = null;

      $any_resume_exam     = FALSE;

      $time                = $this->convertToHoursMins($quiz->dueration);

      $atime               = $this->convertToHoursMins($quiz->dueration);

      $current_question_id = null;

      $prepared_records = null;

       //check if it is ST exam or another

      if(!$any_resume_exam){

          $prepared_records   = (object) $quiz->prepareQuestions($quiz->getQuestions());

      }

      if($current_state) {

         $temp = [];

         foreach($current_state as $key => $val)

         {

          $temp[(int) $key] = $val;

        }

        $current_state = $temp;

      }



      $data['time_hours']         = makeNumber($time['hours'],2,'0','left');

      $data['time_minutes']       = $quiz->dueration;

      $data['time_seconds']       = makeNumber($time['seconds'],2,'0','left');

      $data['atime_hours']        = makeNumber($atime['hours'],2,'0','left');

      $data['atime_minutes']      = $quiz->dueration;

      $data['atime_seconds']      = makeNumber($atime['seconds'],2,'0','left');

      $data['quiz']               = $quiz;

      $data['result_record']      = $result_record;

      $data['user']               = $user;

      $data['active_class']       = 'exams';

      $data['title']              = change_furigana_title($quiz->title);

      $data['right_bar']          = TRUE;

      $data['block_navigation']   = TRUE;

      $data['examseries_slug']    = $examseries_table->slug;



      $data['current_state']       = $current_state;

      $data['current_question_id'] = $current_question_id;

      $final_questions             = $prepared_records->questions;

      $final_subjects              = $prepared_records->subjects;

      $data['questions']           = $final_questions;

      $data['subjects']            = $final_subjects;

      $bookmarks                   = array_pluck($final_questions, 'id');

      $data['bookmarks']           = $bookmarks;

      $data['right_bar_path']      = 'student.exams.answers.exam-right-bar';

      $data['right_bar_data']      = array(

        'questions'      => $final_questions,

        'current_state'  => $current_state,

        'quiz'           => $quiz,

        'time_hours'     => $data['time_hours'],

        'time_minutes'   => $data['time_minutes'],

        'atime_hours'    => $data['atime_hours'],

        'atime_minutes'  => $data['atime_minutes']

      );

      $view_name = 'admin.student.exams.answers.exam-form';

      return view($view_name, $data);

      }



    public function startExam($slug)

    {

      $quiz                = Quiz::getRecordWithSlug($slug);

      $examseries_data = DB::table('examseries_data')

      ->where('quiz_id', '=', $quiz->id)

      ->first();

      $examseries_id = $examseries_data->examseries_id;

      $examseries_table = DB::table('examseries')

      ->where('id', '=', $examseries_id)

      ->first();

      $user                = Auth::user();

      $current_state       = null;

      $any_resume_exam     = FALSE;

      $time                = $this->convertToHoursMins($quiz->dueration);

      $atime               = $this->convertToHoursMins($quiz->dueration);

      $current_question_id = null;

      $prepared_records = null;

       //check if it is ST exam or another

      if(!$any_resume_exam){

          $prepared_records   = (object) $quiz->prepareQuestions($quiz->getQuestions());

      }

      if($current_state) {

         $temp = [];

         foreach($current_state as $key => $val)

         {

          $temp[(int) $key] = $val;

        }

        $current_state = $temp;

      }



      $data['time_hours']         = makeNumber($time['hours'],2,'0','left');

      $data['time_minutes']       = $quiz->dueration;

      $data['time_seconds']       = makeNumber($time['seconds'],2,'0','left');

      $data['atime_hours']        = makeNumber($atime['hours'],2,'0','left');

      $data['atime_minutes']      = $quiz->dueration;

      $data['atime_seconds']      = makeNumber($atime['seconds'],2,'0','left');

      $data['quiz']               = $quiz;

      $data['user']               = $user;

      $data['active_class']       = 'exams';

      $data['title']              = change_furigana_title($quiz->title);

      $data['right_bar']          = TRUE;

      $data['block_navigation']   = TRUE;

      $data['examseries_slug']    = $examseries_table->slug;



       $data['current_state']       = $current_state;

       $data['current_question_id'] = $current_question_id;

       $final_questions             = $prepared_records->questions;

       $final_subjects              = $prepared_records->subjects;

          $data['questions']           = $final_questions;

          $data['subjects']            = $final_subjects;

          $bookmarks                   = array_pluck($final_questions, 'id');

          $data['bookmarks']           = $bookmarks;

          $data['right_bar_path']      = 'student.exams.exam-right-bar';

          $data['right_bar_data']      = array(

            'questions'      => $final_questions,

            'current_state'  => $current_state,

            'quiz'           => $quiz,

            'time_hours'     => $data['time_hours'],

            'time_minutes'   => $data['time_minutes'],

            'atime_hours'    => $data['atime_hours'],

            'atime_minutes'  => $data['atime_minutes']

          );

          $view_name = 'admin.student.exams.exam-form';

          return view($view_name, $data);

      }







    /**

     * Convert minutes to Hours and minutes

     */

    function convertToHoursMins($time, $format = '%02d:%02d')

    {

      if ($time < 1) {

        return;

      }

      $hours = floor($time / 60);

      $minutes = ($time % 60);

      $result['hours'] = $hours;

      $result['minutes'] = $minutes;

      $result['seconds'] = 0;

      return $result;

    }



    /**

     * After the exam complets the data will be submitted to this method

     * @param  Request $request [description]

     * @param  [type]  $slug    [description]

     * @return [type]           [description]

     */

    public function finishExamResult($slug)

    {





      $record_resultfinish = DB::table('quizresultfinish')

      ->where('id', '=', $slug)

      ->where('user_id','=',Auth::user()->id)

      ->first();



      $examseries_table = DB::table('examseries')

      ->where('id', '=', $record_resultfinish->examseri_id)

      ->first();



      $data['quiz']               = array();

       $data['finish']               = 1;

      $data['active_class']       = 'exams';

      $data['record_resultfinish']       = $record_resultfinish;

      // $data['examseries_category']       = $examseries_table->category_id;

      $data['examseries_title']   = $examseries_table->title;

      $data['examseries']         = $examseries_table;

      $data['examseries_category']         = $examseries_table->category_id;





      $data['title']              = 'Kết quả thi online';

      $data['record']             = '';

      $data['user']               = '';



      $toppers              = array();

      $data['toppers']      = array();

      $data['block_navigation']          = TRUE;

      $data['examseries_slug'] = $examseries_table->slug;



      $view_name = 'admin.student.exams.results-exam';

      return view($view_name, $data);



    }







    /**

     * After the exam complets the data will be submitted to this method

     * @param  Request $request [description]

     * @param  [type]  $slug    [description]

     * @return [type]           [description]

     */

    public function finishExam(Request $request, $slug)

    {

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

        // if($isValid = $this->isValidRecord($quiz))

        //     return redirect($isValid);

      $input_data = Input::all();

      $answers = array();

      $time_spent = $request->time_spent;

        //Remove _token key from answers data prepare the list of answers at one place

      foreach ($input_data as $key => $value) {

        if($key=='_token' || $key=='time_spent')

          continue;

        $answers[$key] = $value;

      }

        //Get the list of questions and prepare the list at one place

        //This is to find the unanswered questions

        //List the unanswered questions list at one place

      $questions = DB::table('questionbank_quizzes')->select('questionbank_id', 'subject_id')

      ->where('quize_id','=',$quiz->id)

      ->get();

      $subject                  = [];

      $time_spent_not_answered  = [];

      $not_answered_questions   = [];

      foreach($questions as $q)

      {

        $subject_id = $q->subject_id;

        if(! array_key_exists($q->subject_id, $subject)) {

          $subject[$subject_id]['subject_id']       = $subject_id;

          $subject[$subject_id]['correct_answers']  = 0;

          $subject[$subject_id]['wrong_answers']    = 0;

          $subject[$subject_id]['not_answered']     = 0;

          $subject[$subject_id]['time_spent']       = 0;

          $subject[$subject_id]['time_to_spend']    = 0;

          $subject[$subject_id]['time_spent_correct_answers']    = 0;

          $subject[$subject_id]['time_spent_wrong_answers']    = 0;

        }

        if(! array_key_exists($q->questionbank_id, $answers)){

          $subject[$subject_id]['not_answered']     += 1;

          $not_answered_questions[] = $q->questionbank_id;

          $time_spent_not_answered[$q->questionbank_id]['time_to_spend'] = 0;

          $time_spent_not_answered[$q->questionbank_id]['time_spent'] = $time_spent[$q->questionbank_id];

          $subject[$subject_id]['time_spent']      += $time_spent[$q->questionbank_id];

        }

      }

      $result =   $this->processAnswers($answers, $subject, $time_spent, $quiz->negative_mark);

      $result['not_answered_questions'] = json_encode($not_answered_questions);

        //$result['time_spent_not_answered_questions'] = json_encode($time_spent_not_answered);

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

             if($ip_info) {

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

                                    ->where('exam'.$category_id.'_1', '=', $examseries_id)

                                    ->where('start_date', '<' , \Carbon\Carbon::now())

                                    ->where('end_date', '>' , \Carbon\Carbon::now())

                                    ->first();

             if($check_exam_free) {

               $record_resultfinish->exam_free_id = $check_exam_free->id;

             }



             $useragent = $_SERVER['HTTP_USER_AGENT'];

              //$iPod = stripos($useragent, "iPod");

              $iPad = stripos($useragent, "iPad");

              $iPhone = stripos($useragent, "iPhone");

              $Android = stripos($useragent, "Android");

              $iOS = stripos($useragent, "iOS");

              //-- You can add billion devices



              $DEVICE = ($iPad||$iPhone||$Android||$iOS);



              if ($DEVICE) {

                $record_resultfinish->is_device = 1;

                if($iOS) {

                  $record_resultfinish->device = 'iOS';

                }

                if($iPad) {

                  $record_resultfinish->device = 'iPad';

                }

                if($iPhone) {

                  $record_resultfinish->device = 'iPhone';

                }

                if($Android) {

                  $record_resultfinish->device = 'Android';

                }



                //if($iPad)

              }



             $result_fisnish_id =  $record_resultfinish->save();



             if ($result_fisnish_id) {

                $record->quizresultfinish_id = $record_resultfinish->id;

                $record->save();

             }

            $return_redirect = '/exams/student-exam-series/'. $examseries_table->slug;







            flash('Bạn đã thi: '.$title_quiz,'', 'success');

            return redirect($return_redirect);

        } elseif ($finish_current == 2) {

            $quiz_current_id = 'quiz_'.$finish_current;

            $quiz_current_mark = 'quiz_'.$finish_current.'_mark';

            $quiz_current_analysis = 'quiz_'.$finish_current.'_analysis';

            $record_resultfinish = QuizResultfinish::find($quizresultfinish_data->id);

            $record_resultfinish->$quiz_current_id = $quiz->id;

            $record_resultfinish->$quiz_current_mark = $record->marks_obtained;

            $record_resultfinish->$quiz_current_analysis = $record->subject_analysis;

            $record_resultfinish->finish = $finish_current;

            $result_fisnish_id = $record_resultfinish->save();

                        //add result table after insert QuizResultfinish

            $record->quizresultfinish_id = $record_resultfinish->id;

            $record->save();

            $return_redirect = '/exams/student-exam-series/'. $examseries_table->slug;

            flash('Bạn đã thi: '.$title_quiz,'', 'success');

            return redirect($return_redirect);

        } elseif ($finish_current == 3) {

            $quiz_current_id = 'quiz_'.$finish_current;

            $quiz_current_mark = 'quiz_'.$finish_current.'_mark';

            $quiz_current_analysis = 'quiz_'.$finish_current.'_analysis';

            $record_resultfinish = QuizResultfinish::find($quizresultfinish_data->id);

            $record_resultfinish->$quiz_current_id = $quiz->id;

            $record_resultfinish->$quiz_current_mark = $record->marks_obtained;

            $record_resultfinish->$quiz_current_analysis = $record->subject_analysis;

            $record_resultfinish->finish = $finish_current;

            $result_fisnish_id = $record_resultfinish->save();

            //add result table after insert QuizResultfinish

            $record->quizresultfinish_id = $record_resultfinish->id;

            $record->save();

            $get_result =  QuizResultfinish::where('id','=', $quizresultfinish_data->id)->first();

             $result_quiz_1 = $get_result->quiz_1_mark;

             $result_quiz_2 = $get_result->quiz_2_mark;

             $result_quiz_3 = $get_result->quiz_3_mark;



             /*Sau khi tính hoàn thành 3 bài thi, Tính điểm theo từng Cấp N*/

             /*Tính điểm theo công thức N3*/

             $total_result = 0;



             if ($quiz->category_id == 1) {



               $result_quiz_1_analysis =json_decode($get_result->quiz_1_analysis);

               // Sum mondai 1~3 part 2

               $result_mondai_1_7 = 0;

               $i_result_quiz_2_analysis = 1;

               /*Tách mondai 1~3 quizz 2*/

               foreach($result_quiz_1_analysis as $record_analysis) {

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



               $result_quiz_1_analysis =json_decode($get_result->quiz_1_analysis);

               // Sum mondai 1~3 part 2

               $result_mondai_1_9 = 0;

               $i_result_quiz_2_analysis = 1;

               /*Tách mondai 1~3 quizz 2*/

               foreach($result_quiz_1_analysis as $record_analysis) {

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

               $quiz_1_total = round($result_mondai_1_9 * (60 / 59) );

               $quiz_2_total = round(($result_quiz_1 - $result_mondai_1_9) * (60 / 54)) ;

               $quiz_3_total = round($result_quiz_3 * (60 / 56));

             }



             /*Tính điểm theo công thức N3 (category = 3)*/

            if ($quiz->category_id == 3) {



              $result_quiz_2_analysis =json_decode($get_result->quiz_2_analysis);

                     // Sum mondai 1~3 part 2

              $result_mondai_1_3 = 0;

              $i_result_quiz_2_analysis = 1;

              /*Tách mondai 1~3 quizz 2*/

              foreach($result_quiz_2_analysis as $record_analysis) {

               if ($i_result_quiz_2_analysis <=3) {

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

              $result_quiz_2_analysis =json_decode($get_result->quiz_2_analysis);

                     // Sum mondai 1~3 part 2

              $result_mondai_1_3 = 0;

              $i_result_quiz_2_analysis = 1;

              /*Tách mondai 1~3 quizz 2*/

              foreach($result_quiz_2_analysis as $record_analysis) {

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

              $result_quiz_2_analysis =json_decode($get_result->quiz_2_analysis);

                     // Sum mondai 1~3 part 2

              $result_mondai_1_3 = 0;

              $i_result_quiz_2_analysis = 1;

              /*Tách mondai 1~3 quizz 2*/

              foreach($result_quiz_2_analysis as $record_analysis) {

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
/*
            if($quiz->category_id == 5) {

                  if ($quiz_1_total > 19 && $quiz_3_total > 19 && $total_result > 80) {

                        $status_thi = 1;

                  }

            }

            if($quiz->category_id == 4) {

                  if ($quiz_1_total > 19 && $quiz_3_total > 19 && $total_result > 95) {

                        $status_thi = 1;

                  }

            }

            if($quiz->category_id == 3) {

                  if ($quiz_1_total > 19 && $quiz_2_total > 19 && $quiz_3_total > 19 && $total_result > 95) {

                        $status_thi = 1;

                  }

            }



            if($quiz->category_id == 2) {

                  if ($quiz_1_total > 19 && $quiz_3_total > 19 && $total_result > 90) {

                        $status_thi = 1;

                  }

            }

            if($quiz->category_id == 1) {

                  if ($quiz_1_total > 19 && $quiz_3_total > 19 && $total_result > 100) {

                        $status_thi = 1;

                  }

            }

            $record_resultfinish->status = $status_thi;

*/
			$check_pass = new ExamScore();
			$record_resultfinish->status = $check_pass->checkPassingExam($quiz->category_id, $quiz_1_total, $quiz_2_total, $quiz_3_total);

            $record_resultfinish->save();

            $data['finish'] = 1;

            /*if($examseries_table->category_id == 4 || $examseries_table->category_id == 5) {

              $dienthi = 'KTNN: '.$quiz_1_total.' -Nghe hiểu: '.$quiz_3_total;

            } else {

              $dienthi = 'KTNN: '.$quiz_1_total.' -Đọc hiểu: '.$quiz_2_total.' -Nghe hiểu: '.$quiz_3_total;

            }

            try{

              sendEmail('exam-result', array('name'=>Auth::user()->name, 'to_email' => Auth::user()->email, 'hoten'=>Auth::user()->name, 'baithi' => $examseries_title, 'diemthi'=>$dienthi, 'tongdiem'=>$total_result));

            }

            catch(Exception $ex)

            {

            }

            */

			//  Sau khi thi xong, tư động gửi kết quả và chứng nhận (pdf) về email của học viên
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
			$layoutPDF = $quiz->category_id <= 3 ? 'student.exams.finish-results-exam' : 'student.exams.finish-results-exam2';

			$view_name     = view('admin.' .$layoutPDF, $data)->render();
			$htmldata = \Blade::compileString($view_name);
			//$log = new Logger(env('MAIL_LOG_PATH'));
			//$log->putLog('htmldata: '.$htmldata);

			try {

				$html2pdf = new Html2Pdf('P', 'A4', 'fr', true, 'UTF-8', array(5, 5, 5, 8));
				$html2pdf->setDefaultFont('cid0jp');
				$html2pdf->writeHTML($htmldata, false);
				$path = public_path() . '/uploads/certificate/' . 'CER_'. $user_record->name .'_'. time() .'.pdf' ;
				$html2pdf->output($path, 'F');
			} catch (Html2PdfException $e) {
				$html2pdf->clean();

				$formatter = new ExceptionFormatter($e);
				echo $formatter->getHtmlMessage();
			}
			$mail_template = $quiz->category_id <= 3 ? 'thongbaoketquathi123' : 'thongbaoketquathi45';
			try {
				sendEmail($mail_template,
					array(
						'name'=>$user_record->name,
						'title'=>$examseries_title,
						'start_date'=> date("d-m-Y"),
						'quiz1'=>$record_resultfinish->quiz_1_total,
						'quiz2'=>$record_resultfinish->quiz_2_total,
						'quiz3'=>$record_resultfinish->quiz_3_total,
						'total' => $record_resultfinish->total_marks,
						'status' => $ketqua ,
						'attachment' => $path ,
						'to_email' => Auth::user()->email
					));
			} catch (\Exception $e) {
				$log = new Logger(env('MAIL_LOG_PATH'));
				$log->putLog('An error occurred: ' . $e->getMessage());
			}
            $return_redirect = '/exams/student/finish-exam-result/'. $record_resultfinish->id;

            flash('Bạn đã thi: ' .$title_quiz,'', 'success');

            return redirect($return_redirect);

        }



        // $template    = new EmailTemplate();

        // $content_data =  $template->sendEmailNotification('exam-result',

        //  array('username'    =>$user_record->name,

        //           'content'  => $content,

        //           'to_email' => $user_record->email));

        /*try {

          $user_record->notify(new \App\Notifications\StudentExamResult($user_record,$exam_status,$quiz->title,$quiz->pass_percentage));

        } catch (Exception $e) {

          // dd($e->getMessage());

        }*/

        $topperStatus = false;

        $data['isUserTopper']       = $topperStatus;

        $data['rank_details']       = FALSE;

        $data['quiz']               = $quiz;

        $data['active_class']       = 'exams';

        $data['record_resultfinish']       = $record_resultfinish;

        $data['examseries_category']       = $examseries_table->category_id;

        $data['examseries']       = $examseries_table;

        $data['title']              = change_furigana_title($quiz->title);

        $data['record']             = $record;

        $data['user']               = $user_record;

        //Chart Data START

        /*$color_correct = getColor('background', rand(1,999));

        $color_wrong = getColor('background', rand(1,999));

        $color_not_attempted = getColor('background', rand(1,999));

        // $labels_marks = [getPhrase('correct'), getPhrase('wrong'), getPhrase('not_answered')];

        $labels_marks = ['ただしい', 'まちがい', 'みかいとう'];

        $dataset_marks = [count(json_decode($record->correct_answer_questions)),

                          count(json_decode($record->wrong_answer_questions)),

                          count(json_decode($record->not_answered_questions))];

        $dataset_label_marks = "Marks";

        $bgcolor  = [$color_correct,$color_wrong,$color_not_attempted];

        $border_color = [$color_correct,$color_wrong,$color_not_attempted];

        $chart_data['type'] = 'doughnut';

         $chart_data['data']   = (object) array(

            'labels'            => $labels_marks,

            'dataset'           => $dataset_marks,

            'dataset_label'     => $dataset_label_marks,

            'bgcolor'           => $bgcolor,

            'border_color'      => $border_color

            );

        $data['marks_data'][] = (object)$chart_data;

        $time_spent = 0;

        foreach(json_decode($record->time_spent_correct_answer_questions) as $rec)

        {

          $time_spent += $rec->time_spent;

        }

        foreach(json_decode($record->time_spent_wrong_answer_questions) as $rec)

        {

          $time_spent += $rec->time_spent;

        }

        foreach(json_decode($record->time_spent_not_answered_questions) as $rec)

        {

          $time_spent += $rec->time_spent;

        }

        //Time Chart Data

        $color_correct        = getColor('background', rand(1,999));

        $color_wrong          = getColor('background', rand(1,999));

        $color_not_attempted  = getColor('background', rand(1,999));

        $total_time           = $quiz->dueration*60;

        $total_time_spent     = ($time_spent);

        // $labels_time          = [getPhrase('total_time').' (sec)', getPhrase('consumed_time').' (sec)'];

        $labels_time          = ['しけんじかん'.' (秒)', 'かけたじかん'.' (秒)'];

        $dataset_time         = [ $total_time, $time_spent];

        $dataset_label_time   = "Time in sec";

        $bgcolor              = [$color_correct,$color_wrong,$color_not_attempted];

        $border_color         = [$color_correct,$color_wrong,$color_not_attempted];

        $chart_data['type']   = 'pie';

        $chart_data['data']  = (object) array(

                                                'labels'          => $labels_time,

                                                'dataset'         => $dataset_time,

                                                'dataset_label'   => $dataset_label_time,

                                                'bgcolor'         => $bgcolor,

                                                'border_color'    => $border_color

                                                );

                                                $data['time_data'][]  = (object)$chart_data;*/

        //Chart Data END

        /*$quizrecordObject     = new QuizResult();

        $history              = array();

        $history              = $quizrecordObject->getHistory();*/

        $toppers              = array();

        $data['toppers']      = $toppers;

        $data['block_navigation']          = TRUE;

        $data['examseries_slug'] = $examseries_table->slug;

        //sleep(2);

        $view_name = 'admin.student.exams.results';

        return view($view_name, $data);

      }

    /**

     * Pick grade record based on percentage from grades table

     * @param  [type] $percentage [description]

     * @return [type]             [description]

     */

    public function getPercentageRecord($percentage)

    {

      return DB::table('grades')

      ->where('percentage_from', '<=',$percentage)

      ->where('percentage_to', '>=',$percentage)

      ->get();

    }

    /**

     * This below method process the submitted answers based on the

     * provided answers and quiz questions

     * @param  [type] $answers [description]

     * @return [type]          [description]

     */

    public function processAnswers($answers, $subject, $time_spent, $negative_mark = 0)

    {

      $obtained_marks     = 0;

      $correct_answers    = 0;

      $obtained_negative_marks = 0;

      $corrent_answer_question            = [];

      $wrong_answer_question              = [];

      $time_spent_correct_answer_question = [];

      $time_spent_wrong_answer_question   = [];

      foreach ($answers as $key => $value) {

        if( is_numeric( $key ))

        {

          $question_record  = $this->getQuestionRecord($key);

          $question_type    = $question_record->question_type;

          $actual_answer    = $question_record->correct_answers;

          $subject_id       = $question_record->subject_id;

          if(! array_key_exists($subject_id, $subject)) {

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

            if($value[0] == $actual_answer)

            {

              $correct_answers++;

              $obtained_marks                 += $question_record->marks;

              $corrent_answer_question[]       = $question_record->id;

              $subject[$subject_id]['correct_answers'] +=1;

              $subject[$subject_id]['time_spent_correct_answers'] += $time_spent[$question_record->id];

              $time_spent_correct_answer_question[$question_record->id]['time_to_spend']

              = $question_record->time_to_spend;

              $time_spent_correct_answer_question[$question_record->id]['time_spent']

              = $time_spent[$question_record->id];

            }

            else {

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

            $i=0;

            $flag= 1;

            foreach($value as $answer_key => $answer_value )

            {

              if(isset($actual_answer[$answer_key]))

              {

                if( $actual_answer[$answer_key]->answer !=

                  $answer_value )

                {

                  $flag = 0; break;

                }

              }

              else {

                $flag = 0; break;

              }

            }

            if($flag)

            {

              $correct_answers++;

              $obtained_marks += $question_record->marks;

              $corrent_answer_question[] = $question_record->id;

              $subject[$subject_id]['correct_answers'] +=1;

              $subject[$subject_id]['time_spent_correct_answers']

              += $time_spent[$question_record->id];

              $time_spent_correct_answer_question[$question_record->id]['time_to_spend']

              = $question_record->time_to_spend;

              $time_spent_correct_answer_question[$question_record->id]['time_spent']

              = $time_spent[$question_record->id];

            }

            else {

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

            $i=0;

            $flag= 1;

            foreach($actual_answer as $answer)

            {

              if(strcasecmp(

                trim($answer->answer),

                trim($value[$i++])) != 0)

              {

                $flag = 0; break;

              }

            }

            if($flag)

            {

              $correct_answers++;

              $obtained_marks += $question_record->marks;

              $corrent_answer_question[] = $question_record->id;

              $subject[$subject_id]['correct_answers'] +=1;

              $subject[$subject_id]['time_spent_correct_answers']

              += $time_spent[$question_record->id];

              $time_spent_correct_answer_question[$question_record->id]['time_to_spend']

              = $question_record->time_to_spend;

              $time_spent_correct_answer_question[$question_record->id]['time_spent']

              = $time_spent[$question_record->id];

            }

            else

            {

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

            case (  $question_type == 'para'  ||

              $question_type == 'audio' ||

              $question_type == 'video'

            ):

            $actual_answer = json_decode($actual_answer);

            $indidual_marks = $question_record->marks/$question_record->total_correct_answers;

            $i=0;

            $flag= 0;

            foreach($value as $answer_key => $answer_value )

            {

              if($actual_answer[$answer_key]->answer == $answer_value)

              {

                $flag=1;

                $obtained_marks += $indidual_marks;

              }

            }

            if($flag)

            {

              $correct_answers++;

              $corrent_answer_question[] = $question_record->id;

              $subject[$subject_id]['correct_answers'] +=1;

              $subject[$subject_id]['time_spent_correct_answers']

              += $time_spent[$question_record->id];

              $time_spent_correct_answer_question[$question_record->id]['time_to_spend']

              = $question_record->time_to_spend;

              $time_spent_correct_answer_question[$question_record->id]['time_spent']

              = $time_spent[$question_record->id];

            }

            else

            {

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

            $indidual_marks = $question_record->marks/$question_record->total_correct_answers;

            $i=0;

            $flag= 0;

            foreach($actual_answer as $answer)

            {

              if($answer->answer == $value[$i++])

              {

                $flag=1;

                $obtained_marks += $indidual_marks;

              }

            }

            if($flag)

            {

              $correct_answers++;

              $corrent_answer_question[] = $question_record->id;

              $subject[$subject_id]['correct_answers'] +=1;

              $subject[$subject_id]['time_spent_correct_answers']

              += $time_spent[$question_record->id];

              $time_spent_correct_answer_question[$question_record->id]['time_to_spend']

              = $question_record->time_to_spend;

              $time_spent_correct_answer_question[$question_record->id]['time_spent']

              = $time_spent[$question_record->id];

            }

            else

            {

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

        // dd($time_spent_correct_answer_question);

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

    /**

     * Returns the percentage of the number

     * @param  [type] $total [description]

     * @param  [type] $goal  [description]

     * @return [type]        [description]

     */

    public function getPercentage($total, $goal)

    {

      return ($total / $goal) * 100;

    }

    /**

     * Returns the specific question record based on question_id

     * @param  [type] $question_id [description]

     * @return [type]              [description]

     */

    function getQuestionRecord($question_id)

    {

      return QuestionBank::where('id','=',$question_id)->first();

    }

     /**

     * This method returns the datatables data to view

     * @return [type] [description]

     */

     public function getDatatable($slug = '')

     {

      $records = array();

      if($slug=='all')

      {

        $cats  = User::getUserSeleted('categories');

        $records = Quiz::join('quizcategories', 'quizzes.category_id', '=', 'quizcategories.id')

        ->select(['title', 'dueration', 'category', 'is_paid', 'total_marks','tags','quizzes.slug','quizzes.validity','quizzes.cost' ])

        ->where('total_marks', '!=', 0)

        ->where('start_date','<=',date('Y-m-d'))

        ->where('end_date','>=',date('Y-m-d'))

        ->whereIn('quizzes.category_id',$cats)

        ->get();

      }

      else {

        $category = QuizCategory::getRecordWithSlug($slug);

        $records = Quiz::join('quizcategories', 'quizzes.category_id', '=', 'quizcategories.id')

        ->select(['title', 'dueration', 'category', 'is_paid', 'total_marks','quizzes.slug', 'quizzes.validity','quizzes.cost' ])

        ->where('quizzes.category_id', '=', $category->id)

        ->where('total_marks', '!=', 0)

        ->where('start_date','<=',date('Y-m-d'))

        ->where('end_date','>=',date('Y-m-d'))

        ->get();

      }

      return DataTables::of($records)

      ->addColumn('action', function ($records) {

        if(!checkRole(['student']))

          if($records->is_paid)

            return '<a href="'.URL_PAYMENTS_CHECKOUT.'exam/'.$records->slug.'">'.getPhrase('buy_now').'</a>';

          else

            return '-';

          return '<div class="dropdown more">

          <a id="dLabel" type="button" class="more-dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">

          <i class="mdi mdi-dots-vertical"></i>

          </a>

          <ul class="dropdown-menu" aria-labelledby="dLabel">

          <li><a onClick="showInstructions(\''.URL_STUDENT_TAKE_EXAM.$records->slug.'\')" href="javascript:void(0);"><i class="fa fa-pencil"></i>'.getPhrase("take_exam").'</a></li>

          </ul>

          </div>';

        })

      ->editColumn('is_paid', function($records)

      {

        $status = ($records->is_paid) ? '<span class="label label-primary">'.getPhrase('paid') .'</span>' : '<span class="label label-success">'.getPhrase('free').'</span>';

        if($records->is_paid) {

          $extra = '<ul class="list-unstyled payment-col clearfix"><li>'.$status.'</li>';

          $extra .='<li><p>Cost: '.getCurrencyCode().' '.$records->cost.'</p><p>Validity: '.$records->validity.' '.getPhrase("days").'</p></li></ul>';

          return $extra;

        }

        return $status;

      })

      ->editColumn('dueration', function($records)

      {

        return $records->dueration . ' '.getPhrase('mins');

      })

      ->editColumn('title', function($records)

      {

        if(!checkRole(['student'])) {

          if($records->is_paid) {

            return '<a href="'.URL_PAYMENTS_CHECKOUT.'exam/'.$records->slug.'">'.$records->title.'</a>';

          }

          return $records->title;

        }

        $paid_type =  false;

        if($records->is_paid && !isItemPurchased($records->id, 'exam'))

          $paid_type = true;

        if($paid_type) {

          return '<a href="'.URL_PAYMENTS_CHECKOUT.'exam/'.$records->slug.'">'.$records->title.'</a>';

        }

        return '<a onClick="showInstructions(\''.URL_STUDENT_TAKE_EXAM.$records->slug.'\')" href="javascript:void(0);">'.$records->title.'</a>';

      })

      ->removeColumn('tags')

      ->removeColumn('id')

      ->removeColumn('slug')

      ->removeColumn('validity')

      ->removeColumn('cost')

      ->make();

    }

    public function isValidRecord($record)

    {

      if ($record === null) {

        // flash('Ooops...!', getPhrase("page_not_found"), 'error');

        return $this->getRedirectUrl();

      }

      return FALSE;

    }

    public function getReturnUrl()

    {

      return URL_STUDENT_EXAM_CATEGORIES;

    }

    public function reports($slug)

    {

      dd(User::getRecordWithSlug($slug));

      dd($slug);

    }

    /**

     * This method fetches the list of exam attempts made by the user based on the slug

     * @param  string $slug [description]

     * @return [type]       [description]

     */

    public function examAttempts($slug, $exam_slug = '')

    {

      $user = User::getRecordWithSlug($slug);

      if($isValid = $this->isValidRecord($user))

        return redirect($isValid);

      if(!isEligible($slug))

        return back();

      $exam_record = FALSE;

      if(!$exam_slug)

      {

        $marks = App\QuizResult::where('user_id', '=', $user->id)

        ->orderBy('updated_at','desc')->get();

      } else {

        // echo  $exam_slug;

        $marks = App\QuizResult::where('user_id', '=', $user->id)

        ->where('quizresultfinish_id', '=', $exam_slug)

        ->orderBy('updated_at','desc')->get();

      }

      $chartSettings = new App\ChartSettings();

      $colors = (object) $chartSettings->getRandomColors(count($marks));

      $i=0;

      $labels = [];

      $dataset = [];

      $dataset_label = [];

      $bgcolor = [];

      $border_color = [];

      foreach($marks as $record) {

        $quiz_record = $record->quizName;

        $labels[] = $quiz_record->title.' '.$record->updated_at;

        $dataset[] = $record->percentage;

        $dataset_label = $quiz_record->title.' ('.$record->percentage.'%)';

        $bgcolor[] = $colors->bgcolor[$i];

        $border_color[] = $colors->border_color[$i++];

      }

      $chart_data['type'] = 'line';

        //horizontalBar, bar, polarArea, line, doughnut, pie

      $chart_data['title'] = getPhrase('exam_attempts_and_score');

      $chart_data['data']   = (object) array(

        'labels'            => $labels,

        'dataset'           => $dataset,

        'dataset_label'     => getPhrase('percentage').' (%)',

        'bgcolor'           => $bgcolor,

        'border_color'      => $border_color

      );

      $data['chart_data'] = (object)$chart_data;

      $data['active_class']       = 'analysis';

      $data['title']              = 'Lịch sử bài thi';

      $data['user']               = $user;

      $data['exam_record']        = $exam_record;

      $data['quizresultfinish_id']  = $exam_slug;

      $data['layout']             = getLayout();

      //Get category id (N3, 4, 5)

      $record_resultfinish = QuizResultfinish::find($exam_slug);

      if ($record_resultfinish->examseri_id) {

        $examseries_data = DB::table('examseries')

        ->where('id', '=', $record_resultfinish->examseri_id)

        ->first();

        $data['de_thi'] = $examseries_data->title;

        $data['category_id'] = $examseries_data->category_id;

      }

      $view_name = 'admin.student.exams.attempts-history-show';

      return view($view_name, $data);

    }

    public function examAttemptsfinish()

    {

      $exam_record = FALSE;

      $marks = array();

      $marks = App\QuizResult::where('user_id', '=', Auth::user()->id)

        ->orderBy('updated_at','desc')->get();

      $data['active_class']       = 'analysis';

      $data['title']              = 'Kết quả thi';

      $data['user']               = Auth::user();

      $data['exam_record']        = $exam_record;

      $data['layout']             = getLayout();

      $view_name = 'admin.student.exams.attempts-history-finish';

      return view($view_name, $data);

    }

    /**

     * This method returns the datatable for the student exam attempts

     * @param  [type] $slug [description]

     * @return [type]       [description]

     */

    public function getExamAttemptsDataFinish($slug, $exam_slug = '')

    {

      $user = User::getRecordWithSlug($slug);

      $exam_record = FALSE;

        /*if($exam_slug)

        {

          $exam_record = Quiz::getRecordWithSlug($exam_slug);

        }*/

        $records = array();

        $records = QuizResultfinish::join('examseries', 'examseries.id', '=', 'quizresultfinish.examseri_id')

        ->select(['examseries.title', 'quizresultfinish.created_at',

            'quizresultfinish.id', 'quizresultfinish.quiz_1_total','quizresultfinish.quiz_2_total',

            'quizresultfinish.quiz_3_total','total_marks','quizresultfinish.user_id', 'examseries.category_id', 'quizresultfinish.status' ])

        ->where('quizresultfinish.user_id', '=', $user->id)

        ->where('quizresultfinish.finish', '=', 3)

        ->orderBy('quizresultfinish.id', 'desc')

        ->limit(10)

        ->get();





        //dd($records);

        /*$records = QuizResultfinish::select(['quizresultfinish.id', 'quizresultfinish.quiz_1_total','quizresultfinish.quiz_2_total', 'quizresultfinish.quiz_3_total','quizresultfinish.user_id', 'quizresultfinish.created_at', 'total_marks', 'quizresultfinish.updated_at' ])

            ->where('quizresultfinish.user_id', '=', $user->id)

            ->where('quizresultfinish.finish', '=', 3)

            ->orderBy('id', 'desc')

            ->get();*/

            return datatables::of($records)

             ->addColumn('action', function($records)

             {



                 $record  = Quiz::join('quizresults', 'quizzes.id', '=', 'quizresults.quiz_id')

                     ->select(['quizzes.slug', 'quizresults.slug as resultsslug','user_id','quizzes.type' ])

                     ->where('user_id', '=', Auth::user()->id)

                     ->where('quizresultfinish_id', '=', $records->id)

                     ->orderBy('quizresults.updated_at', 'asc')

                     ->get();







                 $detail = '';

                 foreach ($record as $item){



                     switch ($item->type) {

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





                     $detail.= '<a class="mr-2" href="'.URL_RESULTS_VIEW_ANSWERS.$item->slug.'/'.$item->resultsslug.'"><span class="label label-success py-2 " >'.$title.'</span></a>';

                 }

              // $detail = '<a href="'. URL_STUDENT_EXAM_ATTEMPTS.Auth::user()->slug.'/'.$records->id.'"><i class="fa fa-eye"></i> Xem</a>';

               return $detail;

             })

            ->editColumn('title', function($records)

            {

              return change_furigana_admin($records->title);

            })

            ->editColumn('quiz_1_total', function($records)

            {

              switch ($records->category_id) {

                case '1':

                  $detail = '<span class="">KTNN '.$records->quiz_1_total.'/60</span> - <span class=" ">Đọc hiểu: '.$records->quiz_2_total.'/60</span> - <span class=" ">Nghe hiểu: '.$records->quiz_3_total.'/60</span>';

                  break;

                case '2':

                  $detail = '<span class="">KTNN '.$records->quiz_1_total.'/60</span> -  <span class=" ">Đọc hiểu: '.$records->quiz_2_total.'/60</span> - <span class="">Nghe hiểu: '.$records->quiz_3_total.'/60</span>';

                  break;

                case '3':

                  $detail = '<span class=" ">KTNN: '.$records->quiz_1_total.'/60</span> -  <span class="">Đọc hiểu: '.$records->quiz_2_total.'/60</span> - <span class=" ">Nghe hiểu: '.$records->quiz_3_total.'/60</span>';

                  break;

                case '4':

                  $detail = '<span class=" ">KTNN: '.$records->quiz_1_total.'/120</span> -  <span class="">Nghe hiểu: '.$records->quiz_3_total.'/60</span>';

                  break;

                case '5':

                  $detail = '<span class="">KTNN: '.$records->quiz_1_total.'/120</span> -  <span class="">Nghe hiểu: '.$records->quiz_3_total.'/60</span>';

                  break;

              }

              return $detail;

            })

            ->editColumn('total_marks', function($records)

            {



              $detail = '<span class="badge badge-outline-info badge-pill">'.$records->total_marks.'</span>';

              return $detail;

            })

            ->editColumn('status', function($records)

            {

                $detail  =  '<span class="text-warning">Chưa đạt</span>';

                if ($records->status == 1) {

                  $detail = '<span class="text-success">Đạt</span>';

                }

             return $detail;

           })

            ->removeColumn('created_at')

            ->removeColumn('id')

            ->removeColumn('finish')

            ->removeColumn('user_id')

            ->removeColumn('quiz_2_total')

            ->removeColumn('quiz_3_total')

            ->removeColumn('category_id')

            ->make();

          }

    /**

     * This method returns the datatable for the student exam attempts

     * @param  [type] $slug [description]

     * @return [type]       [description]

     */

    public function getExamAttemptsData($slug, $exam_slug = '')

    {

      $user = User::getRecordWithSlug($slug);

      $exam_record = FALSE;

      if($exam_slug)

      {

        $exam_record = Quiz::getRecordWithSlug($exam_slug);

      }

      $records = array();

      if(!$exam_slug)

        $records = Quiz::join('quizresults', 'quizzes.id', '=', 'quizresults.quiz_id')

      ->select(['title','is_paid' , 'marks_obtained', 'exam_status','quizresults.created_at',

          'quizzes.total_marks','quizzes.slug', 'quizresults.slug as resultsslug','user_id' ])

      ->where('user_id', '=', $user->id)

      ->orderBy('quizresults.id', 'desc')

      ->get();

      else

            /*$records = Quiz::join('quizresults', 'quizzes.id', '=', 'quizresults.quiz_id')

            ->select(['title','is_paid' , 'marks_obtained', 'exam_status','quizresults.created_at', 'quizzes.total_marks','quizzes.slug', 'quizresults.slug as resultsslug','user_id' ])

            ->where('user_id', '=', $user->id)

            ->where('quiz_id', '=', $exam_record->id )

            ->orderBy('quizresults.updated_at', 'desc')

            ->get();*/

            $records = Quiz::join('quizresults', 'quizzes.id', '=', 'quizresults.quiz_id')

            ->select(['title','marks_obtained', 'exam_status','quizresults.created_at',

                'quizzes.total_marks','quizzes.slug', 'quizresults.slug as resultsslug','user_id','quizzes.type' ])

            ->where('user_id', '=', $user->id)

            ->where('quizresultfinish_id', '=', $exam_slug)

            ->orderBy('quizresults.updated_at', 'asc')

            ->get();





            return DataTables::of($records)

            ->addColumn('action', function($records)

            {

              $options = '<a href="'.URL_RESULTS_VIEW_ANSWERS.$records->slug.'/'.$records->resultsslug.'" target="_blank"><i class="fa fa-pencil"></i> Xem kết quả</a>';

              $certificate_link = '';

              $tail = '';

              return $options.$certificate_link.$tail;

                           // return 1;

            })

            ->editColumn('title', function($records)

            {



                  switch ($records->type) {

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



              $user = User::where('id', '=', $records->user_id)->get()->first();

              return '<a href="'.URL_STUDENT_EXAM_ANALYSIS_BYSUBJECT.$user->slug.'/'.$records->slug.'/'.$records->resultsslug.'" data="ketqua">'.$title.'</a>';

            //return change_furigana_admin($records->title);

            })

        /*->editColumn('marks_obtained', function($records)

        {

          $marks = intval ($records->marks_obtained.' / '.$records->total_marks);

          $marks = ($marks > 9) ? '<span class="label label-success">'.$marks.'</span>' : '<span class="label label-danger">'.$marks.'</span>';

          return $marks;

          // return 0;

        })*/

/*        ->editColumn('exam_status', function($records)

        {

           $result = ucfirst($records->exam_status);

           $dau = "Đậu";

           $rot = "Rớt";

           return ($result=='Pass') ? '<span class="label label-success">'.$dau.'</span>' : '<span class="label label-danger">'.$rot.'</span>';

         })*/

         ->removeColumn('exam_status')

         ->removeColumn('marks_obtained')

         ->removeColumn('total_marks')

         ->removeColumn('slug')

         ->removeColumn('quiz_id')

         ->removeColumn('created_at')

         ->removeColumn('user_id')

         ->removeColumn('resultsslug')

         ->removeColumn('grade_title')

         ->removeColumn('grade_points')

         ->removeColumn('quizzes.total_marks')

         ->removeColumn('type')

         ->make();

       }

    /**

     * Generates the List of exams and no. of attempts for each exam

     * @param  [type] $slug [description]

     * @return [type]       [description]

     */

    public function examAnalysis($slug)

    {

      $user = User::getRecordWithSlug($slug);

      if($isValid = $this->isValidRecord($user))

        return redirect($isValid);

      if(!isEligible($slug))

        return back();

      $userid = $user->id;

      $data['active_class']       = 'analysis';

      $data['title']              = getPhrase('exam_analysis_by_attempts');

      $data['user']               = $user;

        // Chart code start

      $records = array();

      $records = Quiz::join('quizresults', 'quizzes.id', '=', 'quizresults.quiz_id')

      ->select(['title','is_paid' ,'dueration', 'quizzes.total_marks',  \DB::raw('count(quizresults.user_id) as attempts, quizzes.slug, user_id') ])

      ->where('user_id', '=', $user->id)

      ->groupBy('quizresults.quiz_id')

      ->get();

      $chartSettings = new App\ChartSettings();

      $colors = (object) $chartSettings->getRandomColors(count($records));

      $i=0;

      $labels = [];

      $dataset = [];

      $dataset_label = [];

      $bgcolor = [];

      foreach($records as $record) {

        $quiz_record = $record->title;

        $labels[] = $record->title.' ('.$record->attempts.' '.getPhrase('attempts').')';

        $dataset[] = $record->attempts;

        $dataset_label[] = $record->title.' ('.$record->attempts.' '.getPhrase('attempts').')';

        $bgcolor[] = $colors->bgcolor[$i];

        $border_color[] = $colors->border_color[$i++];

      }

      $chart_data['type'] = 'pie';

        //horizontalBar, bar, polarArea, line, doughnut, pie

      $chart_data['title'] = getPhrase('exam_analysis_by_attempts');

      $border_color=[];

      $chart_data['data']   = (object) array(

        'labels'            => $labels,

        'dataset'           => $dataset,

        'dataset_label'     => $dataset_label,

        'bgcolor'           => $bgcolor,

        'border_color'      => $border_color

      );

      $data['chart_data'][] = (object)$chart_data;

        //Chart Code End

      $data['layout']             = getLayout();

      // return view('student.exams.analysis-by-exam', $data);

      $view_name = 'admin.student.exams.analysis-by-exam';

      return view($view_name, $data);

    }

     /**

     * This method returns the datatable for the student exam attempts

     * @param  [type] $slug [description]

     * @return [type]       [description]

     */

     public function getExamAnalysisData($slug)

     {

      $user = User::getRecordWithSlug($slug);

      $records = array();

      $records = Quiz::join('quizresults', 'quizzes.id', '=', 'quizresults.quiz_id')

      ->select(['title','is_paid' ,'dueration', 'quizzes.total_marks',  \DB::raw('count(quizresults.user_id) as attempts, quizzes.slug, user_id') ])

      ->where('user_id', '=', $user->id)

      ->groupBy('quizresults.quiz_id')

      ->get();

      return DataTables::of($records)

      ->editColumn('title', function($records)

      {

        $user = User::where('id', '=', $records->user_id)->get()->first();

        return '<a href="'.URL_STUDENT_EXAM_ATTEMPTS.$user->slug.'/'.$records->slug.'"">'.$records->title.'</a>';

      })

      ->editColumn('is_paid', function($records)

      {

        return ($records->is_paid) ? '<span class="label label-primary">'.getPhrase('paid') .'</span>' : '<span class="label label-success">'.getPhrase('free').'</span>';

      })

      ->editColumn('dueration', function($records)

      {

        return $records->dueration.' '.getPhrase('mins');

      })

      ->removeColumn('quizzes.total_marks')

      ->removeColumn('slug')

      ->removeColumn('user_id')
      ->rawColumns(['title', 'is_paid'])
      ->make();

    }

    /**

     * [subjectAnalysisInExam description]

     * @param  [type] $slug      [description]

     * @param  string $exam_slug [description]

     * @return [type]            [description]

     */

    public function subjectAnalysisInExam($slug, $exam_slug = '', $result_slug = '')

    {

      $user = User::getRecordWithSlug($slug);

      if($isValid = $this->isValidRecord($user))

        return redirect($isValid);

      $exam_record = FALSE;

      if(!isEligible($slug))

        return back();

      if($exam_slug)

      {

        $exam_record = Quiz::getRecordWithSlug($exam_slug);

      }

      if($isValid = $this->isValidRecord($exam_record))

        return redirect($isValid);

      $result = array();

      $result = App\QuizResult::where('user_id', '=', $user->id)

      ->where('quiz_id', '=', $exam_record->id)

      ->where('slug', '=', $result_slug)

      ->get()->first();

      if($isValid = $this->isValidRecord($result))

        return redirect($isValid);

      //Everything is fine, we got the exam record and result record,

      //Process the result record to analyze the weekness and strength in each subject

      $data['quizresult'] = $result;

      $result = json_decode($result->subject_analysis);

      $subjects_display = array();

      $i=0;

      $color_correct = getColor('background', rand(1,999));

      $color_wrong = getColor('background', rand(1,999));

      $color_not_attempted = getColor('background', rand(1,999));

      $labels_marks = [getPhrase('correct'), getPhrase('wrong'), getPhrase('not_answered')];

      $labels_time = [getPhrase('time_spent_correct_answers'), getPhrase('time_spent_wrong_answers')];

      $dataset_time = [];

      foreach($result as $record) {

        // $colors = (object) $chartSettings->getRandomColors(count($result)+1);

        $labels = [];

        $dataset = [];

        $dataset_label = [];

        $bgcolor = [];

        $border_color = [];

        $subject_record = Subject::where('id', '=', $record->subject_id)->first();

        $subjects_display[$i]['subject_name'] = $subject_record->subject_title;

        $subjects_display[$i]['correct_answers'] = $record->correct_answers;

        $subjects_display[$i]['wrong_answers'] = $record->wrong_answers;

        $subjects_display[$i]['not_answered'] = $record->not_answered;

        $subjects_display[$i]['time_spent_correct_answers'] = $record->time_spent_correct_answers;

        $subjects_display[$i]['time_spent_wrong_answers'] = $record->time_spent_wrong_answers;

        $dataset_time = [$record->time_spent_correct_answers, $record->time_spent_wrong_answers];

        $bgcolor_time  = [$color_correct,$color_wrong];

        $border_color_time = [$color_correct,$color_wrong];

        $dataset = [$record->correct_answers, $record->wrong_answers, $record->not_answered];

        $dataset_label[] = $subject_record->subject_title;

        $bgcolor  = [$color_correct,$color_wrong,$color_not_attempted];

            // $bgcolor  = getColor('border');

        $border_color = [$color_correct,$color_wrong,$color_not_attempted];

        $time_data['type'] = 'pie';

        $time_data['title'] = $subject_record->subject_title;

        $time_data['data']   = (object) array(

          'labels'            => $labels_time,

          'dataset'           => $dataset_time,

          'dataset_label'     => $dataset_label,

          'bgcolor'           => $bgcolor_time,

          'border_color'      => $border_color_time

        );

        $data['time_data'][] = (object)$time_data;

        $chart_data['type'] = 'doughnut';

        //horizontalBar, bar, polarArea, line, doughnut, pie

        $chart_data['title'] = $subject_record->subject_title;

        $chart_data['data']   = (object) array(

          'labels'            => $labels_marks,

          'dataset'           => $dataset,

          'dataset_label'     => $dataset_label,

          'bgcolor'           => $bgcolor,

          'border_color'      => $border_color

        );

        $data['chart_data'][] = (object)$chart_data;

        $i++;

      }

      $data['subjects_display']   = $subjects_display;

      $data['active_class']       = 'analysis';

      $data['title']              = getPhrase('subject_wise_analysis');

      $data['user']               = $user;

      $data['exam_record']        = $exam_record;

      $data['layout']             = getLayout();

      // return view('student.exams.analysis-by-subject', $data);

      $view_name = 'admin.student.exams.analysis-by-subject';

      return view($view_name, $data);

    }

    /**

     * This method returns the datatable for the student exam attempts

     * @param  [type] $slug [description]

     * @return [type]       [description]

     */

    public function overallSubjectAnalysis($slug)

    {

      $user = User::getRecordWithSlug($slug);

      if($isValid = $this->isValidRecord($user))

        return redirect($isValid);

      if(!isEligible($slug))

        return back();

      $records = array();

      $records = ( new App\QuizResult())->getOverallSubjectsReport($user);

      if(!$records)

      {

        flash('Ooops..!','No Records available', 'overlay');

        return back();

      }

      $color_correct = getColor('background',rand(00,9999));

      $color_wrong = getColor('background', rand(00,9999));

      $color_not_attempted = getColor('background', rand(00,9999));

      $i=0;

      $labels = [];

      $dataset = [];

      $dataset_label = [];

      $bgcolor = [];

      $border_color = [];

      $marks_labels = [getPhrase('correct'), getPhrase('wrong'), getPhrase('not_answered')];

      $time_labels = [getPhrase('time_spent_on_correct_answers'), getPhrase('time_spent_on_wrong_answers')];

      foreach($records as $record) {

        $record = (object)$record;

       //Marks

        $subjects_display[$i]['subject_name'] = $record->subject_name;

        $subjects_display[$i]['correct_answers'] = $record->correct_answers;

        $subjects_display[$i]['wrong_answers'] = $record->wrong_answers;

        $subjects_display[$i]['not_answered'] = $record->not_answered;

        // Time

        $subjects_display[$i]['time_spent_on_correct_answers'] = $record->time_spent_on_correct_answers;

        $subjects_display[$i]['time_spent_on_wrong_answers']   = $record->time_spent_on_wrong_answers;

        $subjects_display[$i]['time_to_spend']                 = $record->time_to_spend;

        $subjects_display[$i]['time_spent']                    = $record->time_spent;

        $marks_dataset = [$record->correct_answers, $record->wrong_answers, $record->not_answered];

        $time_dataset = [$record->time_spent_on_correct_answers, $record->time_spent_on_wrong_answers];

        $dataset_label = $record->subject_name;

        $bgcolor  = [$color_correct,$color_wrong,$color_not_attempted];

        $border_color = [$color_correct,$color_wrong,$color_not_attempted];

        $marks_data['type'] = 'pie';

        //horizontalBar, bar, polarArea, line, doughnut, pie

        $marks_data['title'] = $record->subject_name;

        $marks_data['data']   = (object) array(

          'labels'            => $marks_labels,

          'dataset'           => $marks_dataset,

          'dataset_label'     => $dataset_label,

          'bgcolor'           => $bgcolor,

          'border_color'      => $border_color

        );

        $data['chart_data'][] = (object)$marks_data;

        $time_data['type'] = 'bar';

        //horizontalBar, bar, polarArea, line, doughnut, pie

        $time_data['title'] = $record->subject_name;

        $time_data['data']   = (object) array(

          'labels'            => $time_labels,

          'dataset'           => $time_dataset,

          'dataset_label'     => $dataset_label,

          'bgcolor'           => $bgcolor,

          'border_color'      => $border_color

        );

        $data['time_data'][] = (object)$time_data;

        $i++;

      }

      $data['chart_data'][] = (object)$marks_data;

      $overall_correct_answers = 0;

      $overall_wrong_answers = 0;

      $overall_not_answered = 0;

      $overall_time_spent_correct_answers = 0;

      $overall_time_spent_wrong_answers = 0;

      foreach($records as $r)

      {

        $r = (object)$r;

        $overall_correct_answers  += $r->correct_answers;

        $overall_wrong_answers    += $r->wrong_answers;

        $overall_not_answered     += $r->not_answered;

        $overall_time_spent_correct_answers     += $r->time_spent_on_correct_answers;

        $overall_time_spent_wrong_answers       += $r->time_spent_on_wrong_answers;

      }

      $overall_marks_dataset = [$overall_correct_answers, $overall_wrong_answers, $overall_not_answered];

      $overall_time_dataset = [$overall_time_spent_correct_answers, $overall_time_spent_wrong_answers];

      $overall_marks_data['type'] = 'doughnut';

        //horizontalBar, bar, polarArea, line, doughnut, pie

      $overall_marks_data['title'] =  getPhrase('overall_marks_analysis');

      $overall_marks_data['data']   = (object) array(

        'labels'            => $marks_labels,

        'dataset'           => $overall_marks_dataset,

        'dataset_label'     => getPhrase('overall_marks_analysis'),

        'bgcolor'           => $bgcolor,

        'border_color'      => $border_color

      );

      $data['right_bar_path']     = 'student.exams.subject-analysis.right-bar-performance-chart';

      $data['right_bar_data']     = array('right_bar_data' => (object)$overall_marks_data);

      $data['overall_data'][] = (object)$overall_marks_data;

      $data['subjects_display']   = $records;

      $data['active_class']       = 'analysis';

      $data['title']              = getPhrase('overall_subject_wise_analysis');

      $data['user']               = $user;

      $userid = $user->id;

      $data['layout']             = getLayout();

      // return view('student.exams.subject-analysis.subject-analysis', $data);

      $view_name = 'admin.student.exams.subject-analysis.subject-analysis';

      return view($view_name, $data);

    }

      /**

    * This method save exam attempt data for

    * restart exam if exam terminates abnormally

    * @param  Request $request [description]

    * @return [type]           [description]

    */

      public function saveResumeExamData(Request $request)

      {

        try{

          $data       = $request->jexamdata;

          $return_data['status'] = 'Fail';

          $return_data['message'] = 'Invalid Request';

          if(!count($data))

          {

            return json_encode($return_data);

          }

          $data = (object) $data[0];

          $question             = (object)$data->current_question;

          $quiz_id              = $data->quiz_id;

          $student_id           = $data->student_id;

          $current_question_id  = $question->id;

          $current_hour         = $data->hours;

          $current_minute       = $data->mins;

          $current_second       = $data->seconds;

          $exam_record   = QuizQuestions::where('student_id',$student_id)

          ->where('quiz_id',$quiz_id)

          ->where('is_exam_completed',0)

          ->first();

    // $exam_record->current_state = null;

    // $exam_record->save();

    // return $exam_record;

          $questions = [];

          if($exam_record)

          {

            if($exam_record->current_state)

              $questions = json_decode($exam_record->current_state);

            $questions = (array)$questions;

          }

          else

          {

            $questions[$question->id] = $question;

          }

          $questions = (array)$questions;

    // return json_encode($questions);

    // return json_encode($questions);

    // if(!array_key_exists($current_question_id, $questions))

    // {

          $questions[$question->id] = $question;

    // }

    // return $exam_record;

          $exam_record->current_state = json_encode($questions);

          $exam_record->current_hour = $current_hour;

          $exam_record->current_minute = $current_minute;

          $exam_record->current_second = $current_second;

          $exam_record->current_question_id = $current_question_id;

    // $exam_record->current_state   = $final_data;

          $exam_record->save();

          $return_data['status'] = 'ok';

          $return_data['message'] = 'status saved';

        }

        catch(Exception $ex) {

          $return_data['status'] = 'Fail';

          $return_data['message'] =  $ex->getMessage();

        }

        return json_encode($return_data);

      }

      public function finishResult () {
        $data = array();
        $view_name = 'admin.student.exams.finish-results-exam';
        return view($view_name, $data);
      }

    }