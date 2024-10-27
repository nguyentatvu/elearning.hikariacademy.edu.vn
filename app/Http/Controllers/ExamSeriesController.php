<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use \App;
use App\Http\Requests;
use App\Quiz;
use App\Topic;
use App\Subject;
use App\QuestionBank;
// use App\QuestionBank;
use App\QuizCategory;
use App\ExamSeries;
use Yajra\DataTables\DataTables;
use DB;
use Auth;
use Image;
use ImageSettings;
use File;
use Input;
class ExamSeriesController extends Controller
{
    public function __construct()
    {
      $this->middleware('auth');
    }
    /**
     * Course listing method
     * @return Illuminate\Database\Eloquent\Collection
     */
 
    
    public function index()
    {

      

      if(!checkRole(getUserGrade(9)))
      {
        prepareBlockUserMessage();
        return back();
      }
        $data['active_class']       = 'exams';
        $data['title']              = 'Bộ đề thi';
      // return view('exams.examseries.list', $data);
       $view_name = 'admin.exams.examseries.list';
        return view($view_name, $data);
    }
    public function checkExams($slug)
    {    
      $record = ExamSeries::getRecordWithSlug($slug);
      if($isValid = $this->isValidRecord($record))
        return redirect($isValid);
      $quizzes = DB::table('examseries_data')->where('examseries_id', $record->id)->get()->toArray();
      $arr = array();
      $i=1;
      foreach ($quizzes as $key => $value) {
        if($i == 1) {
          $arr['TV'] = DB::table('questionbank_quizzes')->join('questionbank', 'questionbank.id', '=', 'questionbank_quizzes.questionbank_id')->where('quize_id', $value->quiz_id)->select('questionbank.correct_answers')->get()->toArray();  
        } elseif ($i == 2) {
          $arr['NP'] = DB::table('questionbank_quizzes')->join('questionbank', 'questionbank.id', '=', 'questionbank_quizzes.questionbank_id')->where('quize_id', $value->quiz_id)->select('questionbank.correct_answers')->get()->toArray(); 
        } elseif ($i == 3) {
          $arr['Nghe'] = DB::table('questionbank_quizzes')->join('questionbank', 'questionbank.id', '=', 'questionbank_quizzes.questionbank_id')->where('quize_id', $value->quiz_id)->select('questionbank.correct_answers')->get()->toArray(); 
        }
        $i++;
      }
      $data['examseries'] = $record;
      $data['anwser'] = $arr;
      $data['active_class']       = 'exams';
      $data['title']              = 'Đáp án bộ đề thi';
      $view_name = 'admin.exams.examseries.check';
      return view($view_name, $data);
    }
    /**
     * This method returns the datatables data to view
     * @return [type] [description]
     */
    public function getDatatable()
    {
      if(!checkRole(getUserGrade(9)))
      {
        prepareBlockUserMessage();
        return back();
      }
        $records = array();
            $records = ExamSeries::select(['title', 'image', 'category_id', 'slug', 'id', 'updated_at'])
            ->orderBy('updated_at', 'desc');
        return DataTables::of($records)
        ->addColumn('action', function ($records) {
          $link_data = '<div class="dropdown more">
                        <a id="dLabel" type="button" class="more-dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="mdi mdi-dots-vertical"></i>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-right" aria-labelledby="dLabel">
                        <li><a href="/exams/pdf2/'.$records->slug.'" target="_blank"><i class="fa fa-download"></i>Download PDF</a></li>
                        <li><a href="/exams/view-exam-series/'.$records->slug.'" target="_blank"><i class="fa fa-pencil"></i>Kiểm tra đề thi</a></li>
                        <li><a href="/exams/view-exam-series-chart/'.$records->slug.'" target="_blank"><i class="fa fa-line-chart"></i>Đồ thị độ khó</a></li>
                        <li><a href="'.URL_EXAM_SERIES_RATE_SERIES.$records->slug.'" target="_blank"><i class="fa fa-spinner"></i>Xem đánh giá</a></li>
                           <li><a href="'.URL_EXAM_SERIES_UPDATE_SERIES.$records->slug.'" target="_blank"><i class="fa fa-pencil-square-o"></i>Cập nhật đề thi</a></li>
                           <li><a href="'.URL_EXAM_SERIES_CHECK_EXAMS.$records->slug.'" target="_blank"><i class="fa fa-check"></i>Xem đáp án</a></li>
                           <li><a href="'.URL_EXAM_SERIES_EDIT.$records->slug.'" target="_blank"><i class="fa fa-pencil"></i>Sửa bộ đề</a></li>';
                           $temp = '';
                           /*if(checkRole(getUserGrade(1))) {
                            $temp .= ' <li><a href="javascript:void(0);" onclick="deleteRecord(\''.$records->slug.'\');"><i class="fa fa-trash"></i>Xóa</a></li>';
                            }*/
                            $temp .= ' <li><a href="javascript:void(0);" onclick="deleteRecord(\''.$records->slug.'\');"><i class="fa fa-trash"></i>Xóa</a></li>';
                    $temp .='</ul></div>';
                    $link_data .=$temp;
          return $link_data;
            })
        ->editColumn('title', function($records)
        {
          return '<a href="'.URL_EXAM_SERIES_UPDATE_SERIES.$records->slug.'">'. change_furigana_admin($records->title).'</a>';
        })

        ->editColumn('image', function($records)
        {
          $image_path = '/public/uploads/exams/series/n' . $records->category_id . '.png';
            return '<img src="'.$image_path.'" height="60" width="60"  />';
        })
        ->rawColumns(['action', 'title', 'image'])
        ->removeColumn('id')
        ->removeColumn('slug')
        ->removeColumn('updated_at')
        ->removeColumn('category_id')
        ->make();
    }
    /**
     * This method loads the create view
     * @return void
     */
    public function create()
    {
      if(!checkRole(getUserGrade(9)))
      {
        prepareBlockUserMessage();
        return back();
      }
      $data['record']           = FALSE;
        $data['categories']         = array_pluck(QuizCategory::all(), 'category', 'id');
      $data['active_class']       = 'exams';
        $data['title']              = getPhrase('add_exam_series');
      // return view('exams.examseries.add-edit', $data);
        $view_name = 'admin.exams.examseries.add-edit';
        return view($view_name, $data);
    }
    /**
     * This method loads the edit view based on unique slug provided by user
     * @param  [string] $slug [unique slug of the record]
     * @return [view with record]       
     */
    public function edit($slug)
    {
      if(!checkRole(getUserGrade(9)))
      {
        prepareBlockUserMessage();
        return back();
      }
      $record = ExamSeries::getRecordWithSlug($slug);
      if($isValid = $this->isValidRecord($record))
        return redirect($isValid);
      // dd($record);
      $data['record']           = $record;
      $data['active_class']     = 'exams';
      $data['settings']         = FALSE;
      $data['categories']         = array_pluck(QuizCategory::all(), 'category', 'id');
      $data['title']            = getPhrase('edit_series');
      // return view('exams.examseries.add-edit', $data);
      $view_name = 'admin.exams.examseries.add-edit';
      return view($view_name, $data);
    }
    /**
     * Update record based on slug and reuqest
     * @param  Request $request [Request Object]
     * @param  [type]  $slug    [Unique Slug]
     * @return void
     */
    public function update(Request $request, $slug)
    {
      if(!checkRole(getUserGrade(9)))
      {
        prepareBlockUserMessage();
        return back();
      }
      $record = ExamSeries::getRecordWithSlug($slug);
     $rules = [
         'title'               => 'bail|required|max:40' ,
            ];
         /**
        * Check if the title of the record is changed, 
        * if changed update the slug value based on the new title
        */
       $name = $request->title;
        if($name != $record->title)
            $record->slug = createSlug($name);
       //Validate the overall request
       $this->validate($request, $rules);
      $record->title        = $name;
        $record->slug         = createSlug($name);
        $record->is_paid      = $request->is_paid;
        $record->category_id      = $request->category_id;
        $record->validity     = -1;
        $record->cost       = 0;
        if($request->is_paid) {
          // $record->validity   = $request->validity;
          // $record->cost     = $request->cost;
       }
        $record->total_exams    = $request->total_exams;
        $record->total_questions  = $request->total_questions;
        $record->short_description  = $request->short_description;
        $record->description    = $request->description;
        $record->start_date   = $request->start_date;
        $record->end_date   = $request->end_date;
        $record->record_updated_by  = Auth::user()->id;
        $record->save();
        $file_name = 'image';
        if ($request->hasFile($file_name))
        {
            $rules = array( $file_name => 'mimes:jpeg,jpg,png,gif|max:10000' );
            $this->validate($request, $rules);
        $examSettings = getExamSettings();
          $path = $examSettings->seriesImagepath;
          $this->deleteFile($record->image, $path);
            $record->image      = $this->processUpload($request, $record,$file_name);
              $record->save();
        }
        flash('success','record_updated_successfully', 'success');
      return redirect(URL_EXAM_SERIES);
    }



    /**
     * This method adds record to DB
     * @param  Request $request [Request Object]
     * @return void
     */


