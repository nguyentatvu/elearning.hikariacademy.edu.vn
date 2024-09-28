<?php
namespace App\Http\Controllers;
use \App;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Quiz;
use App\Subject;
use App\Topic;
use App\QuestionBank;
use App\QuizCategory;
use Yajra\DataTables\DataTables;
use DB;
use Auth;
use Exception;
use Image;
use ImageSettings;
use File;
class QuizController extends Controller
{
  public function __construct()
  {
    $this->middleware('auth');
  }
  protected  $examSettings;
  public function setExamSettings()
  {
    $this->examSettings = getExamSettings();
  }
  public function getExamSettings()
  {
    return $this->examSettings;
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
      $data['title']              = 'Đề thi';
      // return view('exams.quiz.list', $data);
      $view_name = 'admin.exams.quiz.list';
      return view($view_name, $data);
    }
    /**
     * This method returns the datatables data to view
     * @return [type] [description]
     */
    public function getDatatable($slug = '')
    {
      if(!checkRole(getUserGrade(9)))
      {
        prepareBlockUserMessage();
        return back();
      }
      $records = array();
      if($slug=='')
      {
        $records = Quiz::join('quizcategories', 'quizzes.category_id', '=', 'quizcategories.id')
        ->select(['title', 'dueration', 'quizcategories.category', 'type', 'total_marks','exam_type','tags','quizzes.slug' ])
        ->orderBy('quizzes.id', 'desc');
      }
      else {
        $category = QuizCategory::getRecordWithSlug($slug);
        $records = Quiz::join('quizcategories', 'quizzes.category_id', '=', 'quizcategories.id')
        ->select(['title', 'dueration', 'quizcategories.category', 'type', 'total_marks','exam_type','tags','quizzes.slug' ])
        ->where('quizzes.category_id', '=', $category->id)
        ->orderBy('quizcategories.updated_at', 'desc');
      }
      return DataTables::of($records)
      ->addColumn('action', function ($records) {
        $link_data = '<div class="dropdown more">
        <a id="dLabel" type="button" class="more-dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <i class="mdi mdi-dots-vertical"></i>
        </a>
        <ul class="dropdown-menu dropdown-menu-right" aria-labelledby="dLabel">
        <li><a href="'.URL_QUIZ_UPDATE_QUESTIONS.$records->slug.'"><i class="fa fa-spinner"></i>Cập nhật câu hỏi</a></li>
        <li><a href="'.URL_QUIZ_EDIT.'/'.$records->slug.'"><i class="fa fa-pencil"></i>Sửa</a></li>';
        $temp = '';
        /*if(checkRole(getUserGrade(1))) {
          $temp .= ' <li><a href="javascript:void(0);" onclick="deleteRecord(\''.$records->slug.'\');"><i class="fa fa-trash"></i>Xóa</a></li>';
        }*/
        $temp .= ' <li><a href="javascript:void(0);" onclick="deleteRecord(\''.$records->slug.'\');"><i class="fa fa-trash"></i>Xóa</a></li>';
        $temp .='</ul></div>';
        $link_data .=$temp;
        return $link_data;
      })
      ->editColumn('type', function($records)
      {
        if  ($records->type == 1) {
          $type = "Nghe";
        } elseif ($records->type == 2) {
          $type = "TV";
        } else {
          $type = "NP/ĐH";
        }

        return $type;
      })
      ->editColumn('total_marks', function($records)
      {
        return intval($records->total_marks);
      })
      ->editColumn('title',function($records)
      {
        return '<a href="'.URL_QUIZ_UPDATE_QUESTIONS.$records->slug.'">'.change_furigana_show_info($records->title).'</a>';
      })
      ->editColumn('exam_type',function($records)
      {
       return App\ExamType::where('code',$records->exam_type)->first()->title;
     })
      ->removeColumn('exam_type')
      ->removeColumn('id')
      ->removeColumn('slug')
      ->removeColumn('tags')
      ->rawColumns(['title','action'])
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
      $data['record']             = FALSE;
      $data['active_class']       = 'exams';
      $data['categories']         = array_pluck(QuizCategory::all(), 'category', 'id');
      $data['instructions']       = array_pluck(App\Instruction::all(), 'title', 'id');
      $data['exam_types']         = App\ExamType::where('status','=',1)->get()->pluck('title','code')->toArray();
      // dd($data);
      $data['title']              = 'Tạo đề thi';
      // return view('exams.quiz.add-edit', $data);
      $view_name = 'admin.exams.quiz.add-edit';
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
      $record = Quiz::getRecordWithSlug($slug);
      if($isValid = $this->isValidRecord($record))
        return redirect($isValid);
      $data['record']           = $record;
      $data['active_class']     = 'exams';
      $data['settings']         = FALSE;
      $data['instructions']     = array_pluck(App\Instruction::all(), 'title', 'id');
      $data['categories']       = array_pluck(QuizCategory::all(), 'category', 'id');
      $data['exam_types']         = App\ExamType::get()->pluck('title','code')->toArray();
      $data['title']            = 'Chỉnh sửa đề thi';
      // return view('exams.quiz.add-edit', $data);
      $view_name = 'admin.exams.quiz.add-edit';


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
      // dd($request);
      if(!checkRole(getUserGrade(9)))
      {
        prepareBlockUserMessage();
        return back();
      }
      $record = Quiz::getRecordWithSlug($slug);
      $rules = [
       'title'               => 'bail|required|max:400' ,
       'dueration'           => 'bail|required|integer' ,
       'pass_percentage'     => 'bail|required|numeric|max:100|min:1' ,
       'category_id'         => 'bail|required|integer' ,
       'instructions_page_id' => 'bail|required|integer' ,
     ];
         /**
        * Check if the title of the record is changed, 
        * if changed update the slug value based on the new title
        */
         $name = $request->title;
         if($name != $record->title)
          $record->slug = createSlug(Quiz::class, $name);
       //Validate the overall request
        $this->validate($request, $rules);
        if($request->show_in_front == 1){
         if($request->is_paid == 1){
          flash('Ooops...!','practice_exam_must_be_non_paid_exam','overlay');
          return back();
        }
        elseif ($request->exam_type !='NSNT') {
          flash('Ooops...!','practice_exam_must_be_no_section_no_timer_exam','overlay');
          return back();
        }
      }  
      $record->title        = $name;
      $record->category_id    = $request->category_id;
      $record->type           = $request->type;
      $record->dueration      = $request->dueration;
      $record->total_marks    = $request->total_marks;
      $record->pass_percentage  = $request->pass_percentage;
      $record->tags       = '';
      $record->is_paid      = $request->is_paid;
      $record->cost       = 0;
      $record->validity       = -1;
      if($record->is_paid) {
        $record->cost         = $request->cost;
        $record->validity     = $request->validity;
      }
      $record->publish_results_immediately      
      = 1;
      $record->having_negative_mark = 1;
      $record->negative_mark = $request->negative_mark;
      $record->instructions_page_id = $request->instructions_page_id;
      $record->show_in_front = $request->show_in_front;
      if(!$request->negative_mark)
        $record->having_negative_mark = 0;
      $record->description    = $request->description;
      $record->record_updated_by  = Auth::user()->id;
      $record->start_date = $request->start_date;
      $record->end_date = $request->end_date;
      $record->exam_type          = $request->exam_type;
      $record->has_language       = $request->has_language;
      if($request->has_language == 1){
        $record->language_name       = $request->language_name;
      }
      if(!env('DEMO_MODE')) {
        $record->save();
      }
      $file_name = 'examimage';
      if ($request->hasFile($file_name))
      {
       $rules = array( $file_name => 'mimes:jpeg,jpg,png,gif|max:10000' );
       $this->validate($request, $rules);
       $record->image      = $this->processUpload($request, $record,$file_name);
       $record->save();
     }
     flash('Cập nhật thành công','', 'success');
     return redirect(URL_QUIZZES);
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
       'title'               => 'bail|required|max:400' ,
       'dueration'           => 'bail|required|integer' ,
       'category_id'         => 'bail|required|integer' ,
       'instructions_page_id' => 'bail|required|integer' ,
       'pass_percentage'     => 'bail|required|numeric|max:100|min:1' ,
       'examimage'                => 'bail|mimes:png,jpg,jpeg|max:2048'
     ];
     $this->validate($request, $rules);
     if($request->show_in_front == 1){
       if($request->is_paid == 1){
        flash('Ooops...!','practice_exam_must_be_non_paid_exam','overlay');
        return back();
      }
      elseif ($request->exam_type !='NSNT') {
        flash('Ooops...!','practice_exam_must_be_no_section_no_timer_exam','overlay');
        return back();
      }
    }  
    $record = new Quiz();
    $name                 =  $request->title;
    $record->title        = $name;
    $record->slug         = createSlug(Quiz::class, $name);
    $record->category_id    = $request->category_id;
    $record->type           = $request->type;
    $record->dueration      = $request->dueration;
    $record->total_marks    = $request->total_marks;
    $record->pass_percentage  = $request->pass_percentage;
    $record->tags       = '';
    $record->is_paid      = $request->is_paid;
    $record->cost       = 0;
    $record->validity       = -1;
    if($record->is_paid) {
      // $record->cost         = $request->cost;
      // $record->validity     = $request->validity;
    }
    $record->publish_results_immediately  = $request->publish_results_immediately;
    $record->publish_results_immediately = 1;
    $record->having_negative_mark = 1;
    $record->negative_mark = $request->negative_mark;
    $record->start_date = $request->start_date;
    $record->end_date = $request->end_date;
    $record->instructions_page_id = $request->instructions_page_id;
    $record->show_in_front = $request->show_in_front;
    if(!$request->negative_mark)
      $record->having_negative_mark = 0;
    $record->description    = $request->description;
    $record->record_updated_by  = Auth::user()->id;
    $record->exam_type          = $request->exam_type;
    $record->has_language       = $request->has_language;
    if($request->has_language == 1){
      $record->language_name       = $request->language_name;
    }
    $record->save(); 



