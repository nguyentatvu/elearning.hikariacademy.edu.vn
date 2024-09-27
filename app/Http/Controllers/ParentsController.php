<?php
namespace App\Http\Controllers;
use \App;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\User;
use App\Classes;
use App\ClassesExam;
use Yajra\DataTables\DataTables;
use DB;
use App\ExamScore;
use App\QuizResultfinish;

class ParentsController extends Controller
{
 public function __construct()
 {
   $currentUser = \Auth::user();
   $this->middleware('auth');
 }
    /**
    * Display a listing of the resource.
    *
    * @return Response
    */
    public function index($slug)
    {
     $user = getUserWithSlug();
     if(!checkRole(getUserGrade(4)))
     {
      prepareBlockUserMessage();
      return back();
    }
    if(!isEligible($user->slug))
      return back();
    $data['records']      = FALSE;
    $data['user']       = $user;
    $data['title']        = 'Học viên';
    $data['active_class'] = 'children';
    $data['layout']       = getLayout();
    $data['slug'] = $slug;
       // return view('parent.list-users', $data);
    $view_name = 'admin.parent.list-users';
    return view($view_name, $data);     
  }
 
  public function class()
  {
   $user = getUserWithSlug();
   if(!checkRole(getUserGrade(4)))
   {
    prepareBlockUserMessage();
    return back();
  }
  if(!isEligible($user->slug))
    return back();
  $data['records']      = FALSE;
  $data['user']       = $user;
  $data['title']        = 'Lớp học';
  $data['active_class'] = 'children';
  $data['layout']       = getLayout();
       // return view('parent.list-users', $data);
  $view_name = 'admin.parent.list-class';
  return view($view_name, $data);     
 }