    public function store(Request $request)
    {
      if(!checkRole(getUserGrade(9)))
      {
        prepareBlockUserMessage();
        return back();
      }
      $rules = [
         'title'              => 'bail|required|max:400' ,
          ];
        $this->validate($request, $rules);
      $sodetudong = $request->exams_auto;
      for ($de=1; $de <=$sodetudong ; $de++) {
        
        $record               = new ExamSeries();
        $name                 =  $request->title . ' - ' . $de;
        $record->title        = $name;
        $record->slug         = createSlug($name);
        $record->is_paid      = $request->is_paid;
        $record->validity     = -1;
        $record->cost         = 0;
        if($request->is_paid) {
          // $record->validity   = $request->validity;
          // $record->cost       = $request->cost;
        }
        $record->total_exams  = $request->total_exams;
        $record->total_questions  = $request->total_questions;
        $record->category_id  = $request->category_id;
        $record->short_description  = $request->short_description;
        $record->description  = $request->description;
        $record->start_date   = $request->start_date;
        $record->end_date     = $request->end_date;
        $record->record_updated_by  = Auth::user()->id;
        $record->total_exams  = 3;
        $record->save();
        // Add new bài thi theo category
        $fori = array(2,3,1);
        if ($request->category_id == 3) {
            //add 3 bài thi
           foreach ($fori as $key_fori => $i) {
              $dueration = 0;
              if ($i == 1) {
                 $dueration = 70;
                 $dethi = ' - Nghe (N3)';
              }
              if ($i == 2) {
                 $dueration = 30;
                 $dethi = ' - TV (N3)';
              }
              if ($i == 3) {
                 $dueration = 70;
                 $dethi = ' - NP (N3)';
              }
              $record_quiz = new Quiz();
              $name_quiz = $name . $dethi;
              $record_quiz->title        = $name_quiz;
              $record_quiz->slug         = createSlug($name_quiz);
              $record_quiz->category_id  = $request->category_id;
              $record_quiz->type         = $i;
              $record_quiz->dueration    = $dueration;
              $record_quiz->total_marks  = 0;
              $record_quiz->pass_percentage  = 50;
              $record_quiz->tags         = '';
              $record_quiz->is_paid      = $request->is_paid;
              $record_quiz->cost         = 0;
              $record_quiz->validity     = -1;
              $record_quiz->publish_results_immediately = 1;
              $record_quiz->having_negative_mark = 1;
              $record_quiz->negative_mark = '';
              $record_quiz->start_date = $request->start_date;
              $record_quiz->end_date = $request->end_date;
              $record_quiz->instructions_page_id = 1;
              $record_quiz->show_in_front = 1;
              $record_quiz->description    = '';
              $record_quiz->record_updated_by  = Auth::user()->id;
              $record_quiz->exam_type          = 'NSNT';
              $record_quiz->has_language       = 0;
              $record_quiz->save(); 
              //Insert examseries_data
              $temp_examseries_data = array();
              $temp_examseries_data['examseries_id']       = $record->id;
              $temp_examseries_data['quiz_id']  = $record_quiz->id;
              DB::table('examseries_data')->insert($temp_examseries_data);
              //Add Question to Quizz
              if ($i == 1) {
                 $questions = array();
                 $questions = QuestionBank::where('subject_id','=',13)->where('topic_id','=',35)->inRandomOrder()->take(6)->get()->toArray();
                 $questions = array_merge($questions,QuestionBank::where('subject_id','=',14)->where('topic_id','=',36)->inRandomOrder()->take(6)->get()->toArray());
                 $questions = array_merge($questions,QuestionBank::where('subject_id','=',15)->where('topic_id','=',37)->inRandomOrder()->take(3)->get()->toArray());
                 $questions = array_merge($questions,QuestionBank::where('subject_id','=',16)->where('topic_id','=',38)->inRandomOrder()->take(4)->get()->toArray());
                 $questions = array_merge($questions,QuestionBank::where('subject_id','=',17)->where('topic_id','=',61)->inRandomOrder()->take(9)->get()->toArray());
                 $marks = 0;
                 $questions_to_update = array();
                 foreach ($questions as $key => $q) {
                   $temp = array();
                   $temp['subject_id']       = $q['subject_id'];
                   $temp['questionbank_id']  = $q['id'];
                   //get last insert id quizz
                   $temp['quize_id']         = $record_quiz->id;
                   $temp['marks']            = $q['marks'];
                   $marks                   += $q['marks'];
                   array_push($questions_to_update, $temp);
                 }
                 /*foreach ($questions_to_update as $key_question => $value_question) {
                    $questionbank_select =  DB::table('questionbank_input')->where('id', '=', $value_question['questionbank_id'])->first(); 
                    $questionbank_select->questionbank_id = $questionbank_select->id;
                    unset($questionbank_select->id);
                    $questionbank_insert = DB::table('questionbank')->insertGetId((array)$questionbank_select);
                    $questions_to_update[$key_question]['questionbank_id'] = $questionbank_insert;
                 }*/
                 DB::table('questionbank_quizzes')->where('quize_id', '=', $record_quiz->id)->delete();
                 //Insert New Questions
                 DB::table('questionbank_quizzes')->insert($questions_to_update);
                 $record_quiz->total_marks       = $marks;
                 $record_quiz->total_questions   = 28;
                 $record_quiz->save();
              }
              if ($i == 2) {
                 $questions = array();
                 $questions_1 = array();
                 // 8
                 // 192 - Kanji 2 chữ  
                 $questions_1 = QuestionBank::where('subject_id','=',1)->where('topic_id','=',1)->inRandomOrder()->take(5)->get()->toArray();
                 // 247 - Kanji N 
                 $questions_1 = array_merge($questions_1,QuestionBank::where('subject_id','=',1)->where('topic_id','=',2)->inRandomOrder()->take(1)->get()->toArray());
                 // 191 - Kanji 1 chữ - A   - 1              
                 $questions_1 = array_merge($questions_1,QuestionBank::where('subject_id','=',1)->where('topic_id','=',3)->inRandomOrder()->take(1)->get()->toArray());
                 $questions_1 = array_merge($questions_1,QuestionBank::where('subject_id','=',1)->where('topic_id','=',4)->inRandomOrder()->take(1)->get()->toArray());
                 shuffle ($questions_1);

                 $questions_2 = array();
                 // 6
                 // 198 - Kanji 2 chữ       - 1              
                 $questions_2 = QuestionBank::where('subject_id','=',2)->where('topic_id','=',5)->inRandomOrder()->take(3)->get()->toArray();
                 // Kanji 1 chữ - N
                 $questions_2 = array_merge($questions_2,QuestionBank::where('subject_id','=',2)->where('topic_id','=',6)->inRandomOrder()->take(1)->get()->toArray());
                 // Kanji 1 chữ - A
                 $questions_2 = array_merge($questions_2,QuestionBank::where('subject_id','=',2)->where('topic_id','=',7)->inRandomOrder()->take(1)->get()->toArray());
                 // Kanji 1 chữ - V
                 $questions_2 = array_merge($questions_2,QuestionBank::where('subject_id','=',2)->where('topic_id','=',8)->inRandomOrder()->take(1)->get()->toArray());
                 shuffle ($questions_2);

                 $questions = array_merge($questions_1, $questions_2);


                 $questions_3 = array();
                 // 11
                 // Kanji 2 chữ
                 $questions_3 = QuestionBank::where('subject_id','=',3)->where('topic_id','=',9)->inRandomOrder()->take(4)->get()->toArray();
                 // Kanji 1 chữ N
                 $questions_3 = array_merge($questions_3,QuestionBank::where('subject_id','=',3)->where('topic_id','=',10)->inRandomOrder()->take(1)->get()->toArray());
                 // Kanji 1 chữ - A
                 $questions_3 = array_merge($questions_3,QuestionBank::where('subject_id','=',3)->where('topic_id','=',11)->inRandomOrder()->take(1)->get()->toArray());
                 // V 
                 $questions_3 = array_merge($questions_3,QuestionBank::where('subject_id','=',3)->where('topic_id','=',12)->inRandomOrder()->take(3)->get()->toArray());
                 // F
                 $questions_3 = array_merge($questions_3,QuestionBank::where('subject_id','=',3)->where('topic_id','=',13)->inRandomOrder()->take(1)->get()->toArray());
                 // 5
                 $questions_3 = array_merge($questions_3,QuestionBank::where('subject_id','=',3)->where('topic_id','=',14)->inRandomOrder()->take(1)->get()->toArray());
                 shuffle ($questions_3);
                 $questions = array_merge($questions, $questions_3);

                 
                 // 5 Từ ghép
                 $questions_4 = array();
                // F
                 $questions_4 = QuestionBank::where('subject_id','=',4)->where('topic_id','=',16)->inRandomOrder()->take(1)->get()->toArray();
                 // Kanji 2 chữ
                 $questions_4 = array_merge($questions_4,QuestionBank::where('subject_id','=',4)->where('topic_id','=',17)->inRandomOrder()->take(2)->get()->toArray());
                 // V
                 $questions_4 = array_merge($questions_4,QuestionBank::where('subject_id','=',4)->where('topic_id','=',18)->inRandomOrder()->take(1)->get()->toArray());
                 // A
                 $questions_4 = array_merge($questions_4,QuestionBank::where('subject_id','=',4)->where('topic_id','=',274)->inRandomOrder()->take(1)->get()->toArray());
                 shuffle ($questions_4);
                 $questions = array_merge($questions, $questions_4);

                 
                 // 5
                 $questions_5 = array();
                 // kj 2 chữ
                 $questions_5 = QuestionBank::where('subject_id','=',5)->where('topic_id','=',19)->inRandomOrder()->take(1)->get()->toArray();
                 // V
                 $questions_5 = array_merge($questions_5,QuestionBank::where('subject_id','=',5)->where('topic_id','=',20)->inRandomOrder()->take(1)->get()->toArray());
                 // F
                 $questions_5 = array_merge($questions_5,QuestionBank::where('subject_id','=',5)->where('topic_id','=',21)->inRandomOrder()->take(1)->get()->toArray());
                 // A
                 $questions_5 = array_merge($questions_5,QuestionBank::where('subject_id','=',5)->where('topic_id','=',22)->inRandomOrder()->take(1)->get()->toArray());
                 // N
                 $questions_5 = array_merge($questions_5,QuestionBank::where('subject_id','=',5)->where('topic_id','=',278)->inRandomOrder()->take(1)->get()->toArray());
                 shuffle ($questions_5);
                 $questions = array_merge($questions, $questions_5);


                 $marks = 0;
                 $questions_to_update = array();
                 foreach ($questions as $key => $q) {
                   $temp = array();
                   $temp['subject_id']       = $q['subject_id'];
                   $temp['questionbank_id']  = $q['id'];
                   //get last insert id quizz
                   $temp['quize_id']         = $record_quiz->id;
                   $temp['marks']            = $q['marks'];
                   $marks                   += $q['marks'];
                   array_push($questions_to_update, $temp);
                 }
                 /*//Đổi câu trả lời add question to questionbank
                 foreach ($questions_to_update as $key_question => $value_question) {
                    $questionbank_select =  DB::table('questionbank_input')->where('id', '=', $value_question['questionbank_id'])->first(); 
                    $answers = json_decode ($questionbank_select->answers);
                    $index = $questionbank_select->correct_answers - 1;
                    $answers1 = shuffle_assoc($answers);
                    $answers_new = json_encode(array_values($answers1));
                    $questionbank_select->answers = $answers_new;
                    $caudungmoi =  array_search($index,array_keys($answers1))  + 1;
                    $questionbank_select->questionbank_id = $questionbank_select->id;
                    unset($questionbank_select->id);
                    $questionbank_select->correct_answers = $caudungmoi;
                    $questionbank_insert = DB::table('questionbank')->insertGetId((array)$questionbank_select);
                    $questions_to_update[$key_question]['questionbank_id'] = $questionbank_insert;
                 }*/
                 
                 DB::table('questionbank_quizzes')->where('quize_id', '=', $record_quiz->id)->delete();
                 //Insert New Questions
                 DB::table('questionbank_quizzes')->insert($questions_to_update);
                 $record_quiz->total_marks       = $marks;
                 $record_quiz->total_questions   = 35;
                 $record_quiz->save();
              }
              if ($i == 3) {
                 $questions = array();
                 //@1 13
                 //Trợ từ
                 $questions = QuestionBank::where('subject_id','=',6)->where('topic_id','=',23)->inRandomOrder()->take(10)->get()->toArray();
                 // Kinh Ngữ chưa có trong N3
                 $questions = array_merge($questions,QuestionBank::where('subject_id','=',6)->where('topic_id','=',24)->inRandomOrder()->take(1)->get()->toArray());
                 // F
                 $questions = array_merge($questions,QuestionBank::where('subject_id','=',6)->where('topic_id','=',25)->inRandomOrder()->take(1)->get()->toArray());
                 // Chia thể V/A
                 $questions = array_merge($questions,QuestionBank::where('subject_id','=',6)->where('topic_id','=',26)->inRandomOrder()->take(1)->get()->toArray());
                 shuffle ($questions);

                 //@2 5
                 // Lắp ghép câu
                 $questions = array_merge($questions,QuestionBank::where('subject_id','=',7)->where('topic_id','=',27)->inRandomOrder()->take(5)->get()->toArray());
                 // @3 5
                 // NP trong đoạn
                 $topic_ngu_phap_trong_doan = Topic::where('subject_id','=',8)->where('parent_id','=',28)->inRandomOrder()->pluck('id')->first();
                 $questions = array_merge($questions,QuestionBank::where('subject_id','=',8)->where('topic_id','=',$topic_ngu_phap_trong_doan)->take(5)->get()->toArray());

                 // @4 3
                 /*$questions = array_merge($questions,QuestionBank::where('subject_id','=',9)->where('explanation','like','%(1)%')->inRandomOrder()->take(1)->get()->toArray());
                 $questions = array_merge($questions,QuestionBank::where('subject_id','=',9)->where('explanation','like','%(2)%')->inRandomOrder()->take(1)->get()->toArray());
                 $questions = array_merge($questions,QuestionBank::where('subject_id','=',9)->where('explanation','like','%(3)%')->inRandomOrder()->take(1)->get()->toArray());
                 $questions = array_merge($questions,QuestionBank::where('subject_id','=',9)->where('explanation','like','%(4)%')->inRandomOrder()->take(1)->get()->toArray());*/

                 $questions_1 = QuestionBank::where('subject_id','=',9)->inRandomOrder()->take(1)->get()->toArray();
                 $id_questions_1 = $questions_1[0]['id'];
                 $questions = array_merge($questions, $questions_1);
                 $questions_2 = QuestionBank::where('subject_id','=',9)->wherenotin('id',[$id_questions_1])->inRandomOrder()->take(1)->get()->toArray();
                 $id_questions_2 = $questions_2[0]['id'];
                 $questions = array_merge($questions, $questions_2);
                 $questions_3 = QuestionBank::where('subject_id','=',9)->wherenotin('id',[$id_questions_1,$id_questions_2])->inRandomOrder()->take(1)->get()->toArray();
                 $id_questions_3 = $questions_3[0]['id'];
                 $questions = array_merge($questions, $questions_3);
                 $questions_4 = QuestionBank::where('subject_id','=',9)->wherenotin('id',[$id_questions_1,$id_questions_2,$id_questions_3])->inRandomOrder()->take(1)->get()->toArray();
                 $questions = array_merge($questions, $questions_4);


                 // @5 4
                 $topic_hieu_nd_1 = Topic::where('subject_id','=',10)->where('parent_id','=',32)->inRandomOrder()->pluck('id')->first();
                 $questions = array_merge($questions,QuestionBank::where('subject_id','=',10)->where('topic_id','=',$topic_hieu_nd_1)->take(3)->get()->toArray());

                 $topic_hieu_nd_2 = Topic::where('subject_id','=',10)->where('parent_id','=',32)->wherenotin('id', [$topic_hieu_nd_1])->inRandomOrder()->pluck('id')->first();
                 $questions = array_merge($questions,QuestionBank::where('subject_id','=',10)->where('topic_id','=',$topic_hieu_nd_2)->take(3)->get()->toArray());


                 // @6 1
                 $topic_tim_kiem = Topic::where('subject_id','=',11)->where('parent_id','=',33)->inRandomOrder()->pluck('id')->first();
                 $questions = array_merge($questions,QuestionBank::where('subject_id','=',11)->where('topic_id','=',$topic_tim_kiem)->take(4)->get()->toArray());
                 // @7 1
                 $topic_tim_kiem = Topic::where('subject_id','=',12)->where('parent_id','=',34)->inRandomOrder()->pluck('id')->first();
                 $questions = array_merge($questions,QuestionBank::where('subject_id','=',12)->where('topic_id','=',$topic_tim_kiem)->take(2)->get()->toArray());
                 $marks = 0;
                 $questions_to_update = array();
                 foreach ($questions as $key => $q) {
                   $temp = array();
                   $temp['subject_id']       = $q['subject_id'];
                   $temp['questionbank_id']  = $q['id'];
                   //get last insert id quizz
                   $temp['quize_id']         = $record_quiz->id;
                   $temp['marks']            = $q['marks'];
                   $marks                   += $q['marks'];
                   array_push($questions_to_update, $temp);
                 }
                 /*//Đổi câu trả lời add question to questionbank
                 foreach ($questions_to_update as $key_question => $value_question) {
                    $questionbank_select =  DB::table('questionbank_input')->where('id', '=', $value_question['questionbank_id'])->first(); 
                    $answers = json_decode ($questionbank_select->answers);
                    $index = $questionbank_select->correct_answers - 1;
                    $answers1 = shuffle_assoc($answers);
                    $answers_new = json_encode(array_values($answers1));
                    $questionbank_select->answers = $answers_new;
                    $caudungmoi =  array_search($index,array_keys($answers1))  + 1;
                    $questionbank_select->questionbank_id = $questionbank_select->id;
                    unset($questionbank_select->id);
                    $questionbank_select->correct_answers = $caudungmoi;
                    $questionbank_insert = DB::table('questionbank')->insertGetId((array)$questionbank_select);
                    $questions_to_update[$key_question]['questionbank_id'] = $questionbank_insert;
                 }*/
                 DB::table('questionbank_quizzes')->where('quize_id', '=', $record_quiz->id)->delete();
                 //Insert New Questions
                 DB::table('questionbank_quizzes')->insert($questions_to_update);
                 $record_quiz->total_marks       = $marks;
                 $record_quiz->total_questions   = 39;
                 $record_quiz->save();
              }
            }//#end for 
            // add total_questions
            $record->total_questions  = 102;
            $record->save();
        } //# end if
        if ($request->category_id == 4) {
            //add 3 bài thi
            foreach ($fori as $key_fori => $i) { 
              $dueration = 0;
              if ($i == 1) {
                 $dueration = 60;
                 $dethi = ' - Nghe (N4)';
              }
              if ($i == 2) {
                 $dueration = 30;
                 $dethi = ' - TV (N4)';
              }
              if ($i == 3) {
                 $dueration = 60;
                 $dethi = ' - NP (N4)';
              }
              $record_quiz = new Quiz();
              $name_quiz = $name . $dethi;
              $record_quiz->title        = $name_quiz;
              $record_quiz->slug         = createSlug($name_quiz);
              $record_quiz->category_id  = $request->category_id;
              $record_quiz->type         = $i;
              $record_quiz->dueration    = $dueration;
              $record_quiz->total_marks  = 0;
              $record_quiz->pass_percentage  = 50;
              $record_quiz->tags         = '';
              $record_quiz->is_paid      = $request->is_paid;
              $record_quiz->cost         = 0;
              $record_quiz->validity     = -1;
              $record_quiz->publish_results_immediately = 1;
              $record_quiz->having_negative_mark = 1;
              $record_quiz->negative_mark = '';
              $record_quiz->start_date = $request->start_date;
              $record_quiz->end_date = $request->end_date;
              $record_quiz->instructions_page_id = 1;
              $record_quiz->show_in_front = 1;
              $record_quiz->description    = '';
              $record_quiz->record_updated_by  = Auth::user()->id;
              $record_quiz->exam_type          = 'NSNT';
              $record_quiz->has_language       = 0;
              $record_quiz->save(); 
              //Insert examseries_data
              $temp_examseries_data = array();
              $temp_examseries_data['examseries_id']       = $record->id;
              $temp_examseries_data['quiz_id']  = $record_quiz->id;
              DB::table('examseries_data')->insert($temp_examseries_data);
              //Add Question to Quizz
              if ($i == 1) {
                $questions = array();
                $questions = QuestionBank::where('subject_id','=',61)->where('topic_id','=',236)->inRandomOrder()->take(8)->get()->toArray();
                $questions = array_merge($questions,QuestionBank::where('subject_id','=',62)->where('topic_id','=',237)->inRandomOrder()->take(7)->get()->toArray());
                $questions = array_merge($questions,QuestionBank::where('subject_id','=',63)->where('topic_id','=',238)->inRandomOrder()->take(5)->get()->toArray());
                $questions = array_merge($questions,QuestionBank::where('subject_id','=',64)->where('topic_id','=',239)->inRandomOrder()->take(8)->get()->toArray());
                $marks = 0;
                $questions_to_update = array();
                foreach ($questions as $key => $q) {
                  $temp = array();
                  $temp['subject_id']       = $q['subject_id'];
                  $temp['questionbank_id']  = $q['id'];
                  //get last insert id quizz
                  $temp['quize_id']         = $record_quiz->id;
                  $temp['marks']            = $q['marks'];
                  $marks                   += $q['marks'];
                  array_push($questions_to_update, $temp);
                }
                /*foreach ($questions_to_update as $key_question => $value_question) {
                   $questionbank_select =  DB::table('questionbank_input')->where('id', '=', $value_question['questionbank_id'])->first(); 
                   $questionbank_select->questionbank_id = $questionbank_select->id;
                   unset($questionbank_select->id);
                   $questionbank_insert = DB::table('questionbank')->insertGetId((array)$questionbank_select);
                   $questions_to_update[$key_question]['questionbank_id'] = $questionbank_insert;
                }*/
                DB::table('questionbank_quizzes')->where('quize_id', '=', $record_quiz->id)->delete();
                //Insert New Questions
                DB::table('questionbank_quizzes')->insert($questions_to_update);
                $record_quiz->total_marks       = $marks;
                $record_quiz->total_questions   = 28;
                $record_quiz->save();
              }
              if ($i == 2) {
                 $questions = array();
                 
                 //@1 9
                 $questions_1 = array();
                 // 192 - Kanji 2 chữ       - 7
                 $questions_1 = QuestionBank::where('subject_id','=',50)->where('topic_id','=',192)->inRandomOrder()->take(5)->get()->toArray();
                 // 247 - Kanji 3 chữ       - 1
                 $questions_1 = array_merge($questions_1,QuestionBank::where('subject_id','=',50)->where('topic_id','=',247)->inRandomOrder()->take(1)->get()->toArray());
                 // 191 - Kanji 1 chữ - V   - 1              
                 $questions_1 = array_merge($questions_1,QuestionBank::where('subject_id','=',50)->where('topic_id','=',193)->inRandomOrder()->take(1)->get()->toArray());
                 // 191 - Kanji 1 chữ - V   - 1              
                 $questions_1 = array_merge($questions_1,QuestionBank::where('subject_id','=',50)->where('topic_id','=',194)->inRandomOrder()->take(1)->get()->toArray());
                 // 191 - Kanji 1 chữ - V   - 1              
                 $questions_1 = array_merge($questions_1,QuestionBank::where('subject_id','=',50)->where('topic_id','=',191)->inRandomOrder()->take(1)->get()->toArray());
                 shuffle ($questions_1);

                
                 //@2 6
                 $questions_2 = array();
                 // 198 - Kanji 2 chữ       - 1              
                 $questions_2 = QuestionBank::where('subject_id','=',51)->where('topic_id','=',198)->inRandomOrder()->take(3)->get()->toArray();
                 // Kanji 1 chữ - N
                 $questions_2 = array_merge($questions_2,QuestionBank::where('subject_id','=',51)->where('topic_id','=',195)->inRandomOrder()->take(1)->get()->toArray());
                 // Kanji 1 chữ - A
                 $questions_2 = array_merge($questions_2,QuestionBank::where('subject_id','=',51)->where('topic_id','=',196)->inRandomOrder()->take(1)->get()->toArray());
                 // Kanji 1 chữ - V
                 $questions_2 = array_merge($questions_2,QuestionBank::where('subject_id','=',51)->where('topic_id','=',197)->inRandomOrder()->take(1)->get()->toArray());
                 shuffle ($questions_2);
                 $questions = array_merge($questions_1, $questions_2);


                 //@3 9
                 $questions_3 = array();
                 // Kanji 1 chữ - N
                 $questions_3 = QuestionBank::where('subject_id','=',52)->where('topic_id','=',203)->inRandomOrder()->take(2)->get()->toArray();
                 // Kanji 1 chữ - A
                 $questions_3 = array_merge($questions_3,QuestionBank::where('subject_id','=',52)->where('topic_id','=',204)->inRandomOrder()->take(1)->get()->toArray());
                 // Kanji 1 chữ - V
                 $questions_3 = array_merge($questions_3,QuestionBank::where('subject_id','=',52)->where('topic_id','=',205)->inRandomOrder()->take(4)->get()->toArray());
                 // F
                 $questions_3 = array_merge($questions_3,QuestionBank::where('subject_id','=',52)->where('topic_id','=',206)->inRandomOrder()->take(1)->get()->toArray());
                 // Katakana
                 $questions_3 = array_merge($questions_3,QuestionBank::where('subject_id','=',52)->where('topic_id','=',199)->inRandomOrder()->take(1)->get()->toArray());
                 shuffle ($questions_3);
                 $questions = array_merge($questions, $questions_3);


                 //@4 5 đòng nghĩa
                 $questions_4 = array();
                 // N
                 $questions_4 = QuestionBank::where('subject_id','=',53)->where('topic_id','=',207)->inRandomOrder()->take(1)->get()->toArray();
                 // V
                 $questions_4 = array_merge($questions_4,QuestionBank::where('subject_id','=',53)->where('topic_id','=',209)->inRandomOrder()->take(2)->get()->toArray());
                 // A
                 $questions_4 = array_merge($questions_4,QuestionBank::where('subject_id','=',53)->where('topic_id','=',208)->inRandomOrder()->take(1)->get()->toArray());
                 // F
                 $questions_4 = array_merge($questions_4,QuestionBank::where('subject_id','=',53)->where('topic_id','=',210)->inRandomOrder()->take(1)->get()->toArray());
                 shuffle ($questions_4);
                 $questions = array_merge($questions, $questions_4);

                 // 5
                 $questions_5 = array();
                 // V
                 $questions_5 = QuestionBank::where('subject_id','=',54)->where('topic_id','=',200)->inRandomOrder()->take(1)->get()->toArray();
                 // F
                 $questions_5 = array_merge($questions_5,QuestionBank::where('subject_id','=',54)->where('topic_id','=',284)->inRandomOrder()->take(1)->get()->toArray());
                 // A
                 $questions_5 = array_merge($questions_5,QuestionBank::where('subject_id','=',54)->where('topic_id','=',201)->inRandomOrder()->take(1)->get()->toArray());
                 // N
                 $questions_5 = array_merge($questions_5,QuestionBank::where('subject_id','=',54)->where('topic_id','=',202)->inRandomOrder()->take(2)->get()->toArray());
                 shuffle ($questions_5);
                 $questions = array_merge($questions, $questions_5);


                 $marks = 0;
                 $questions_to_update = array();
                 foreach ($questions as $key => $q) {
                   $temp = array();
                   $temp['subject_id']       = $q['subject_id'];
                   $temp['questionbank_id']  = $q['id'];
                   //get last insert id quizz
                   $temp['quize_id']         = $record_quiz->id;
                   $temp['marks']            = $q['marks'];
                   $marks                   += $q['marks'];
                   array_push($questions_to_update, $temp);
                 }
                 /*//Đổi câu trả lời add question to questionbank
                 foreach ($questions_to_update as $key_question => $value_question) {
                    $questionbank_select =  DB::table('questionbank_input')->where('id', '=', $value_question['questionbank_id'])->first(); 
                    $answers = json_decode ($questionbank_select->answers);
                    $index = $questionbank_select->correct_answers - 1;
                    $answers_random = shuffle_assoc($answers);
                    $answers_new = json_encode(array_values($answers_random));
                    $questionbank_select->answers = $answers_new;
                    $caudungmoi =  array_search($index,array_keys($answers_random))  + 1;
                    $questionbank_select->questionbank_id = $questionbank_select->id;
                    unset($questionbank_select->id);
                    $questionbank_select->correct_answers = $caudungmoi;
                    $questionbank_insert = DB::table('questionbank')->insertGetId((array)$questionbank_select);
                    $questions_to_update[$key_question]['questionbank_id'] = $questionbank_insert;
                 }*/
                 DB::table('questionbank_quizzes')->where('quize_id', '=', $record_quiz->id)->delete();
                 //Insert New Questions
                 DB::table('questionbank_quizzes')->insert($questions_to_update);
                 $record_quiz->total_marks       = $marks;
                 $record_quiz->total_questions   = 34;
                 $record_quiz->save();
              }
              if ($i == 3) {
                 $questions = array();
                 //@1 15
                 //Trợ từ
                 $questions = QuestionBank::where('subject_id','=',55)->where('topic_id','=',211)->inRandomOrder()->take(3)->get()->toArray();
                 // Nghi vấn
                 // $questions = array_merge($questions,QuestionBank::where('subject_id','=',55)->where('topic_id','=',213)->inRandomOrder()->take(1)->get()->toArray());
                 // Ý nghĩa
                 $questions = array_merge($questions,QuestionBank::where('subject_id','=',55)->where('topic_id','=',221)->inRandomOrder()->take(7)->get()->toArray());
                 // F
                 // $questions = array_merge($questions,QuestionBank::where('subject_id','=',55)->where('topic_id','=',214)->inRandomOrder()->take(2)->get()->toArray());
                 // Chia thể V/A
                 $questions = array_merge($questions,QuestionBank::where('subject_id','=',55)->where('topic_id','=',212)->inRandomOrder()->take(4)->get()->toArray());
                 // Ngữ cảnh
                 $questions = array_merge($questions,QuestionBank::where('subject_id','=',55)->where('topic_id','=',215)->inRandomOrder()->take(1)->get()->toArray());
                 shuffle ($questions);


                 //@2 5
                 // Lắp ghép câu
                 $questions = array_merge($questions,QuestionBank::where('subject_id','=',56)->where('topic_id','=',216)->inRandomOrder()->take(5)->get()->toArray());
                 // @3 5
                 // NP trong đoạn
                 $topic_ngu_phap_trong_doan = Topic::where('subject_id','=',57)->where('parent_id','=',217)->inRandomOrder()->pluck('id')->first();
                 $questions = array_merge($questions,QuestionBank::where('subject_id','=',57)->where('topic_id','=',$topic_ngu_phap_trong_doan)->take(5)->get()->toArray());
                 // @4 4
                 /*$questions = array_merge($questions,QuestionBank::where('subject_id','=',58)->where('explanation','like','%(1)%')->inRandomOrder()->take(1)->get()->toArray());
                 $questions = array_merge($questions,QuestionBank::where('subject_id','=',58)->where('explanation','like','%(2)%')->inRandomOrder()->take(1)->get()->toArray());
                 $questions = array_merge($questions,QuestionBank::where('subject_id','=',58)->where('explanation','like','%(3)%')->inRandomOrder()->take(1)->get()->toArray());
                 $questions = array_merge($questions,QuestionBank::where('subject_id','=',58)->where('explanation','like','%(4)%')->inRandomOrder()->take(1)->get()->toArray());*/

                 $questions_1 = QuestionBank::where('subject_id','=',58)->inRandomOrder()->take(1)->get()->toArray();
                 $id_questions_1 = $questions_1[0]['id'];
                 $questions = array_merge($questions, $questions_1);
                 $questions_2 = QuestionBank::where('subject_id','=',58)->wherenotin('id',[$id_questions_1])->inRandomOrder()->take(1)->get()->toArray();
                 $id_questions_2 = $questions_2[0]['id'];
                 $questions = array_merge($questions, $questions_2);
                 $questions_3 = QuestionBank::where('subject_id','=',58)->wherenotin('id',[$id_questions_1,$id_questions_2])->inRandomOrder()->take(1)->get()->toArray();
                 $id_questions_3 = $questions_3[0]['id'];
                 $questions = array_merge($questions, $questions_3);
                 $questions_4 = QuestionBank::where('subject_id','=',58)->wherenotin('id',[$id_questions_1,$id_questions_2,$id_questions_3])->inRandomOrder()->take(1)->get()->toArray();
                 $questions = array_merge($questions, $questions_4);

                 // @5 4
                 $topic_hieu_nd = Topic::where('subject_id','=',59)->where('parent_id','=',218)->inRandomOrder()->pluck('id')->first();
                 $questions = array_merge($questions,QuestionBank::where('subject_id','=',59)->where('topic_id','=',$topic_hieu_nd)->take(4)->get()->toArray());
                 // @6 2
                 $topic_tim_kiem = Topic::where('subject_id','=',60)->where('parent_id','=',219)->inRandomOrder()->pluck('id')->first();
                 $questions = array_merge($questions,QuestionBank::where('subject_id','=',60)->where('topic_id','=',$topic_tim_kiem)->take(2)->get()->toArray());
                 $marks = 0;
                 $questions_to_update = array();
                 foreach ($questions as $key => $q) {
                   $temp = array();
                   $temp['subject_id']       = $q['subject_id'];
                   $temp['questionbank_id']  = $q['id'];
                   //get last insert id quizz
                   $temp['quize_id']         = $record_quiz->id;
                   $temp['marks']            = $q['marks'];
                   $marks                   += $q['marks'];
                   array_push($questions_to_update, $temp);
                 }
                 /*//Đổi câu trả lời add question to questionbank
                 foreach ($questions_to_update as $key_question => $value_question) {
                    $questionbank_select =  DB::table('questionbank')->where('id', '=', $value_question['questionbank_id'])->first(); 
                    $answers = json_decode ($questionbank_select->answers);
                    $index = $questionbank_select->correct_answers - 1;
                    $answers1 = shuffle_assoc($answers);
                    $answers_new = json_encode(array_values($answers1));
                    $questionbank_select->answers = $answers_new;
                    $caudungmoi =  array_search($index,array_keys($answers1))  + 1;
                    $questionbank_select->questionbank_id = $questionbank_select->id;
                    unset($questionbank_select->id);
                    $questionbank_select->correct_answers = $caudungmoi;
                    $questionbank_insert = DB::table('questionbank')->insertGetId((array)$questionbank_select);
                    $questions_to_update[$key_question]['questionbank_id'] = $questionbank_insert;
                 }*/
                 DB::table('questionbank_quizzes')->where('quize_id', '=', $record_quiz->id)->delete();
                 //Insert New Questions
                 DB::table('questionbank_quizzes')->insert($questions_to_update);
                 $record_quiz->total_marks       = $marks;
                 $record_quiz->total_questions   = 35;
                 $record_quiz->save();
              }
            }//#end for 
            // add total_questions
            $record->total_questions  = 97;
            $record->save();
        } 
        if ($request->category_id == 5) {
            //add 3 bài thi
            foreach ($fori as $key_fori => $i) { 
              $dueration = 0;
              if ($i == 1) {
                 $dueration = 60;
                 $dethi = ' - Nghe (N5)';
              }
              if ($i == 2) {
                 $dueration = 25;
                 $dethi = ' - TV (N5)';
              }
              if ($i == 3) {
                 $dueration = 50;
                 $dethi = ' - NP (N5)';
              }
              $record_quiz = new Quiz();
              $name_quiz = $name . $dethi;
              $record_quiz->title        = $name_quiz;
              $record_quiz->slug         = createSlug($name_quiz);
              $record_quiz->category_id  = $request->category_id;
              $record_quiz->type         = $i;
              $record_quiz->dueration    = $dueration;
              $record_quiz->total_marks  = 0;
              $record_quiz->pass_percentage  = 50;
              $record_quiz->tags         = '';
              $record_quiz->is_paid      = $request->is_paid;
              $record_quiz->cost         = 0;
              $record_quiz->validity     = -1;
              $record_quiz->publish_results_immediately = 1;
              $record_quiz->having_negative_mark = 1;
              $record_quiz->negative_mark = '';
              $record_quiz->start_date = $request->start_date;
              $record_quiz->end_date = $request->end_date;
              $record_quiz->instructions_page_id = 1;
              $record_quiz->show_in_front = 1;
              $record_quiz->description    = '';
              $record_quiz->record_updated_by  = Auth::user()->id;
              $record_quiz->exam_type          = 'NSNT';
              $record_quiz->has_language       = 0;
              $record_quiz->save(); 
              //Insert examseries_data
              $temp_examseries_data = array();
              $temp_examseries_data['examseries_id']       = $record->id;
              $temp_examseries_data['quiz_id']  = $record_quiz->id;
              DB::table('examseries_data')->insert($temp_examseries_data);
              //Add Question to Quizz
              if ($i == 1) {
                $questions = array();
                $questions = QuestionBank::where('subject_id','=',39)->where('topic_id','=',121)->inRandomOrder()->take(7)->get()->toArray();
                $questions = array_merge($questions,QuestionBank::where('subject_id','=',38)->where('topic_id','=',122)->inRandomOrder()->take(6)->get()->toArray());
                $questions = array_merge($questions,QuestionBank::where('subject_id','=',37)->where('topic_id','=',123)->inRandomOrder()->take(5)->get()->toArray());
                $questions = array_merge($questions,QuestionBank::where('subject_id','=',36)->where('topic_id','=',124)->inRandomOrder()->take(6)->get()->toArray());
                $marks = 0;
                $questions_to_update = array();
                foreach ($questions as $key => $q) {
                  $temp = array();
                  $temp['subject_id']       = $q['subject_id'];
                  $temp['questionbank_id']  = $q['id'];
                  //get last insert id quizz
                  $temp['quize_id']         = $record_quiz->id;
                  $temp['marks']            = $q['marks'];
                  $marks                   += $q['marks'];
                  array_push($questions_to_update, $temp);
                }
               /* foreach ($questions_to_update as $key_question => $value_question) {
                   $questionbank_select =  DB::table('questionbank')->where('id', '=', $value_question['questionbank_id'])->first(); 
                   $questionbank_select->questionbank_id = $questionbank_select->id;
                   unset($questionbank_select->id);
                   $questionbank_insert = DB::table('questionbank')->insertGetId((array)$questionbank_select);
                   $questions_to_update[$key_question]['questionbank_id'] = $questionbank_insert;
                }*/
                DB::table('questionbank_quizzes')->where('quize_id', '=', $record_quiz->id)->delete();
                //Insert New Questions
                DB::table('questionbank_quizzes')->insert($questions_to_update);
                $record_quiz->total_marks       = $marks;
                $record_quiz->total_questions   = 24;
                $record_quiz->save();
              }
              if ($i == 2) {
                 $questions = array();
                 $questions_1 = array();
                 // 9
                 // 192 - Kanji 2 chữ       - 7
                 $questions_1 = QuestionBank::where('subject_id','=',49)->where('topic_id','=',90)->inRandomOrder()->take(4)->get()->toArray();
                 // 247 - Kanji 3 chữ       - 1
                 $questions_1 = array_merge($questions_1,QuestionBank::where('subject_id','=',49)->where('topic_id','=',91)->inRandomOrder()->take(4)->get()->toArray());
                 // 191 - Kanji 1 chữ - V   - 1              
                 $questions_1 = array_merge($questions_1,QuestionBank::where('subject_id','=',49)->where('topic_id','=',92)->inRandomOrder()->take(1)->get()->toArray());
                 $questions_1 = array_merge($questions_1,QuestionBank::where('subject_id','=',49)->where('topic_id','=',93)->inRandomOrder()->take(1)->get()->toArray());
                 shuffle ($questions_1);
                 


                 // @2 8 câu
                 $questions_2 = array();
                 // 198 - Kanji 2 chữ       - 1              
                 $questions_2 = QuestionBank::where('subject_id','=',48)->where('topic_id','=',94)->inRandomOrder()->take(2)->get()->toArray();
                 // Kanji 1 chữ - N
                 $questions_2 = array_merge($questions_2,QuestionBank::where('subject_id','=',48)->where('topic_id','=',95)->inRandomOrder()->take(4)->get()->toArray());
                 // Kanji 1 chữ - V
                 $questions_2 = array_merge($questions_2,QuestionBank::where('subject_id','=',48)->where('topic_id','=',97)->inRandomOrder()->take(2)->get()->toArray());
                 shuffle ($questions_2);
                 $questions = array_merge($questions_1, $questions_2);

                 
                 //@3 10 câu
                 $questions_3 = array();
                 // Kanji 1 chữ - N
                 $questions_3 = QuestionBank::where('subject_id','=',47)->where('topic_id','=',99)->inRandomOrder()->take(1)->get()->toArray();
                 // Kanji 1 chữ - A
                 $questions_3 = array_merge($questions_3,QuestionBank::where('subject_id','=',47)->where('topic_id','=',100)->inRandomOrder()->take(2)->get()->toArray());
                 // Kanji 1 chữ - V
                 $questions_3 = array_merge($questions_3,QuestionBank::where('subject_id','=',47)->where('topic_id','=',101)->inRandomOrder()->take(2)->get()->toArray());
                 // Katakana
                 $questions_3 = array_merge($questions_3,QuestionBank::where('subject_id','=',47)->where('topic_id','=',102)->inRandomOrder()->take(4)->get()->toArray());
                 // F
                 $questions_3 = array_merge($questions_3,QuestionBank::where('subject_id','=',47)->where('topic_id','=',104)->inRandomOrder()->take(1)->get()->toArray());
                shuffle ($questions_3);
                $questions = array_merge($questions, $questions_3);


                 // 5
                 // @4 5 cau dong nghia
                $questions_4 = array();
                 // V
                 $questions_4 = QuestionBank::where('subject_id','=',46)->where('topic_id','=',105)->inRandomOrder()->take(3)->get()->toArray();
                 // A
                 $questions_4 = array_merge($questions_4,QuestionBank::where('subject_id','=',46)->where('topic_id','=',106)->inRandomOrder()->take(1)->get()->toArray());
                 // F
                 $questions_4 = array_merge($questions_4,QuestionBank::where('subject_id','=',46)->where('topic_id','=',107)->inRandomOrder()->take(1)->get()->toArray());
                 shuffle ($questions_4);
                 $questions = array_merge($questions, $questions_4);

                 $marks = 0;
                 $questions_to_update = array();
                 foreach ($questions as $key => $q) {
                   $temp = array();
                   $temp['subject_id']       = $q['subject_id'];
                   $temp['questionbank_id']  = $q['id'];
                   //get last insert id quizz
                   $temp['quize_id']         = $record_quiz->id;
                   $temp['marks']            = $q['marks'];
                   $marks                   += $q['marks'];
                   array_push($questions_to_update, $temp);
                 }
                 /*//Đổi câu trả lời add question to questionbank
                 foreach ($questions_to_update as $key_question => $value_question) {
                    $questionbank_select =  DB::table('questionbank')->where('id', '=', $value_question['questionbank_id'])->first(); 
                    $answers = json_decode ($questionbank_select->answers);
                    $index = $questionbank_select->correct_answers - 1;
                    $answers1 = shuffle_assoc($answers);
                    $answers_new = json_encode(array_values($answers1));
                    $caudungmoi =  array_search($index,array_keys($answers1))  + 1;
                    $questionbank_select->questionbank_id = $questionbank_select->id;
                    unset($questionbank_select->id);
                    $questionbank_select->correct_answers = $caudungmoi;
                    $questionbank_insert = DB::table('questionbank')->insertGetId((array)$questionbank_select);
                    $questions_to_update[$key_question]['questionbank_id'] = $questionbank_insert;
                 }*/
                 DB::table('questionbank_quizzes')->where('quize_id', '=', $record_quiz->id)->delete();
                 //Insert New Questions
                 DB::table('questionbank_quizzes')->insert($questions_to_update);
                 $record_quiz->total_marks       = $marks;
                 $record_quiz->total_questions   = 33;
                 $record_quiz->save();
              }
              if ($i == 3) {
                 $questions = array();
                 //@1 15
                 //Trợ từ
                 $questions = QuestionBank::where('subject_id','=',45)->where('topic_id','=',109)->inRandomOrder()->take(6)->get()->toArray();
                 // Nghi vấn
                 $questions = array_merge($questions,QuestionBank::where('subject_id','=',45)->where('topic_id','=',110)->inRandomOrder()->take(5)->get()->toArray());
                 // y nghia
                 $questions = array_merge($questions,QuestionBank::where('subject_id','=',45)->where('topic_id','=',228)->inRandomOrder()->take(2)->get()->toArray());
                 // F
                 $questions = array_merge($questions,QuestionBank::where('subject_id','=',45)->where('topic_id','=',111)->inRandomOrder()->take(1)->get()->toArray());
                 // Chia thể V/A
                 $questions = array_merge($questions,QuestionBank::where('subject_id','=',45)->where('topic_id','=',112)->inRandomOrder()->take(1)->get()->toArray());
                 // Ngữ cảnh
                 $questions = array_merge($questions,QuestionBank::where('subject_id','=',45)->where('topic_id','=',113)->inRandomOrder()->take(1)->get()->toArray());
                 shuffle ($questions);

                 //@2 5
                 // Lắp ghép câu
                 $questions = array_merge($questions,QuestionBank::where('subject_id','=',44)->where('topic_id','=',114)->inRandomOrder()->take(5)->get()->toArray());
                 // @3 5
                 // NP trong đoạn
                 $topic_ngu_phap_trong_doan = Topic::where('subject_id','=',43)->where('parent_id','=',115)->inRandomOrder()->pluck('id')->first();
                 $questions = array_merge($questions,QuestionBank::where('subject_id','=',43)->where('topic_id','=',$topic_ngu_phap_trong_doan)->take(5)->get()->toArray());

                 // @4 3
                /* $questions = array_merge($questions,QuestionBank::where('subject_id','=',42)->where('explanation','like','%(1)%')->inRandomOrder()->take(1)->get()->toArray());
                 $questions = array_merge($questions,QuestionBank::where('subject_id','=',42)->where('explanation','like','%(2)%')->inRandomOrder()->take(1)->get()->toArray());
                 $questions = array_merge($questions,QuestionBank::where('subject_id','=',42)->where('explanation','like','%(3)%')->inRandomOrder()->take(1)->get()->toArray());*/

                 $questions_1 = QuestionBank::where('subject_id','=',42)->inRandomOrder()->take(1)->get()->toArray();
                 $id_questions_1 = $questions_1[0]['id'];
                 $questions = array_merge($questions, $questions_1);
                 $questions_2 = QuestionBank::where('subject_id','=',42)->wherenotin('id',[$id_questions_1])->inRandomOrder()->take(1)->get()->toArray();
                 $id_questions_2 = $questions_2[0]['id'];
                 $questions = array_merge($questions, $questions_2);
                 $questions_3 = QuestionBank::where('subject_id','=',42)->wherenotin('id',[$id_questions_1,$id_questions_2])->inRandomOrder()->take(1)->get()->toArray();
                 $questions = array_merge($questions, $questions_3);
                 


                 // @5 4
                 $topic_hieu_nd = Topic::where('subject_id','=',41)->where('parent_id','=',119)->inRandomOrder()->pluck('id')->first();
                 $questions = array_merge($questions,QuestionBank::where('subject_id','=',41)->where('topic_id','=',$topic_hieu_nd)->take(2)->get()->toArray());
                 // @6 1
                 $topic_tim_kiem = Topic::where('subject_id','=',40)->where('parent_id','=',120)->inRandomOrder()->pluck('id')->first();
                 $questions = array_merge($questions,QuestionBank::where('subject_id','=',40)->where('topic_id','=',$topic_tim_kiem)->take(1)->get()->toArray());
                 $marks = 0;
                 $questions_to_update = array();
                 foreach ($questions as $key => $q) {
                   $temp = array();
                   $temp['subject_id']       = $q['subject_id'];
                   $temp['questionbank_id']  = $q['id'];
                   //get last insert id quizz
                   $temp['quize_id']         = $record_quiz->id;
                   $temp['marks']            = $q['marks'];
                   $marks                   += $q['marks'];
                   array_push($questions_to_update, $temp);
                 }
                /* //Đổi câu trả lời add question to questionbank
                 foreach ($questions_to_update as $key_question => $value_question) {
                    $questionbank_select =  DB::table('questionbank')->where('id', '=', $value_question['questionbank_id'])->first(); 
                    $answers = json_decode ($questionbank_select->answers);
                    $index = $questionbank_select->correct_answers - 1;
                    $answers1 = shuffle_assoc($answers);
                    $answers_new = json_encode(array_values($answers1));
                    $caudungmoi =  array_search($index,array_keys($answers1))  + 1;
                    $questionbank_select->questionbank_id = $questionbank_select->id;
                    unset($questionbank_select->id);
                    $questionbank_select->correct_answers = $caudungmoi;
                    $questionbank_insert = DB::table('questionbank')->insertGetId((array)$questionbank_select);
                    $questions_to_update[$key_question]['questionbank_id'] = $questionbank_insert;
                 }*/
                 DB::table('questionbank_quizzes')->where('quize_id', '=', $record_quiz->id)->delete();
                 //Insert New Questions
                 DB::table('questionbank_quizzes')->insert($questions_to_update);
                 $record_quiz->total_marks       = $marks;
                 $record_quiz->total_questions   = 32;
                 $record_quiz->save();
              }
            }//#end for 
            // add total_questions
            $record->total_questions  = 89;
            $record->save();
        } 
      } //###end for 20
      flash('Thêm bộ đề thi thành công','', 'success');
      return redirect(URL_EXAM_SERIES);
    }





