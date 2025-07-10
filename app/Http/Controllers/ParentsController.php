<?php
namespace App\Http\Controllers;
use \App;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\User;
use App\Classes;
use App\ClassesExam;
use App\ClassesUser;
use Yajra\DataTables\DataTables;
use DB;
use App\ExamScore;
use App\LmsClassSeries;
use App\LmsContent;
use App\LmsSeries;
use App\LmsStudentView;
use App\LmsTestResult;
use App\QuizResultfinish;
use App\Services\QuizResultFinishService;
use App\TestTokuteiResult;
use App\UserRoadmap;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Maatwebsite\Excel\Facades\Excel;

class ParentsController extends Controller
{
    private $quizResultFinishService;

    public function __construct(
        QuizResultFinishService $quizResultFinishService
    ) {
        $this->quizResultFinishService = $quizResultFinishService;

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
        <li><a href="'.PREFIX.'lmsseries/class/'.$records->id.'"><i class="fa fa-list"></i>Xem danh sách khóa học</a></li>
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

    public function lmsseriesClass($slug) {
        $user = getUserWithSlug();
        if (!checkRole(getUserGrade(4))) {
            prepareBlockUserMessage();
            return back();
        }

        $classes = DB::table('classes')
            ->select('classes.*')
            ->where('id', '=', $slug)
            ->first();
        $lms_options = LmsSeries::query()
            ->select('id', 'title', 'type_series', 'delete_status')
            ->where('delete_status', 0)
            ->get()
            ->groupBy('type_series')
            ->map(function ($group_items) {
                return $group_items->map(function ($item) {
                    return [
                        'id' => optional($item)->id ?? '',
                        'title' => optional($item)->title ?? '',
                    ];
                });
            })
            ->toArray();
        $lmsseries_types = [
            0 => 'Khoá học',
            1 => 'Khoá luyện thi',
        ];

        $data['lmsseries_types']   = $lmsseries_types;
        $data['lms_options']   = $lms_options;
        $data['class_name']   = $classes->name;
        $data['records']      = FALSE;
        $data['slug']         = $slug;
        $data['user']         = $user;
        $data['title']        = 'Danh sách đề thi lớp: ' . $classes->name;
        $data['active_class'] = 'exam-list';
        $data['layout']       = getLayout();
        $view_name = 'admin.parent.lmsseries-class';
        return view($view_name, $data);
    }

    public function lmsseriesClassGetDatatable($slug)
    {

        if(!checkRole(getUserGrade(4)))
        {
            prepareBlockUserMessage();
            return back();
        }

        $records = DB::table('lms_class_series')
            ->select(['lmsseries.id', 'lmsseries.title'])
            ->join('lmsseries', 'lmsseries.id', '=', 'lms_class_series.series_id')
            ->where('class_id', $slug)
            ->where('lms_class_series.delete_status', 0)
            ->get();

        return DataTables::of($records)
            ->addColumn('action', function ($record) use ($slug) {
                $class_series_id = $record->id;
                $link_data = '
                    <form class="dropdown more" method="POST" action="/lmsseries/class/delete/'. "$slug/$class_series_id" .'">
                        <input type="hidden" name="_token" value="'. csrf_token() .'">
                        <a id="dLabel" type="button" class="more-dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="mdi mdi-dots-vertical"></i>
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="dLabel">
                            <li>
                                <button style="white-space: pre-line;"><i class="fa fa-trash"></i> Xóa khoá học!</button>
                            </li>
                        </ul>
                    </form>
                ';

                return $link_data;
            })
            ->removeColumn('id')
            ->rawColumns(['action'])
            ->make();
    }

    public function lmsseriesClassAdd($class_id) {
        if(!checkRole(getUserGrade(4)))
        {
            prepareBlockUserMessage();
            return back();
        }

        $lmsseries_id = optional(request())->lmsseries_id ?? null;
        if (is_null($lmsseries_id) || is_null($class_id)) return back();

        LmsClassSeries::updateOrCreate(
            [
                'class_id' => $class_id,
                'series_id' => $lmsseries_id,
            ],
            [
                'created_by' => Auth::id(),
                'delete_status' => 0,
            ]
        );

        return back();
    }

    public function lmsseriesClassDelete($class_id, $lmsseries_id) {
        if(!checkRole(getUserGrade(4)))
        {
            prepareBlockUserMessage();
            return back();
        }
        DB::table('lms_class_series')
            ->where('class_id', $class_id)
            ->where('series_id', $lmsseries_id)
            ->update(['delete_status' => 1]);

        return back();
    }

    public function downloadProgressExcel() {
        $request = request();

        $business_name = $request->business_name ?? '';
        $class_id = $request->class_id;
        if (!$class_id) {
            return back()->withErrors(['message' => 'Lớp học không hợp lệ']);
        }

        $class = Classes::find($class_id);
        $class_name = $class->name ?? '';

        $lmsseries_ids = LmsClassSeries::query()
            ->where('class_id', $class_id)
            ->get()
            ->pluck('series_id')
            ->toArray();

        $lmsseries_titles = LmsSeries::query()
            ->whereIn('id', $lmsseries_ids)
            ->pluck('title')
            ->implode(', ');

        $result = ClassesUser::query()
            ->with(['user'])
            ->where('classes_id', $class_id)
            ->get()
            ->map(function ($class_user, $index) use ($lmsseries_titles, $lmsseries_ids) {
                $user = $class_user->user;

                if (empty($user)) {
                    return null;
                }

                return array_merge([
                    'lmsseries_name' => $lmsseries_titles,
                ], $this->getExportDataOfStudent($user, $lmsseries_ids));
            })
            ->filter()
            ->values()
            ->map(function ($item, $index) {
                return array_merge($item, ['order' => $index + 1]);
            })
            ->toArray();


        $path = storage_path('app/excel/class_progress_template.xlsx');
        $reader = \PHPExcel_IOFactory::createReader('Excel2007');
        $phpExcel = $reader->load($path);

        $sheet1 = $phpExcel->getSheetByName('Sheet1');
        $templateSheet = $phpExcel->getSheetByName('Sheet2');

        $sheet1->setCellValue("A1", mb_strtoupper("BÁO CÁO TIẾN TRÌNH HỌC LỚP $business_name"));
        $sheet1->setTitle('Tổng quan');

        // General sheet
        $general_start_row = 3;
        foreach($result as $index => $raw_data) {
            $active_row_index = $general_start_row + $index;
            $student_data = [
                $raw_data['order'],
                $raw_data['name'],
                $raw_data['hid'],
                $raw_data['lmsseries_name'],
                $raw_data['roadmap'],
                $raw_data['start_day'],
                $raw_data['learning_status'],
                $raw_data['video_watched'],
                $raw_data['completion_percentage'],
                $raw_data['last_learned_lesson'],
                $raw_data['structure_summary_test_pass_percentage'],
                $raw_data['general_test_avg_percentage'],
            ];

            $sheet1->fromArray($student_data, "Không có", "A$active_row_index");
        }

        // Student detail sheets
        $detail_start_row = 4;
        foreach($result as $index => $raw_data) {
            $active_row_index = $detail_start_row;
            $student_name = truncateWithEllipsis($raw_data['name']);

            $clone = clone $templateSheet;
            $clone->setTitle("C.Tiết \"$student_name\"");
            $clone->setCellValue("A1", mb_strtoupper("BÁO CÁO TIẾN TRÌNH HỌC \"$student_name\""));

            $student_data = collect([
                $class_name,
                $raw_data['hid'],
                $raw_data['lmsseries_name'],
                $raw_data['name'],
                $raw_data['start_day'],
                $raw_data['learning_status'],
                $raw_data['video_watched'],
                $raw_data['completion_percentage'],
                $raw_data['last_login_time'],
                $raw_data['last_learned_lesson'],
                $raw_data['missed_contents'],
                $raw_data['last_learned_view_time'],
                $raw_data['structure_summary_test_pass_statuses'],
                $raw_data['structure_summary_test_attempt_count'],
                $raw_data['last_exercise_done_time'],
                $raw_data['last_test_done_time'],
                $raw_data['last_exam_done_time'],
                $raw_data['lessons_learned_this_month'],
                $raw_data['general_test_highest_results'],
                $raw_data['mock_test_highest_results'],
                $raw_data['test_traffic_highest_results'],
                $raw_data['test_traffic_attempt_count'],
                $raw_data['test_tokutei_highest_results'],
                $raw_data['test_tokutei_attempt_count'],
            ])->map(function ($item) {
                return is_array($item)
                    ? (count($item) > 0 ? $item : ['Không có'])
                    : [$item];
            })->toArray();

            // dd($student_data);

            $clone->fromArray($student_data, null, "C$active_row_index");
            $phpExcel->addSheet($clone);
        }

        // Set active sheet when opening
        $phpExcel->setActiveSheetIndex(0);

        // Remove template sheet
        $phpExcel->removeSheetByIndex(
            $phpExcel->getIndex($templateSheet)
        );

        // Use Phpexcel Writer directly
        $writer = \PHPExcel_IOFactory::createWriter($phpExcel, 'Excel2007');

        // Create Response to download
        $filename = "Tiến trình học lớp $class_name.xlsx";
        $temp_file = tempnam(sys_get_temp_dir(), $filename);
        $writer->save($temp_file);

        return response()->download($temp_file, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ])->deleteFileAfterSend(true);
    }