      if ($record->category_id == 3 && $record->type == 1) {
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
            $temp['quize_id']         = $record->id;
            $temp['marks']            = $q['marks'];
            $marks                   += $q['marks'];
            array_push($questions_to_update, $temp);
          }
          DB::table('questionbank_quizzes')->where('quize_id', '=', $record->id)->delete();
          //Insert New Questions
          DB::table('questionbank_quizzes')->insert($questions_to_update);
          $record->total_marks       = $marks;
          $record->total_questions   = 28;
          $record->save();
      }
      if ($record->category_id == 3 && $record->type == 2) {
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
             $temp['quize_id']         = $record->id;
             $temp['marks']            = $q['marks'];
             $marks                   += $q['marks'];
             array_push($questions_to_update, $temp);
           }
           
           DB::table('questionbank_quizzes')->where('quize_id', '=', $record->id)->delete();
           //Insert New Questions
           DB::table('questionbank_quizzes')->insert($questions_to_update);
           $record->total_marks       = $marks;
           $record->total_questions   = 35;
           $record->save();
      }
      if ($record->category_id == 3 && $record->type == 3) {
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
            $temp['quize_id']         = $record->id;
            $temp['marks']            = $q['marks'];
            $marks                   += $q['marks'];
            array_push($questions_to_update, $temp);
          }
         
          DB::table('questionbank_quizzes')->where('quize_id', '=', $record->id)->delete();
          //Insert New Questions
          DB::table('questionbank_quizzes')->insert($questions_to_update);
          $record->total_marks       = $marks;
          $record->total_questions   = 39;
          $record->save();
      }
      if ($record->category_id == 4 && $record->type == 1) {
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
            $temp['quize_id']         = $record->id;
            $temp['marks']            = $q['marks'];
            $marks                   += $q['marks'];
            array_push($questions_to_update, $temp);
          }
         
          DB::table('questionbank_quizzes')->where('quize_id', '=', $record->id)->delete();
          //Insert New Questions
          DB::table('questionbank_quizzes')->insert($questions_to_update);
          $record->total_marks       = $marks;
          $record->total_questions   = 28;
          $record->save();
      }
      if ($record->category_id == 4 && $record->type == 2) {
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
            $temp['quize_id']         = $record->id;
            $temp['marks']            = $q['marks'];
            $marks                   += $q['marks'];
            array_push($questions_to_update, $temp);
          }
        
          DB::table('questionbank_quizzes')->where('quize_id', '=', $record->id)->delete();
          //Insert New Questions
          DB::table('questionbank_quizzes')->insert($questions_to_update);
          $record->total_marks       = $marks;
          $record->total_questions   = 34;
          $record->save();
      }
      if ($record->category_id == 4 && $record->type == 3) {
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
          $temp['quize_id']         = $record->id;
          $temp['marks']            = $q['marks'];
          $marks                   += $q['marks'];
          array_push($questions_to_update, $temp);
        }
        
        DB::table('questionbank_quizzes')->where('quize_id', '=', $record->id)->delete();
        //Insert New Questions
        DB::table('questionbank_quizzes')->insert($questions_to_update);
        $record->total_marks       = $marks;
        $record->total_questions   = 35;
        $record->save();
      }
      if ($record->category_id == 5 && $record->type == 1) {
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
           $temp['quize_id']         = $record->id;
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
         DB::table('questionbank_quizzes')->where('quize_id', '=', $record->id)->delete();
         //Insert New Questions
         DB::table('questionbank_quizzes')->insert($questions_to_update);
         $record->total_marks       = $marks;
         $record->total_questions   = 24;
         $record->save();
      }
      if ($record->category_id == 5 && $record->type == 2) {
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
             $temp['quize_id']         = $record->id;
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
           DB::table('questionbank_quizzes')->where('quize_id', '=', $record->id)->delete();
           //Insert New Questions
           DB::table('questionbank_quizzes')->insert($questions_to_update);
           $record->total_marks       = $marks;
           $record->total_questions   = 33;
           $record->save();
      }
      if ($record->category_id == 5 && $record->type == 3) {
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
             $temp['quize_id']         = $record->id;
             $temp['marks']            = $q['marks'];
             $marks                   += $q['marks'];
             array_push($questions_to_update, $temp);
           }
       
           DB::table('questionbank_quizzes')->where('quize_id', '=', $record->id)->delete();
           //Insert New Questions
           DB::table('questionbank_quizzes')->insert($questions_to_update);
           $record->total_marks       = $marks;
           $record->total_questions   = 32;
           $record->save();
      }
      
      if ($record->category_id == 4 && $record->type == 3) {

          
          $questions = array();
          //@1 15
          //Trợ từ
          $questions = QuestionBank::where('subject_id','=',55)->where('topic_id','=',211)->inRandomOrder()->take(6)->get()->toArray();
          // Nghi vấn
          $questions = array_merge($questions,QuestionBank::where('subject_id','=',55)->where('topic_id','=',213)->inRandomOrder()->take(1)->get()->toArray());
          // F
          $questions = array_merge($questions,QuestionBank::where('subject_id','=',55)->where('topic_id','=',214)->inRandomOrder()->take(2)->get()->toArray());
          // Chia thể V/A
          $questions = array_merge($questions,QuestionBank::where('subject_id','=',55)->where('topic_id','=',212)->inRandomOrder()->take(5)->get()->toArray());
          // Ngữ cảnh
          $questions = array_merge($questions,QuestionBank::where('subject_id','=',55)->where('topic_id','=',215)->inRandomOrder()->take(1)->get()->toArray());

          //@2 5
          // Lắp ghép câu
          $questions = array_merge($questions,QuestionBank::where('subject_id','=',56)->where('topic_id','=',216)->inRandomOrder()->take(5)->get()->toArray());

          // @3 5
          // NP trong đoạn
          $topic_ngu_phap_trong_doan = Topic::where('subject_id','=',57)->where('parent_id','=',217)->inRandomOrder()->pluck('id')->first();
          $questions = array_merge($questions,QuestionBank::where('subject_id','=',57)->where('topic_id','=',$topic_ngu_phap_trong_doan)->take(5)->get()->toArray());

          // @4 4
          $questions = array_merge($questions,QuestionBank::where('subject_id','=',58)->where('explanation','like','%(1)%')->inRandomOrder()->take(1)->get()->toArray());
          $questions = array_merge($questions,QuestionBank::where('subject_id','=',58)->where('explanation','like','%(2)%')->inRandomOrder()->take(1)->get()->toArray());
          $questions = array_merge($questions,QuestionBank::where('subject_id','=',58)->where('explanation','like','%(3)%')->inRandomOrder()->take(1)->get()->toArray());
          $questions = array_merge($questions,QuestionBank::where('subject_id','=',58)->where('explanation','like','%(4)%')->inRandomOrder()->take(1)->get()->toArray());

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
            $temp['quize_id']         = $record->id;
            $temp['marks']            = $q['marks'];
            $marks                   += $q['marks'];

            array_push($questions_to_update, $temp);
          }


          DB::table('questionbank_quizzes')->where('quize_id', '=', $record->id)->delete();
          //Insert New Questions
          DB::table('questionbank_quizzes')->insert($questions_to_update);


          $record->total_marks       = $marks;
          $record->total_questions   = 35;
          $record->save();

          
      }


   flash('Thêm bài thi thành công','', 'success');
   return redirect(URL_QUIZZES);
 }
    /**
     * Delete Record based on the provided slug
     * @param  [string] $slug [unique slug]
     * @return Boolean 
     */
    public function delete($slug)
    {
      if(!checkRole(getUserGrade(9)))
      {
        prepareBlockUserMessage();
        return back();
      }
      /**
       * Delete the questions associated with this quiz first
       * Delete the quiz
       * @var [type]
       */

      $record = Quiz::where('slug', $slug)->first();
      DB::table('quizresults')->where('quiz_id', '=', $record->id)->delete();
      DB::table('questionbank_quizzes')->where('quize_id', '=', $record->id)->delete();
      DB::table('examseries_data')->where('quiz_id', '=', $record->id)->delete();
      
      try{
        if(!env('DEMO_MODE')) {
          $record->delete();
        }
        $response['status'] = 1;
        $response['message'] = getPhrase('record_deleted_successfully');
      } catch (Exception $e) {
        $response['status'] = 0;
        if(getSetting('show_foreign_key_constraint','module'))
          $response['message'] =  $e->getMessage();
        else
          $response['message'] =  getPhrase('this_record_is_in_use_in_other_modules');
      }
      return json_encode($response);
    }
    public function isValidRecord($record)
    {
      if ($record === null) {
        flash('Ooops...!', getPhrase("page_not_found"), 'error');
        return $this->getRedirectUrl();
      }
      return FALSE;
    }
    public function getReturnUrl()
    {
      return URL_QUIZZES;
    }
    /**
     * Returns the list of subjects based on the requested subject
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function getSubjectData(Request $request)
    {
      $subject_id = $request->subject_id;
      $subject = Subject::where('id','=',$subject_id)->first();
      $topics = Topic::where('parent_id', '=', '0')->where('subject_id','=',$subject_id)
      ->get();
      $questions = $subject->questions()->join('topics','topics.id','=','questionbank.topic_id')->get(['questionbank.id', 'questionbank.subject_id', 'topics.topic_name','topic_id', 'question_type', 'question', 'book', 'page', 'marks', 'correct_answers','difficulty_level', 'questionbank.status', DB::raw('(SELECT COUNT(*) FROM questionbank_quizzes WHERE questionbank_quizzes.questionbank_id = questionbank.id) as socautrung')]);
      return json_encode(array('topics'=>$topics, 'questions'=>$questions, 'subject'=>$subject));
    }
    /**
     * Returns the list of subjects based on the requested subject
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function getTopicData(Request $request)
    {
      $topic_id = $request->topic_id;
      //$topic = Topic::where('id','=',$topic_id)->first();
      // $topics = $subject
      // ->topics()
      // ->where('parent_id', '=', '0')->where('subject_id','=',$subject_id)
      // ->get(['topic_name', 'id']);
      $questions = QuestionBank::join('topics','topics.id','=','questionbank.topic_id')->where('topic_id','=',$topic_id)->get(['questionbank.id', 'questionbank.subject_id', 'topics.topic_name','topic_id', 'question_type', 'question', 'book', 'page', 'marks', 'difficulty_level', 'questionbank.status']);
      return json_encode(array('questions'=>$questions));
    }
    /**
     * Updates the questions in a selected quiz
     * @param  [type] $slug [description]
     * @return [type]       [description]
     */
    public function updateQuestions($slug)
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
     $record = Quiz::getRecordWithSlug($slug);  
     $category = $record->category_id;
     $type = $record->type;
     $data['record']           = $record;
     $data['active_class']       = 'exams';
          // $data['right_bar']          = FALSE;
          // $data['right_bar_path']     = 'exams.quiz.right-bar-update-questions';
     $data['settings']           = FALSE;
     $previous_questions = array();
     if($record->total_questions > 0)
     {
      $questions = DB::table('questionbank_quizzes')
      ->where('quize_id', '=', $record->id)
      ->get();
              // dd($questions);
    foreach($questions as $question)
    {
      $temp = array();
      $temp['id']          = $question->subject_id.$question->questionbank_id;
      $temp['subject_id']  = $question->subject_id;
      $temp['question_id'] = $question->questionbank_id;
      $temp['marks']       = $question->marks;
      $question_details         = QuestionBank::find($question->questionbank_id);
      $question_details         = QuestionBank::join('topics', 'questionbank.topic_id', '=', 'topics.id')->find($question->questionbank_id);

      $count = DB::table('questionbank_quizzes')
      ->where('questionbank_id', '=', $question->questionbank_id)
      ->count();
      $subject                  = $question_details->subject;
      $temp['question']         = '('. $count . ') ' . $question_details->question;
      $temp['question_type']    = $question_details->question_type;
      $temp['difficulty_level'] = $question_details->difficulty_level;
      $temp['subject_title']    = $subject->subject_title;
      $temp['topic_name']    = $question_details->topic_name;
      array_push($previous_questions, $temp);
    }
    $section_data = [];
    $section_wise_questions       = [];
    $settings['is_have_sections'] = 0;
    $settings['questions']        = $previous_questions;
    if($record->exam_type!='NSNT') {
      $settings['is_have_sections'] = 1;
      if($record->section_data) {
        $section_data = json_decode($record->section_data);
      }
      $temp_questions =[];
      foreach($previous_questions as $question)
        $temp_questions[$question['question_id']] = $question;
      foreach($section_data as $sd)
      {
        $index = str_replace(' ','_',$sd->section_name);
        $section_wise_questions[$index]['section_name'] = $sd->section_name;
        $section_wise_questions[$index]['section_time'] = $sd->section_time;
        foreach($sd->questions as $q_no) 
        {
          $section_wise_questions[$index]['questions'][] = $temp_questions[$q_no];
        }
        $index++;
      }
      //$settings['questions'] = $section_wise_questions;
    }
    $settings['total_marks']  = $record->total_marks;
    $settings['section_data'] = $record->section_data;
    $data['settings']         = json_encode($settings);
    }
    $orderby = 'asc';
    if ($category == 5) {
      $orderby = 'desc';
    }
    $data['category_id'] = $category;
    $data['subjects']     = array_pluck(App\Subject::where('category_id','=',$category)->where('type','=',$type)->orderby('id',$orderby)->get(), 'subject_title', 'id');
    $data['title']        = 'Cập nhật Câu hỏi '. change_furigana_title($record->title);

  // echo "<pre>";
  // print_r($settings);
  // echo "</pre>"; exit;

  // return view('exams.quiz.update-questions', $data);
  $view_name = 'admin.exams.quiz.update-questions';
  return view($view_name, $data);
}
public function storeQuestions(Request $request, $slug)
{
       // dd($request);
  if(!checkRole(getUserGrade(9)))
  {
    prepareBlockUserMessage();
    return back();
  }
  $added_sections  = $request->add_section_names;
  $added_times     = $request->add_section_times;
  DB::beginTransaction();
  try {
    $quiz = Quiz::getRecordWithSlug($slug); 
    $quiz_id    = $quiz->id;
    $questions  = json_decode($request->saved_questions);
        // dd($questions);
    $marks = 0;
    $questions_to_update = array();
    $sections_data = array();
    foreach ($questions as $ques_key => $q) 
    {
           // dd($q);
      if($quiz->exam_type!='NSNT')
      {
        foreach($q->questions as $question)
        {
            // dd($question);
          $temp = array();
          $temp['subject_id']       = $question->subject_id;
          $temp['questionbank_id']  = $question->question_id;
          $temp['quize_id']         = $quiz_id;
          $temp['marks']            = $question->marks;
          $marks                   += $question->marks;
          array_push($questions_to_update, $temp);
          $key = str_replace(' ', '_', $added_sections[$ques_key]);
              // dd($key);
          $sections_data[$key]['section_name']  = $added_sections[$ques_key];
          $sections_data[$key]['section_time']  = $added_times[$ques_key];
          if(!isset($sections_data[$key]['questions']))
            $sections_data[$key]['questions'] = [];
          if(!in_array($question->question_id, $sections_data[$key]['questions']))
            array_push($sections_data[$key]['questions'], $question->question_id);
        }
      }
      else {
        $temp = array();
        $temp['subject_id']       = $q->subject_id;
        $temp['questionbank_id']  = $q->question_id;
        $temp['quize_id']         = $quiz_id;
        $temp['marks']            = $q->marks;
        $marks                   += $q->marks;
        array_push($questions_to_update, $temp);
      }
    }
    $sections_data  = json_encode($sections_data);
    $total_questions = count($questions_to_update);
          //Clear all previous questions
    DB::table('questionbank_quizzes')->where('quize_id', '=', $quiz_id)->delete();
          //Insert New Questions
    DB::table('questionbank_quizzes')->insert($questions_to_update);
    $quiz->total_questions = $total_questions;
    $quiz->total_marks     = $marks;
    $quiz->section_data    = $sections_data;
    $quiz->save();
    DB::commit();
    flash('Cập nhật thành công','', 'success');
  }
  catch (Exception $e) {
   DB::rollBack();
   flash('Có lỗi trong quá trình cập nhật','', 'error');
 }
 return redirect(URL_QUIZZES);
}
     /**
     * Course listing method
     * @return Illuminate\Database\Eloquent\Collection
     */
     public function examTypes()
     {
      if(!checkRole(getUserGrade(9)))
      {
        prepareBlockUserMessage();
        return back();
      }
      $data['active_class']       = 'exams';
      $data['title']              = getPhrase('exam_types');
      $data['exam_types']         = App\ExamType::get();
        // return view('exams.exam-types', $data);
      $view_name = 'admin.exams.exam-types';
      return view($view_name, $data);
    }
    public function editExamType($code)
    { 
     if(!checkRole(getUserGrade(9)))
     {
      prepareBlockUserMessage();
      return back();
    }
    $data['active_class']       = 'exams';
    $data['title']              = getPhrase('edit_exam_type');
    $data['record']             = App\ExamType::where('code',$code)->first();
        // dd($data);
        // return view('exams.edit-exam-type', $data);
    $view_name = 'admin.exams.edit-exam-type';
    return view($view_name, $data);
  }
  public function updateExamType(Request $request, $code)
  {
   if(!checkRole(getUserGrade(9)))
   {
    prepareBlockUserMessage();
    return back();
  }
  $record   = App\ExamType::where('code',$code)->first()->update($request->all());
  flash('Thành công','','success'); 
  return redirect(URL_EXAM_TYPES);
}
public function processUpload(Request $request, $record, $file_name)
{
 if(env('DEMO_MODE')) {
  return ;
}
if ($request->hasFile($file_name)) {
  $examSettings = getExamSettings();
  $destinationPath      = $examSettings->categoryImagepath;
  $fileName = $record->id.'-'.$file_name.'.'.$request->$file_name->guessClientExtension();
  $request->file($file_name)->move($destinationPath, $fileName);
         //Save Normal Image with 300x300
  Image::make($destinationPath.$fileName)->fit($examSettings->imageSize)->save($destinationPath.$fileName);
  return $fileName;
}
}
public function deleteFile($record, $path, $is_array = FALSE)
{
 if(env('DEMO_MODE')) {
  return ;
}
$files = array();
$files[] = $path.$record;
File::delete($files);
}
}