    public function deleteFile($record, $path, $is_array = FALSE)
    {
      if(env('DEMO_MODE')) {
        return '';
      }
        $files = array();
        $files[] = $path.$record;
        File::delete($files);
    }
    /**
     * This method process the image is being refferred
     * by getting the settings from ImageSettings Class
     * @param  Request $request   [Request object from user]
     * @param  [type]  $record    [The saved record which contains the ID]
     * @param  [type]  $file_name [The Name of the file which need to upload]
     * @return [type]             [description]
     */
     public function processUpload(Request $request, $record, $file_name)
     {
      if(env('DEMO_MODE')) {
        return 'demo';
      }
         if ($request->hasFile($file_name)) {
          $examSettings = getExamSettings();
            $imageObject = new ImageSettings();
          $destinationPath            = $examSettings->seriesImagepath;
          $destinationPathThumb       = $examSettings->seriesThumbImagepath;
          $fileName = $record->id.'-'.$file_name.'.'.$request->$file_name->guessClientExtension();
          $request->file($file_name)->move($destinationPath, $fileName);
         //Save Normal Image with 300x300
          Image::make($destinationPath.$fileName)->fit($examSettings->imageSize)->save($destinationPath.$fileName);
           Image::make($destinationPath.$fileName)->fit($imageObject->getThumbnailSize())->save($destinationPathThumb.$fileName);
        return $fileName;
        }
     }
    /**
     * Delete Record based on the provided slug
     * @param  [string] $slug [unique slug]
     * @return Boolean 
     */
    public function delete($slug)
    {
      if(!checkRole(getUserGrade(2)))
      {
        prepareBlockUserMessage();
        return back();
      }
      /**
       * Delete the questions associated with this quiz first
       * Delete the quiz
       * @var [type]
       */
      $record = ExamSeries::where('slug', $slug)->first();
      DB::table('quizresultfinish')->where('examseri_id', '=', $record['id'])->delete();
      $quizzes = DB::table('examseries_data')->where('examseries_id', $record['id'])->get()->toArray();
      if ($quizzes) {
        foreach ($quizzes as $key => $value) {
          DB::table('quizresults')->where('quiz_id', '=', $value->quiz_id)->delete();
          DB::table('questionbank_quizzes')->where('quize_id', '=', $value->quiz_id)->delete();
          DB::table('examseries_data')->where('quiz_id', '=', $value->quiz_id)->delete();
          DB::table('quizzes')->where('id', '=', $value->quiz_id)->delete();
        }
      }
      try{
        $record->delete();
        $response['status'] = 1;
        $response['message'] = 'Đã xóa bộ đề thi';
      } catch ( \Illuminate\Database\QueryException $e) {
                 $response['status'] = 0;
           if(getSetting('show_foreign_key_constraint','module'))
            $response['message'] =  $e->errorInfo;
           else
            $response['message'] =  'Đề thi này không thể xóa.';
       }
       return json_encode($response);
    }
    public function isValidRecord($record)
    {
      if ($record === null) {
        flash('Ooops...!', getPhrase("page_not_found"), 'error');
        return $this->getReturnUrl();
    }
    return FALSE;
    }
    public function getReturnUrl()
    {
      return URL_EXAM_SERIES;
    }
    /**
     * Returns the list of subjects based on the requested subject
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function getExams(Request $request)
    {
      $category_id = $request->category_id;
      $is_paid     = $request->series_type;
      $exams = Quiz::where('category_id','=',$category_id)
                    ->where('total_marks','!=','0')
                    ->where('is_paid',$is_paid)
                    ->get();
      return json_encode(array('exams'=>$exams));
    }
    /**
     * Updates the questions in a selected quiz
     * @param  [type] $slug [description]
     * @return [type]       [description]
     */
    public function updateSeries($slug)
    {
       if(!checkRole(getUserGrade(9)))
       {
            prepareBlockUserMessage();
            return back();
        }
      /**
       * Get the Quiz Id with the slug
       * Get the available questions from questionbank_quizzes table
       * Load view with this data
       */
    $record = ExamSeries::getRecordWithSlug($slug); 
      $data['record']           = $record;
      $data['active_class']       = 'exams';
        $data['right_bar']          = TRUE;
        $data['right_bar_path']     = 'exams.examseries.right-bar-update-questions';
        $data['settings']           = FALSE;
        $previous_records = array();
        if($record->total_exams > 0)
        {
            $quizzes = DB::table('examseries_data')
                            ->where('examseries_id', '=', $record->id)
                            ->get();
            foreach($quizzes as $quiz)
            {
                $temp = array();
                $temp['id'] = $quiz->quiz_id;
                $quiz_details = Quiz::where('id', '=', $quiz->quiz_id)->first();
                $temp['dueration'] = $quiz_details->dueration;
                $temp['total_marks'] = $quiz_details->total_marks;
                $temp['total_questions'] = $quiz_details->total_questions;
                $temp['title'] = $quiz_details->title;
                array_push($previous_records, $temp);
            }
            $settings['exams'] = $previous_records;
            $settings['total_questions'] = $record->total_questions;
        $data['settings']           = json_encode($settings);
        }
      $data['exam_categories']        = array_pluck(App\QuizCategory::all(), 
                      'category', 'id');
      $data['title']  = 'Cập nhật '.$record->title;
      // return view('exams.examseries.update-questions', $data);
       $view_name = 'admin.exams.examseries.update-questions';
        return view($view_name, $data);
    }
    public function storeSeries(Request $request, $slug)
    { 
        if(!checkRole(getUserGrade(9)))
        {
            prepareBlockUserMessage();
            return back();
        }
        $exam_series = ExamSeries::getRecordWithSlug($slug); 
        $series_id  = $exam_series->id;
        $quizzes    = json_decode($request->saved_series);
        $questions  = 0;
        $exams    = 0;
        $quizzes_to_update = array();
        foreach ($quizzes as $record) {
            $temp = array();
            $temp['quiz_id'] = $record->id;
            $temp['examseries_id'] = $series_id;
            array_push($quizzes_to_update, $temp);
            $questions += $record->total_questions;
        }
        $exam_series->total_questions = $questions;
        $exam_series->total_exams = count($quizzes);
        if(!env('DEMO_MODE')) {
          //Clear all previous questions
          DB::table('examseries_data')->where('examseries_id', '=', $series_id)->delete();
          //Insert New Questions
          DB::table('examseries_data')->insert($quizzes_to_update);
          $exam_series->save();
        }
        flash('success','record_updated_successfully', 'success');
        return redirect(URL_EXAM_SERIES);
    }
    /**
     * This method lists all the available exam series for students
     * 
     * @return [type] [description]
     */
    public function listSeries()
    {
      if(checkRole(getUserGrade(9)))
      {
        return back();
      }
        $data['active_class']       = 'exams';
        $data['title']              = 'Bộ đề thi';
        $data['series']             = [];
        $user = Auth::user();
        $interested_categories      = null;
        $data['series_cd'] = array();
        $classes_user =  DB::table('classes_user')
                     ->where('student_id','=',$user->id)
                     ->orderBy('id', 'desc')
                     ->first();
        if (!empty($classes_user)) {
          $classes_exam =  ExamSeries::join('classes_exam', 'classes_exam.exam_id', '=', 'examseries.id')
                       ->where('classes_exam.classes_id','=',$classes_user->classes_id)
                       ->where('classes_exam.start_date','<=',date('Y-m-d H:i:s'))
                       ->where('classes_exam.end_date','>=',date('Y-m-d H:i:s'))
                       //-> where('is_paid','=','2')
                       ->get();
          if ($classes_exam->count() > 0) {
            $data['series_cd'] = $classes_exam;
          }
        }

        $data['series_n1']             = array();
        $data['series_n2']             = array();
        $data['series_n3']             = array();
        $data['series_n4']             = array();
        $data['series_n5']             = array();

        $exam_free  = DB::table('exam_free')
                                          ->where('start_date','<=',date('Y-m-d H:i:s'))
                                          ->where('end_date','>=',date('Y-m-d H:i:s'))
                                          ->first();

        //dd($exam_free);

        $data['exam_check'] = null;

        if (!empty($exam_free)){
            $data['exam_check'] = 'exam';
        }

        $data['exam_time']= DB::table('exam_free')
            ->select('name','start_date','end_date')
            ->latest()
            ->first();


        if (!empty($exam_free)) {

          $data['series_n1']             = ExamSeries::wherein('id',[$exam_free->exam1_1])->get();
          $data['series_n2']             = ExamSeries::wherein('id',[$exam_free->exam2_1])->get();
          $data['series_n3']             = ExamSeries::wherein('id',[$exam_free->exam3_1])->get();
          $data['series_n4']             = ExamSeries::wherein('id',[$exam_free->exam4_1])->get();
          $data['series_n5']             = ExamSeries::wherein('id',[$exam_free->exam5_1])->get();
        }else{
            $data['series_n1']             = array();
            $data['series_n2']             = array();
            $data['series_n3']             = array();
            $data['series_n4']             = array();
            $data['series_n5']             = array();

        }

        if ($user->role_id == 6){
            if(checkRole(getUserGrade(6))) {
                $data['series_n1']             = ExamSeries::where('category_id','=','1')
                   // ->where('show_test','=','1')
                    ->get();
                $data['series_n2']             = ExamSeries::where('category_id','=','2')
                    //->where('show_test','=','1')
                    ->get();
                $data['series_n3']             = ExamSeries::where('category_id','=','3')
                    //->where('show_test','=','1')
                    ->get();
                $data['series_n4']             = ExamSeries::where('category_id','=','4')
                    //->where('show_test','=','1')
                    ->get();
                $data['series_n5']             = ExamSeries::where('category_id','=','5')
                    //->where('show_test','=','1')
                    ->get();

                $data['exam_check'] = 'role_test';

            }

        }
        



        //dd($data['exam_time']);
        
        /*$data['series_n2']             = ExamSeries::where('start_date','<=',date('Y-m-d'))
                                            ->where('end_date','>=',date('Y-m-d'))
                                            -> where('category_id','=','2')
                                            -> where('is_paid','=','0')
                                            ->get();
        $data['series_n3']             = ExamSeries::where('start_date','<=',date('Y-m-d'))
                                            ->where('end_date','>=',date('Y-m-d'))
                                            -> where('category_id','=','3')
                                            -> where('is_paid','=','0')
                                            ->get();
        $data['series_n4']             = ExamSeries::where('start_date','<=',date('Y-m-d'))
                                            ->where('end_date','>=',date('Y-m-d'))
                                            -> where('category_id','=','4')
                                            -> where('is_paid','=','0')
                                            ->get();
        $data['series_n5']             = ExamSeries::where('start_date','<=',date('Y-m-d'))
                                            ->where('end_date','>=',date('Y-m-d'))
                                            -> where('category_id','=','5')
                                            -> where('is_paid','=','0')
                                            ->get();*/

        $data['active_class']       = 'examslist';                                    
        $data['layout']             = getLayout();
        $data['user']             = $user;
        $view_name = 'admin.student.exams.exam-series-list';
        return view($view_name, $data);
    }