    private function getExportDataOfStudent($user, $lmsseries_ids) {
        $user_id = $user->id;
        $date_time_format = 'Y-m-d H:i:s';
        $fallback_empty = 'Không có';

        $user_roadmaps = UserRoadmap::query()
            ->with('lmsSeries')
            ->where('user_id', $user_id)
            ->whereIn('lmsseries_id', $lmsseries_ids)
            ->get()
            ->map(function ($roadmap) {
                $duration_months = $roadmap->duration_months
                    ? $roadmap->duration_months . ' tháng'
                    : "Tự do";
                $lmsseries_name = optional($roadmap->lmsSeries)->title ?? '';

                return "$duration_months ($lmsseries_name)";
            })
            ->pipe(function ($roadmaps) {
                if ($roadmaps->isEmpty()) {
                    return "Chưa chọn lộ trình";
                }

                return $roadmaps->implode(', ');
            });

        $content_view_query = LmsStudentView::query()
            ->where('users_id', $user_id);
        $latest_content_query = (clone $content_view_query)
            ->with('lmsContent')
            ->latest('created_date');

        $last_learned_view = (clone $latest_content_query)->hasLmsContentByTypeAndSeries(
            array_merge(LmsContent::TESTS_LIST, LmsContent::EXERCISES_LIST, LmsContent::TEST_TOKUTEI_LIST),
            $lmsseries_ids, true
        )->first();
        $last_learned_lesson = optional(optional($last_learned_view)->lmsContent)->bai ?? $fallback_empty;

        $last_learned_view_time = optional($last_learned_view)->created_date ?? $fallback_empty;
        $last_test_done_time = optional(
            (clone $latest_content_query)->hasLmsContentByTypeAndSeries(
                LmsContent::TESTS_LIST, $lmsseries_ids
            )->first()
        )->created_date ?? $fallback_empty;
        $last_exercise_done_time = optional(
            (clone $latest_content_query)->hasLmsContentByTypeAndSeries(
                LmsContent::EXERCISES_LIST, $lmsseries_ids
            )->first()
        )->created_date ?? $fallback_empty;
        $last_exam_done_time = optional(
            optional(
                QuizResultfinish::where('user_id', $user_id)->latest('updated_at')->first()
            )->updated_at
        )->format($date_time_format) ?? $fallback_empty;
        $start_day = (clone $content_view_query)->min('created_date');
        $lessons_learned_this_month = (clone $content_view_query)->hasLmsContentByTypeAndSeries(
            array_merge(LmsContent::TESTS_LIST, LmsContent::EXERCISES_LIST, LmsContent::TEST_TOKUTEI_LIST),
            $lmsseries_ids, true
        )->whereRaw('MONTH(created_date) = ? AND YEAR(created_date) = ?', [
            date('m'), date('Y')
        ])->count();

        $missed_contents = (clone $content_view_query)
            ->with('lmsContent.lmsseriesContent')
            ->whereHas('lmsContent', function ($query) use ($lmsseries_ids) {
                $query->whereIn('lmsseries_id', $lmsseries_ids);
            })
            ->get()
            ->groupBy(function ($view) {
                $series = optional(optional($view)->lmsContent)->lmsseriesContent ?? null;
                return optional($series)->id;
            })
            ->flatMap(function ($lmsseries_views, $series_id) {
                $max_order_content_view = $lmsseries_views->sortByDesc(function ($view) {
                    return optional($view->lmsContent)->stt ?? 0;
                })->first();
                $max_order_content = optional($max_order_content_view)->lmsContent;

                if (is_null($max_order_content)) {
                    return [];
                }

                $learned_content = LmsContent::query()
                    ->where('lmsseries_id', $series_id)
                    ->where('delete_status', 0)
                    ->whereNotIn('type', [0, 8])
                    ->where('stt', '<=', $max_order_content->stt)
                    ->get();
                $learned_content_ids = $learned_content->pluck('id');

                $missed_content_ids = array_diff(
                    $learned_content_ids->toArray(),
                    $lmsseries_views->pluck('lmscontent_id')->toArray()
                );

                return $learned_content
                    ->whereIn('id', $missed_content_ids)
                    ->pluck('bai')
                    ->toArray();
            });

        $structure_summary_test_done = (clone $content_view_query)
            ->with('lmsContent.lmsseriesContent')
            ->whereHas('lmsContent', function ($query) use ($lmsseries_ids) {
                $query
                    ->whereIn('type', [LmsContent::PARTIAL_EXERCISE, LmsContent::SUMMARY_EXERCISE])
                    ->whereIn('lmsseries_id', $lmsseries_ids);
            })
            ->get();

        $structure_summary_test_done_statuses = $structure_summary_test_done
            ->groupBy(function ($view) {
                $series = optional(optional($view)->lmsContent)->lmsseriesContent ?? null;
                return optional($series)->id;
            })
            ->flatMap(function ($lmsseries_views) {
                return $lmsseries_views->map(function ($view) {
                    $lmscontent = $view->lmsContent;
                    $test_name = optional($lmscontent)->bai;
                    $test_pass_status = $view->finish ? "Đạt" : "Không đạt";
                    $view_time = $view->view_time ?? 0;
                    $retry_time = max($view_time - 1, 0);

                    return [
                        'pass_status' => "$test_name: $test_pass_status",
                        'attempt_count' => "$test_name: $retry_time lần",
                    ];
                });
            })->toArray();

        $structure_summary_test_done_count = $structure_summary_test_done->count();
        $structure_summary_test_count = LmsContent::query()
            ->whereIn('type', [LmsContent::PARTIAL_EXERCISE, LmsContent::SUMMARY_EXERCISE])
            ->whereIn('lmsseries_id', $lmsseries_ids)
            ->get()
            ->count();
        $structure_summary_test_pass_percentage = ((int) $structure_summary_test_count !== 0)
            ? (int) (($structure_summary_test_done_count * 100) / $structure_summary_test_count)
            : 0;

        $general_test_results = LmsTestResult::query()
            ->with('lmsContent')
            ->whereHas('lmsContent', function ($query) use ($lmsseries_ids) {
                $query
                    ->where('type', LmsContent::TEST)
                    ->whereIn('lmsseries_id', $lmsseries_ids);
            })
            ->where('users_id', $user_id)
            ->get();
        $tokutei_test_results = LmsTestResult::query()
            ->with('lmsContent')
            ->whereHas('lmsContent', function ($query) use ($lmsseries_ids) {
                $query
                    ->whereIn('type', LmsContent::TEST_TOKUTEI_LIST)
                    ->whereIn('lmsseries_id', $lmsseries_ids);
            })
            ->where('users_id', $user_id)
            ->get()
            ->groupBy('lmscontent_id')
            ->map(function ($group) {
                $highest_point_result = $group->sortByDesc('point')->first();
                $lmscontent_title = optional($highest_point_result->lmsContent)->bai ?? '';
                $student_point = $highest_point_result->point;
                $total_point = $highest_point_result->total_point;
                $attempt_count = optional($group)->count() ?? 0;

                return [
                    'highest_score' => "$lmscontent_title: $student_point/$total_point",
                    'attempt_count' => "$lmscontent_title: $attempt_count lần",
                ];
            })
            ->toArray();

        $traffic_test_results = LmsTestResult::query()
            ->with('lmsContent')
            ->whereHas('lmsContent', function ($query) use ($lmsseries_ids) {
                $query
                    ->where('type', LmsContent::TEST_TRAFFIC_RULE)
                    ->whereIn('lmsseries_id', $lmsseries_ids);
            })
            ->where('users_id', $user_id)
            ->get()
            ->groupBy('lmscontent_id')
            ->map(function ($group) {
                $highest_point_result = $group->sortByDesc('point')->first();
                $lmscontent_title = optional($highest_point_result->lmsContent)->bai ?? '';
                $student_point = $highest_point_result->point;
                $total_point = $highest_point_result->total_point;
                $attempt_count = optional($group)->count() ?? 0;

                return [
                    'highest_score' => "$lmscontent_title: $student_point/$total_point",
                    'attempt_count' => "$lmscontent_title: $attempt_count lần",
                ];
            })
            ->toArray();

        $general_test_avg_percentage = $general_test_results
            ->pipe(function ($collection) {
                $total_point = $collection->count() * 100;
                if (empty($total_point)) return 0;

                $total_student_point = $collection->pluck('point')->sum();
                return (int) (($total_student_point * 100) / $total_point);
            });
        $general_test_highest_results = $general_test_results
            ->groupBy('lmscontent_id')
            ->map(function ($group) {
                $highest_point_result = $group->sortByDesc('point')->first();
                $lmscontent_title = optional($highest_point_result->lmsContent)->bai ?? '';
                $student_point = $highest_point_result->point;
                $total_point = $highest_point_result->total_point;

                return "$lmscontent_title: $student_point/$total_point";
            });

        $mock_test_highest_results = $this->getMockTestHighestResults($user_id);

        $check_date_null_year = 2000;
        $last_login_time = optional($user)->last_login_date;
        $last_login_time = (optional($last_login_time)->year ?? 0) > $check_date_null_year
            ? $last_login_time->format($date_time_format)
            : "Chưa đăng nhập";

        $learning_statuses = LmsSeries::query()
            ->withCount(['lmscontents as video_watched' => function ($query) use ($user_id) {
                $query->whereHas('lmsStudentView', function ($query) use ($user_id) {
                    $query->where('users_id', $user_id);
                });
            }])
            ->withCount(['lmscontents as total_lessons' => function ($query) {
                $query
                    ->whereNotIn('type', [0, 8])
                    ->where('delete_status', 0);
            }])
            ->whereIn('id', $lmsseries_ids)
            ->get()
            ->map(function ($lmsseries) {
                $lmsseries_title = $lmsseries->title;
                $video_watched = $lmsseries->video_watched;
                $total_lessons = $lmsseries->total_lessons;
                $status = $video_watched === 0
                    ? 'Chưa bắt đầu'
                    : ($video_watched == $total_lessons ? 'Hoàn thành' : 'Đang học');

                return [
                    'status' => $status . " ($lmsseries_title)",
                    'video_watched' => 'Đã xem ' . "$video_watched/$total_lessons" . " ($lmsseries_title)",
                    'completion_percentage' => (int) (($video_watched * 100) / $total_lessons) . "% số bài học ($lmsseries_title)",
                    'number_video_watched' => $video_watched,
                    'number_total_lesson' => $total_lessons,
                ];
            });

        return [
            "name" => $user->name,
            "hid" => $user->hid,
            "roadmap" => $user_roadmaps,
            "start_day" => $start_day,
            "learning_status" => $learning_statuses->pluck('status')->implode(', '),
            "video_watched" => $learning_statuses->pluck('video_watched')->implode(', '),
            "completion_percentage" => $learning_statuses->pluck('completion_percentage')->implode(', '),
            "last_learned_lesson" => $last_learned_lesson,
            "structure_summary_test_pass_percentage" => "Đạt " . $structure_summary_test_pass_percentage . '% số lần làm bài tập',
            "structure_summary_test_pass_statuses" => array_column($structure_summary_test_done_statuses, 'pass_status'),
            "structure_summary_test_attempt_count" => array_column($structure_summary_test_done_statuses, 'attempt_count'),
            "general_test_avg_percentage" => "Làm được " . (int) $general_test_avg_percentage . '% trên tổng số điểm',
            "general_test_highest_results" => $general_test_highest_results->toArray(),
            "mock_test_highest_results" => $mock_test_highest_results->toArray(),
            "last_login_time" => $last_login_time,
            "last_learned_view_time" => $last_learned_view_time,
            "last_exercise_done_time" => $last_exercise_done_time,
            "last_test_done_time" => $last_test_done_time,
            "last_exam_done_time" => $last_exam_done_time,
            "lessons_learned_this_month" => strval($lessons_learned_this_month),
            "missed_contents" => $missed_contents->toArray(),
            "test_traffic_highest_results" => array_column($traffic_test_results, 'highest_score'),
            "test_traffic_attempt_count" => array_column($traffic_test_results, 'attempt_count'),
            "test_tokutei_highest_results" => array_column($tokutei_test_results, 'highest_score'),
            "test_tokutei_attempt_count" => array_column($tokutei_test_results, 'attempt_count'),
        ];
    }