    /**
     * Shows comments of students for teacher to view
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function comments()
    {
        $data['records'] = false;
        $data['user'] = getUserWithSlug();
        $data['active_class'] = 'comments';
        $data['title'] = 'Comments';
        $data['layout']       = getLayout();

        $view_name = 'admin.parent.list-comment';
        return view($view_name, $data);
    }

	public function classmark($slug, $slug_exam, $slug_category)
	{
	 $user = getUserWithSlug();
	 if(!checkRole(getUserGrade(4)))
	 {
	  prepareBlockUserMessage();
	  return back();
	}
	if(!isEligible($user->slug))
	  return back();
	$classes = DB::table('classes')
	->select('classes.*' )
	->where('id', '=', $slug)
	->first();
	$exam = DB::table('examseries')
	->select('examseries.*' )
	->where('id', '=', $slug_exam)
	->first();
		  // /exit;
	$data['slug'] = $slug;
	$data['slug_exam'] = $slug_exam;
	$data['slug_category'] = $slug_category;
	$data['classname'] = $classes->name;
	$data['exam_title'] = $exam->title;
	$data['records']      = FALSE;
	$data['user']       = $user;
	$data['title']        = 'Điểm thi lớp: ' . $classes->name . ' - Đề thi: ' . $exam->title;
	$data['active_class'] = 'children';
	$data['layout']       = getLayout();
		   // return view('parent.list-users', $data);
	$view_name = 'admin.parent.list-classmark';
	return view($view_name, $data);     
	}

    /**
     * Get Classmark Datatable
     * @param string $slug
     * @param string $slug_exam
     * @param string $slug_category
     *
     * @return Yajra\DataTables\DataTables
     */
    public function getClassmarkDatatable($slug, $slug_exam, $slug_category) {
        $records = array();

        $records = QuizResultfinish::join('classes_user', 'classes_user.student_id', '=', 'quizresultfinish.user_id')
            ->join('examseries', 'examseries.id', '=', 'quizresultfinish.examseri_id')
            ->join('users', 'users.id', '=', 'quizresultfinish.user_id')
            ->select(
                [
                    'quizresultfinish.id as id',
                    'users.name',
                    'quizresultfinish.quiz_1_total',
                    'quizresultfinish.quiz_2_total',
                    'quizresultfinish.quiz_3_total',
                    'quizresultfinish.total_marks',
                    'quizresultfinish.finish',
                    'examseries.category_id',
                    'quizresultfinish.id as quiz_result_id',
                    'classes_user.student_id as student_id',
                ]
            )
            ->where('classes_user.classes_id', '=', $slug)
            ->where('examseri_id', '=', $slug_exam)
            ->where('examseries.category_id', '=', $slug_category)
            ->get();
        $records->load('resultReview');

        return DataTables::of($records)
            ->editColumn('name', function ($record) {
                return ucfirst($record->name);
            })
            ->editColumn('quiz_1_total', function ($record) {
                $exam_score = new ExamScore();
                if ($record->finish == 3) {
                    if ($record->category_id <= 3) {
                        $style1 = ($exam_score->checkKijunTen($record->category_id, 1, $record->quiz_1_total)) ? "info" : "danger";
                        $style2 = ($exam_score->checkKijunTen($record->category_id, 2, $record->quiz_2_total)) ? "info" : "danger";
                        $style3 = ($exam_score->checkKijunTen($record->category_id, 3, $record->quiz_3_total)) ? "info" : "danger";
                        $detail = '言語知識（文字・語彙・文法）: <span class="label label-' . $style1 . '">' . $record->quiz_1_total . '</span><br>読解: <span class="label label-' . $style2 . '">' . $record->quiz_2_total . '</span><br>聴解: <span class="label label-' . $style3 . '">' . $record->quiz_3_total . '</span>';
                    } else {
                        $style1 = ($exam_score->checkKijunTen($record->category_id, 1, $record->quiz_1_total)) ? "info" : "danger";
                        $style3 = ($exam_score->checkKijunTen($record->category_id, 2, $record->quiz_3_total)) ? "info" : "danger";
                        $detail = '言語知識（文字・語彙・文法）: <span class="label label-' . $style1 . '">' . $record->quiz_1_total . '</span><br>聴解: <span class="label label-' . $style3 . '">' . $record->quiz_3_total . '</span>';
                    }
                } else {
                    $detail = '<span class="label label-danger">Chưa hoàn thành</span>';
                }
                return $detail;
            })
            ->editColumn('total_marks', function ($record) {
                if ($record->finish == 3) {

                    $exam_score = new ExamScore();
                    $style = ($exam_score->checkPassingscore($record->category_id, $record->total_marks) && $exam_score->checkKijunTenAnyKubun($record->category_id, $record->quiz_1_total, $record->quiz_2_total, $record->quiz_3_total)) ? "success" : "warning";
                    $ketqua = '<span class="label label-' . $style . '">' . $record->total_marks . '/ 180</span>';
                } else {
                    $ketqua = '<span class="label label-danger">Chưa hoàn thành</span>';
                }
                return $ketqua;
            })
            ->editColumn('finish', function ($record) {
                $exam_score = new ExamScore();

                if ($record->finish == 3) {
                    if ($exam_score->checkPassingscore($record->category_id, $record->total_marks) && $exam_score->checkKijunTenAnyKubun($record->category_id, $record->quiz_1_total, $record->quiz_2_total, $record->quiz_3_total)) {
                        $ketqua = '<span class="label label-success">Đạt</span>';
                    } else {
                        $ketqua = '<span class="label label-warning">Chưa đạt</span>';
                    }
                } else {
                    $ketqua = '<span class="label label-danger">Chưa hoàn thành</span>';
                }
                return $ketqua;
            })
            ->addColumn('review', function ($record) {
                $isReviewed = !empty($record->resultReview);
                $buttonClass = $isReviewed ? 'btn-review_done' : 'btn-review_yet';
                $iconClass = $isReviewed ? 'fa-check' : 'fa-pencil';
                $buttonText = $isReviewed ? 'Đã đánh giá' : 'Đánh giá';

                return
                    '<button class="btn ' . $buttonClass . ' mb-3 mb-xl-0" data-toggle="modal" data-target="#reviewModal"
                        onclick="openReviewModal(\'' . $record->quiz_result_id . '\')">
                        <i class="fa ' . $iconClass . '"></i> ' . $buttonText . '
                    </button>';
            })
            ->removeColumn('id')
            ->removeColumn('result_review')
            ->removeColumn('category_id')
            ->removeColumn('quiz_2_total')
            ->removeColumn('quiz_3_total')
            ->removeColumn('quiz_result_id')
            ->removeColumn('student_id')
            ->rawColumns(['finish', 'review', 'total_marks', 'quiz_1_total'])
            ->make();
  }