    public function viewExam($slug)
        {
            
            $record = ExamSeries::getRecordWithSlug($slug); 
            $examseries_id = $record->id; 
            // Get quizresultfinish
           $quizresultfinish_data = DB::table('quizresultfinish')
                    ->where('examseri_id', '=', $examseries_id)
                    ->where('user_id', '=', Auth::user()->id)
                    ->orderBy('id', 'desc')
                    ->first();
            if ($quizresultfinish_data && $quizresultfinish_data->finish < 3) {
              $finish_current = $quizresultfinish_data->finish + 1;
            } else {
              $finish_current = 1;
            }
            $data['finish_current'] = $finish_current;
              
            $data['active_class']       = 'exams';
            $data['pay_by']             = '';
            $data['content_record']     = FALSE;
            $data['title']              = change_furigana_admin( $record->title );
            $data['item']               = $record;
            /*$data['right_bar']         = true;
            $data['right_bar_path']   = 'student.exams.exam-series-item-view-right-bar';
            $data['right_bar_data']     = array(
                                                'item' => $record,
                                                );*/
            $data['layout']              = getLayout();
            $view_name = 'admin.exams.examseries.series-view-item';
            return view($view_name, $data);
        }

    /**
     * This method displays all the details of selected exam series
     * @param  [type] $slug [description]
     * @return [type]       [description]
     */
    public function viewItem($slug)
    {
        $record = ExamSeries::getRecordWithSlug($slug); 
        $examseries_id = $record->id; 
        // Get quizresultfinish
       $quizresultfinish_data = DB::table('quizresultfinish')
                ->where('examseri_id', '=', $examseries_id)
                ->where('user_id', '=', Auth::user()->id)
                ->orderBy('id', 'desc')
                ->first();
        if ($quizresultfinish_data && $quizresultfinish_data->finish < 3) {
          $finish_current = $quizresultfinish_data->finish + 1;
        } else {
          $finish_current = 1;
        }
        $data['finish_current'] = $finish_current;
        /*if($isValid = $this->isValidRecord($record))
          return redirect($isValid);*/  
        $data['active_class']       = 'examslist';
        $data['pay_by']             = '';
        $data['content_record']     = FALSE;
        $data['title']              = change_furigana_admin( $record->title );
        $data['item']               = $record;
        $data['right_bar']         = TRUE;
        $data['right_bar_path']   = 'student.exams.exam-series-item-view-right-bar';
        $data['right_bar_data']     = array(
                                            'item' => $record,
                                            );
        $data['layout']              = getLayout();
       // return view('student.exams.series.series-view-item', $data);
         $view_name = 'admin.student.exams.series.series-view-item';
        return view($view_name, $data);
    }