    /**
     * Get data for mock exam
     *
     * @param string $user_id
     * @return array
     */
    private function getMockTestHighestResults(string $user_id)
    {
        return QuizResultfinish::query()
            ->join('examseries', 'examseries.id', '=', 'quizresultfinish.examseri_id')
            ->where('quizresultfinish.user_id', '=', $user_id)
            ->orderBy('quizresultfinish.created_at', 'desc')
            ->select(
                'examseries.title',
                'examseries.category_id',
                'quizresultfinish.id',
                'quizresultfinish.created_at',
                'quizresultfinish.finish',
                'quizresultfinish.quiz_1_total',
                'quizresultfinish.quiz_2_total',
                'quizresultfinish.quiz_3_total',
                'quizresultfinish.total_marks',
                'quizresultfinish.status'
            )
            ->get()
            ->map(function ($result) {
                $total_mark = $result->quiz_1_total + $result->quiz_2_total + (
                    $result->category_id <= 3 ? $result->quiz_3_total : 0
                );
                $result->total_mark = $total_mark;

                return $result;
            })
            ->groupBy('id')
            ->map(function ($group) {
                $highest_point_result = $group->sortByDesc('total_mark')->first();
                $test_title = $highest_point_result->title ?? '';
                $student_point = $highest_point_result->total_mark;

                return "$test_title: $student_point/180";
            });
    }
}