   public function examList($slug)
   {
     $user = getUserWithSlug();
     if(!checkRole(getUserGrade(4)))
     {
      prepareBlockUserMessage();
      return back();
    }

    //Get đề chỉ định
    $exam_chidinh = DB::table('examseries')
    ->select('examseries.*' )
    //->where('is_paid', '=', 2)
    ->get();
    $data['option_exam_chidinh'] = array_pluck($exam_chidinh, 'title', 'id');      
    $classes = DB::table('classes')
    ->select('classes.*' )
    ->where('id', '=', $slug)
    ->first();
    $data['categories']   = array_pluck(DB::table('quizcategories')->get(), 'category', 'id');
    $data['class_name']   = $classes->name;
    $data['records']      = FALSE;
    $data['slug']         = $slug;
    $data['user']         = $user;
    $data['title']        = 'Danh sách đề thi lớp: ' . $classes->name;
    $data['active_class'] = 'exam-list';
    $data['layout']       = getLayout();
    $view_name = 'admin.parent.exam-list';
    return view($view_name, $data);
  }
  public function editExamClass($slug, $slug_exam)
  {
   $user = getUserWithSlug();
   if(!checkRole(getUserGrade(4)))
   {
    prepareBlockUserMessage();
    return back();
  }
  if(!isEligible($user->slug))
    return back();
      //Get đề chỉ định
  $exam_chidinh = DB::table('examseries')
  ->select('examseries.*' )
  ->where('is_paid', '=', 2)
  ->get();
  $data['option_exam_chidinh'] = array_pluck($exam_chidinh, 'title', 'id');      
  $classes = DB::table('classes')
  ->select('classes.*' )
  ->where('id', '=', $slug)
  ->first();
  $class_exam = ClassesExam::where('id','=',$slug_exam)->first();
    // echo "<pre>";
    // print_r ($class_exam);
    // echo "</pre>"; exit;
  $data['class_exam']   = $class_exam;
  $data['class_name']   = $classes->name;
  $data['records']      = FALSE;
  $data['slug']         = $slug;
  $data['user']         = $user;
  $data['title']        = 'Chỉnh sửa đề thi lớp: ' . $classes->name;
  $data['active_class'] = 'exam-list';
  $data['layout']       = getLayout();
  $view_name = 'admin.parent.exam-list-edit';
  return view($view_name, $data);
}
public function examListUpdate(Request $request, $slug)
{
	 $user = getUserWithSlug();
	 if(!checkRole(getUserGrade(4)))
	 {
	  prepareBlockUserMessage();
	  return back();
	}
	$message = '';
	$hasError = 0;
	DB::beginTransaction();
	$classes_exam = new ClassesExam();
	$classes_exam->classes_id = $slug;
	$classes_exam->exam_id = $request->exam_id;
	$classes_exam->start_date = date('Y-m-d H:i',(strtotime($request->start_date)));
	$classes_exam->end_date = date('Y-m-d H:i',(strtotime($request->end_date)));

	if (!empty($request->exam_id) && !empty($request->exam_id) && !empty($request->exam_id)) {
	  try{
		$classes_exam->save();
		DB::commit();
		$message = 'Bài thi đã được lưu';
	  }
	  catch(Exception $ex){
		DB::rollBack();
		$hasError = 1;
		$message = $ex->getMessage();
	  }
	}
	if(!$hasError)
	  flash('Thêm bài thi thành công', $message, 'success');
	else 
	  flash('Ooops',$message, 'error');
	return back();
	}
	public function editExamClassUpdate(Request $request, $slug, $slug_exam)
	{
	 if(!checkRole(getUserGrade(4)))
	 {
	  prepareBlockUserMessage();
	  return back();
	}
	$message = '';
	$hasError = 0;
	DB::beginTransaction();
	$classes_exam = ClassesExam::where('id','=',$slug_exam)->get()->first();
	$classes_exam->exam_id = $request->exam_id;
	$classes_exam->start_date = date('Y-m-d H:i',(strtotime($request->start_date)));
	$classes_exam->end_date = date('Y-m-d H:i',(strtotime($request->end_date)));
	if (!empty($request->exam_id) && !empty($request->exam_id) && !empty($request->exam_id)) {
	  try{
		$classes_exam->save();
		DB::commit();
		$message = 'Bài thi đã được lưu';
	  }
	  catch(Exception $ex){
		DB::rollBack();
		$hasError = 1;
		$message = $ex->getMessage();
	  }
	}
	if(!$hasError)
	  flash('Sửa đề thi thành công', $message, 'success');
	else 
	  flash('Ooops',$message, 'error');
	return back();
}
     /**
     * This method returns the datatables data to view
     * @return [type] [description]
     */
     public function getClassDatatable($slug)
     {
      $records = array();
      $user = getUserWithSlug($slug);
        // $records = User::select(['name', 'email', 'image', 'slug', 'id'])->where('parent_id', '=', $user->id)->get();
      $records = Classes::select(['name','created_at', 'id'])->where('teacher_id', '=', $user->id)->orderby('created_at','desc')->get();
      return DataTables::of($records)
      ->addColumn('action', function ($records) {
        return '<div class="dropdown more">
        <a id="dLabel" type="button" class="more-dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <i class="mdi mdi-dots-vertical"></i>
        </a>
        <ul class="dropdown-menu" aria-labelledby="dLabel">
        <li><a href="'.PREFIX.'lms/class/'.$records->id.'"><i class="fa fa-list"></i>Xem danh sách khóa học</a></li>
        <li><a href="'.URL_PARENT_EXAM_LIST.$records->id.'"><i class="fa fa-list"></i>Xem danh sách đề</a></li>
        <li><a href="'.URL_PARENT_CHILDREN.$records->id.'"><i class="fa fa-eye"></i>Xem học viên</a></li>
        </ul>
        </div>';
      })
      ->editColumn('name', function($records)
      {
        return $records->name;
      })
      ->editColumn('created_at', function($records){
        return $records->created_at;
      })
      ->removeColumn('id')
      ->rawColumns(['action'])
      ->make();
    }
     /**
     * This method returns the datatables data to view
     * @return [type] [description]
     */
     public function getDatatable($slug)
     {
      $records = array();
      $user = getUserWithSlug($slug);
      $records = User::join('classes_user', 'classes_user.student_id', '=', 'users.id')
      ->select(['name', 'email', 'image', 'slug', 'users.id'])->where('classes_user.classes_id', '=', $slug)->get();
      return DataTables::of($records)
      ->addColumn('action', function ($records) {
       $buy_package = '';
        return '<div class="dropdown more">
      <a id="dLabel" type="button" class="more-dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
      <i class="mdi mdi-dots-vertical"></i>
      </a>
      <ul class="dropdown-menu" aria-labelledby="dLabel">
      <li><a href="'.URL_USERS_EDIT.$records->slug.'"><i class="fa fa-pencil"></i>'.getPhrase("edit").'</a></li>
      </ul>
      </div>';
    })
      ->editColumn('name', function($records)
      {
        return '<a href="'.URL_USER_DETAILS.$records->slug.'" title="'.$records->name.'">'.ucfirst($records->name).'</a>';
      })       
      ->editColumn('image', function($records){
        return '<img src="'.getProfilePath($records->image).'"  />';
      })
      ->removeColumn('slug')
      ->removeColumn('id')
      ->rawColumns(['action', 'name', 'image'])
      ->make();
    }
    public function getExamDatatable($slug)
    {
      $records = array();
        //$user = getUserWithSlug($slug);
        /*$records = ExamSeries::join('classes_user', 'classes_user.student_id', '=', 'users.id')
        ->select(['name', 'email', 'image', 'slug', 'users.id'])->where('classes_user.classes_id', '=', $slug)->get();
            */
        $records = ClassesExam::join('examseries', 'examseries.id', '=', 'classes_exam.exam_id')
        ->select(['classes_exam.id', 'examseries.title', 'examseries.slug as examseries_slug','classes_exam.start_date', 'classes_exam.end_date', 'classes_exam.classes_id', 'examseries.category_id', 'examseries.id as examseries_id'])->where('classes_id','=',$slug)->get();
        return DataTables::of($records)
        ->addColumn('action', function ($records) {
         $buy_package = '';
        
          return '<div class="dropdown more">
        <a id="dLabel" type="button" class="more-dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <i class="mdi mdi-dots-vertical"></i>
        </a>
        <ul class="dropdown-menu" aria-labelledby="dLabel">
        <li><a style="white-space: pre-line; line-height: 1.6;" href="/parent/classmark/'.$records->classes_id.'/'.$records->examseries_id.'/'.$records->category_id.'"><i class="fa fa-file-o"></i>Xem điểm & Đánh giá</a></li>
        <li><a href="/exams/view-exam-series/'.$records->examseries_slug.'"><i class="fa fa-eye"></i>Xem đề thi</a></li>
        <li><a href="/parent/exam_list/edit/'.$records->classes_id.'/'.$records->id.'"><i class="fa fa-edit"></i>Sửa</a></li>
        <li><a href="javascript:void(0);" onclick="deleteRecord(\''.$records->id.'\');"><i class="fa fa-trash"></i>Xóa</a></li>
        </ul>
        </div>';
      })
        ->editColumn('title', function($records)
        {
          return $records->title;
        })       
        //  ->editColumn('classes_id', function($records){
        //     return 2;
        // })
        //  ->editColumn('exam_id', function($records){
        //     return 2;
        // })
        ->editColumn('start_date', function($records){
          return $records->start_date;
        })
        ->removeColumn('id')
        ->removeColumn('classes_id')
        ->removeColumn('category_id')
        ->removeColumn('examseries_id')
        ->removeColumn('examseries_slug')
        ->rawColumns(['action'])
        ->make();
      }

      