    public function Stand_Deviation($arr) 
    { 
        $num_of_elements = count($arr); 
          
        $variance = 0.0; 
          
                // calculating mean using array_sum() method 
        $average = array_sum($arr)/$num_of_elements; 
          
        foreach($arr as $i) 
        { 
            // sum of squares of differences between  
                        // all numbers and means. 
            $variance += pow(($i - $average), 2); 
        } 
          
        return (float)sqrt($variance/$num_of_elements); 
    } 

    public function chartExam($slug)
        {
            
            $record = ExamSeries::getRecordWithSlug($slug); 
            $examseries_id = $record->id;

            $total_marks = DB::table('quizresultfinish')
              ->select(['total_marks'])
              ->where('examseri_id', '=', $examseries_id)
              ->where('finish', '=', 3)
              ->where('total_marks', '>', 20)
              ->orderBy('total_marks')
              ->get();

              $total_marks_avg = DB::table('quizresultfinish')
              ->select(['total_marks'])
              ->where('examseri_id', '=', $examseries_id)
              ->where('finish', '=', 3)
              ->where('total_marks', '>', 20)
              ->orderBy('total_marks')
              ->avg('total_marks');
              

              $diem = array();
              foreach ($total_marks as $key => $value) {
                array_push($diem, $value->total_marks);
              }
              $diem_str = implode(',', $diem);

              $stdev = $this->Stand_Deviation($diem);

            $data['diem'] = $diem;
            $data['diem_str'] = $diem_str;
            $data['total_marks_avg'] = $total_marks_avg;
            $data['stdev'] = $stdev;

              
            $data['active_class']       = 'exams';
            $data['pay_by']             = '';
            $data['content_record']     = FALSE;
            $data['title']              = 'Biểu đồ độ khó đề thi: ' . $record->title;

            $data['layout']              = getLayout();
            $view_name = 'admin.exams.examseries.series-view-item-chart';
            return view($view_name, $data);
        }




}