      public function childrenAnalysis()
      {
       $user = getUserWithSlug();
       if(!checkRole(getUserGrade(4)))
       {
        prepareBlockUserMessage();
        return back();
      }
      if(!isEligible($user->slug))
        return back();
      $data['records']      = FALSE;
      $data['user']       = $user;
      $data['title']        = 'Phân tích học viên';
      $data['active_class'] = 'analysis';
      $data['layout']       = getLayout();
       // return view('parent.list-users', $data);
      $view_name = 'admin.parent.list-users';
      return view($view_name, $data);     
    }
    public function delete($slug)
    {
      if(!checkRole(getUserGrade(4)))
      {
        prepareBlockUserMessage();
        return back();
      }
      /**
       * Delete the questions associated with this ClassesExam first
       * Delete the ClassesExam
       * @var [id]
       */
      $record = ClassesExam::where('id', '=', $slug)->get()->first();
      try{
        $record->delete();
        $response['status'] = 1;
        $response['message'] = 'Bạn đã xóa thành công';
      } catch (Exception $e) {
        $response['status'] = 0;
        if(getSetting('show_foreign_key_constraint','module'))
          $response['message'] =  $e->getMessage();
        else
          $response['message'] =  getPhrase('this_record_is_in_use_in_other_modules');
      }
      return json_encode($response);
    }

    public function ajaxn($subject_id) {

        //$list = Topic::getTopics($slug, 0);

        $list = DB::table('examseries')->where('category_id', $subject_id)->get();
           
        $parents =  array();
        array_push($parents, array('id'=>'', 'text' => '--Chọn bộ đề thi--'));

        foreach ($list as $key => $value) {
          $r = array('id'=>$value->id, 'text' => $value->title);
              array_push($parents, $r);
        }
        return json_encode($parents);
     
    }

    public function lmsClass($slug)
   {
     $user = getUserWithSlug();
     if(!checkRole(getUserGrade(4)))
     {
      prepareBlockUserMessage();
      return back();
    }

    //Get đề chỉ định
    $exam_chidinh = DB::table('examseries')
    ->select('examseries.*' )
    ->where('is_paid', '=', 2)
    ->get();
    $data['option_exam_chidinh'] = array_pluck($exam_chidinh, 'title', 'id');      
    $classes = DB::table('classes')
    ->select('classes.*' )
    ->where('id', '=', $slug)
    ->first();
    $data['categories']   = array_pluck(DB::table('quizcategories')->get(), 'category', 'id');
    $data['class_name']   = $classes->name;
    $data['records']      = FALSE;
    $data['slug']         = $slug;
    $data['user']         = $user;
    $data['title']        = 'Danh sách đề thi lớp: ' . $classes->name;
    $data['active_class'] = 'exam-list';
    $data['layout']       = getLayout();
    $view_name = 'admin.parent.exam-list';
    return view($view_name, $data);
  }
  
}