<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Requests;
use \App;
use App\Subject;
use App\LmsSeries;
use App\QuizResult;
use App\QuizResultfinish;
use App\QuizResultReview;
use Yajra\DataTables\DataTables;
use DB;
use Auth;
use Image;
use ImageSettings;
use File;
use Input;
use Excel;
use Illuminate\Support\Facades\Validator;

class LmsExamController extends Controller
{
	public function __construct()
	{
		$this->middleware('auth');
	}
	/**
	 * Course listing method
	 * @return Illuminate\Database\Eloquent\Collection
	 */

	
	public function index(){

		if(!checkRole(getUserGrade(2))){
			prepareBlockUserMessage();
			return back();
		}

		$data['URL_IMPORT_CONTENT']     = PREFIX."lms/series/import-excel";
		$data['create_url']			= PREFIX.'lms/seriessexam/add';
		$data['datatbl_url']		= PREFIX.'lms/series/getExamList';
		$data['active_class']       = 'lms';
		$data['title']              = 'Khóa học';
		// dd($data);
		$view_name = 'admin.lmsexam.lmsseries.list';
		return view($view_name, $data);
	}
	/**
	 * This method returns the datatables data to view
	 * @return [type] [description]
	 */
	public function getDatatable(){
		if(!checkRole(getUserGrade(2))){
			prepareBlockUserMessage();
			return back();
		}
		$records = array();
		$records = LmsSeries::select(['lmsseries.title','lmsseries.slug', 'lmsseries.id', 'lmsseries.updated_at'])
			->where([
				['delete_status',0],
				['type_series',1]
			])
			->orderBy('order_by', 'asc');
		return DataTables::of($records)
		->addColumn('action', function ($records) {
			$link_data = '<div class="dropdown more">
			<a id="dLabel" type="button" class="more-dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
			<i class="mdi mdi-dots-vertical"></i>
			</a>
			<ul class="dropdown-menu dropdown-menu-right" aria-labelledby="dLabel">
			<li><a href="'.$records->slug.'/content"><i class="fa fa-spinner"></i>'.getPhrase("update").'</a></li>
			<li><a href="'.'seriessexam/edit/'.$records->slug.'"><i class="fa fa-pencil"></i>'.getPhrase("edit").'</a></li>';
			$temp = '';
			if(checkRole(getUserGrade(1))) {
				$temp .= ' <li><a href="javascript:void(0);" onclick="deleteRecord(\''.$records->slug.'\');"><i class="fa fa-trash"></i>'. getPhrase("delete").'</a></li>';
			}
			$temp .='</ul></div>';
			$link_data .=$temp;
			return $link_data;
		})
		->editColumn('title', function($records)
		{
			return '<a href="'.PREFIX.'lms/'.$records->slug.'/content'.'">'.$records->title.'</a>';
		})
		->editColumn('cost', function($records)
		{
			return ($records->is_paid) ? $records->cost : '-';
		})
		->editColumn('is_paid', function($records)
		{
			return ($records->is_paid) ? '<span class="label label-primary">Trả phí</span>' : '<span class="label label-success">'.getPhrase('free').'</span>';
		})
		->removeColumn('id')
		->removeColumn('slug')
		->removeColumn('updated_at')
		->rawColumns(['title', 'is_paid', 'action'])
		->make();
	}
	/**
	 * This method loads the create view
	 * @return void
	 */
	public function create(){
		if(!checkRole(getUserGrade(2))){
			prepareBlockUserMessage();
			return back();
		}

		$data['URL_LMS_SERIES'] 	= PREFIX.'lms/series';
		$data['URL_LMS_SERIES_ADD'] = PREFIX.'lms/series/add';
		$data['record']           	= FALSE;
		$data['active_class']       = 'lms';
		$data['title']              = getPhrase('add_series');
	  // return view('lms.lmsseries.add-edit', $data);
		$view_name = 'admin.lms.lmsseries.add-edit';
		return view($view_name, $data);
	}
	/**
	 * This method loads the edit view based on unique slug provided by user
	 * @param  [string] $slug [unique slug of the record]
	 * @return [view with record]
	 */
	public function edit($slug){
		if(!checkRole(getUserGrade(2)))
		{
			prepareBlockUserMessage();
			return back();
		}
		$record = LmsSeries::getRecordWithSlug($slug);
		if($isValid = $this->isValidRecord($record))
			return redirect($isValid);

		$data['URL_LMS_SERIES'] 	= PREFIX.'lms/series';
		$data['URL_LMS_SERIES_ADD'] = PREFIX.'lms/series/add';
		$data['URL_LMS_SERIES_EDIT']= PREFIX.'lms/series/edit/';
		$data['record']           	= $record;
		$data['active_class']     	= 'lms';
		$data['settings']         	= FALSE;
		$data['title']            	= getPhrase('edit_series');
	  // return view('lms.lmsseries.add-edit', $data);
		$view_name = 'admin.lms.lmsseries.add-edit';
		return view($view_name, $data);
	}
	/**
	 * Update record based on slug and reuqest
	 * @param  Request $request [Request Object]
	 * @param  [type]  $slug    [Unique Slug]
	 * @return void
	 */
	public function update(Request $request,$slug){
		if(!checkRole(getUserGrade(2)))
		{
			prepareBlockUserMessage();
			return back();
		}
		$record = LmsSeries::getRecordWithSlug($slug);

		$rules = ['title'      => 'bail|required|max:30'];
		 /**
		* Check if the title of the record is changed,
		* if changed update the slug value based on the new title
		*/
		$name = $request->title;
		if($name != $record->title)
			$record->slug = createSlug($name);
	   //Validate the overall request
		$this->validate($request, $rules);
		if($request->has('show_in_front')){
			if($request->is_paid == 1 && $request->show_in_front == 1){
				flash('Ooops...!','home_page_series_must_be_free_type_series','overlay');
				return back();
			}
			else{
				$record->show_in_front   = $request->show_in_front;
			}
		}
		$record->title        = $name;
		$record->is_paid      = $request->is_paid;
		$record->lms_category_id      = $request->lms_category_id;
		$record->validity     = -1;
		$record->cost       = 0;
		if($request->is_paid) {
			$record->validity   = $request->validity;
			$record->cost     = $request->cost;
		}
		$record->total_items    = $request->total_items;
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
			$examSettings = getSettings('lms');
			$path = $examSettings->seriesImagepath;
			$this->deleteFile($record->image, $path);
			$record->image      = $this->processUpload($request, $record,$file_name);
			$record->save();
		}
		flash('success','record_updated_successfully', 'success');
		return redirect(PREFIX.'lms/series');
	}
	/**
	 * This method adds record to DB
	 * @param  Request $request [Request Object]
	 * @return void
	 */
	public function store(Request $request){
		if(!checkRole(getUserGrade(2))){
			prepareBlockUserMessage();
			return back();
		}
		// dd($request->request);
		$rules = [
			'title'               => 'bail|required|max:30' ,
		];
		  // dd($request);
		$this->validate($request, $rules);
		$record = new LmsSeries();
		if($request->has('show_in_front')){
			if($request->is_paid == 1 && $request->show_in_front == 1){
				flash('Ooops...!','home_page_series_must_be_free_type_series','overlay');
				return back();
			}
			else{
				$record->show_in_front   = $request->show_in_front;
			}
		}
		$name                 =  $request->title;
		$record->title        = $name;
		$record->slug         = createSlug($name);
		$record->cost       = 0;

		$record->total_items    = $request->total_items;
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
			$examSettings = getSettings('lms');
			$path = $examSettings->seriesImagepath;
			$this->deleteFile($record->image, $path);
			$record->image      = $this->processUpload($request, $record,$file_name);
			$record->save();
		}
		flash('success','record_added_successfully', 'success');
		return redirect(PREFIX.'lms/series');
	}
	public function deleteFile($record, $path, $is_array = FALSE){
		if(env('DEMO_MODE')) {
			return;
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
	public function processUpload(Request $request, $record, $file_name){
		if(env('DEMO_MODE')) {
			return 'demo';
		}
		if ($request->hasFile($file_name)) {
			$examSettings = getSettings('lms');
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
	public function delete($categories,$slug){
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
	  $record = LmsSeries::where('slug', $slug)->first();
	  if(!$record)
	  {
	  	$response['status'] = 0;
	  	$response['message'] = getPhrase('invalid_record');
	  	return json_encode($response);
	  }
	  try{
	  	if(!env('DEMO_MODE')) {
	  		$record->delete();
	  	}
	  	$response['status'] = 1;
	  	$response['message'] = getPhrase('record_deleted_successfully');
	  }
	  catch ( \Illuminate\Database\QueryException $e) {
	  	$response['status'] = 0;
	  	if(getSetting('show_foreign_key_constraint','module'))
	  		$response['message'] =  $e->errorInfo;
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
		return URL_LMS_SERIES;
	}
	/**
	 * Returns the list of subjects based on the requested subject
	 * @param  Request $request [description]
	 * @return [type]           [description]
	 */
	public function getSeries(Request $request)
	{
		$category_id  = $request->category_id;
		$items      = App\LmsContent::where('subject_id','=',$category_id)
		->get();
		return json_encode(array('items'=>$items));
	}
	/**
	 * Updates the questions in a selected quiz
	 * @param  [type] $slug [description]
	 * @return [type]       [description]
	 */
	public function updateSeries($slug)
	{
		if(!checkRole(getUserGrade(2)))
		{
			prepareBlockUserMessage();
			return back();
		}
	  /**
	   * Get the Quiz Id with the slug
	   * Get the available questions from questionbank_quizzes table
	   * Load view with this data
	   */
	  $record = LmsSeries::getRecordWithSlug($slug);
	  $data['record']           = $record;
	  $data['active_class']       = 'lms';
	  $data['right_bar']          = TRUE;
	  $data['right_bar_path']     = 'lms.lmsseries.right-bar-update-lmslist';
	  $data['categories']         = array_pluck(App\Subject::all(),'subject_title', 'id');
	  $data['settings']           = FALSE;
	  $previous_records = array();
	  if($record->total_items > 0)
	  {
	  	$series = DB::table('lmsseries_data')
	  	->where('lmsseries_id', '=', $record->id)
	  	->get();
	  	foreach($series as $r)
	  	{
	  		$temp = array();
	  		$temp['id']   = $r->lmscontent_id;
	  		$series_details = App\LmsContent::where('id', '=', $r->lmscontent_id)->first();
			  // dd($series_details);
	  		$temp['content_type'] = $series_details->content_type;
	  		$temp['code']      = $series_details->code;
	  		$temp['title']     = $series_details->title;
	  		array_push($previous_records, $temp);
	  	}
	  	$settings['contents'] = $previous_records;
	  	$data['settings']           = json_encode($settings);
	  }
	  $data['exam_categories']        = array_pluck(App\QuizCategory::all(),
	  	'category', 'id');
	  // $data['categories']        = array_pluck(QuizCategory::all(), 'category', 'id');
	  $data['title']              = getPhrase('update_series_for').' '.$record->title;
	  // return view('lms.lmsseries.update-list', $data);
	  $view_name = 'admin.lms.lmsseries.update-list';
	  return view($view_name, $data);
	}
	public function storeSeries(Request $request, $slug)
	{
		if(!checkRole(getUserGrade(2)))
		{
			prepareBlockUserMessage();
			return back();
		}
		$lms_series = LmsSeries::getRecordWithSlug($slug);
		$lmsseries_id  = $lms_series->id;
		$contents   = json_decode($request->saved_series);
		$contents_to_update = array();
		foreach ($contents as $record) {
			$temp = array();
			$temp['lmscontent_id'] = $record->id;
			$temp['lmsseries_id'] = $lmsseries_id;
			array_push($contents_to_update, $temp);
		}
		$lms_series->total_items = count($contents);
		if(!env('DEMO_MODE')) {
		//Clear all previous questions
			DB::table('lmsseries_data')->where('lmsseries_id', '=', $lmsseries_id)->delete();
		//Insert New Questions
			DB::table('lmsseries_data')->insert($contents_to_update);
			$lms_series->save();
		}
		flash('success','record_updated_successfully', 'success');
		return redirect(URL_LMS_SERIES);
	}
	/**
	 * This method lists all the available exam series for students
	 *
	 * @return [type] [description]
	 */



	public function listCategories(){
		$data['active_class']       = 'lms';
		$data['title']              = 'Khóa học';
		// $data['series']         	= LmsSeries::paginate((new App\GeneralSettings())->getPageLength());
		$data['series'] = DB::table('lmsseries')
			->join('lms_class','lmsseries.id','=','lms_class.lmsseries_id')
			->join('classes','lms_class.classes_id','=','classes.id')
			->join('classes_user','classes_user.classes_id','=','classes.id')
			->where([
				['classes_user.student_id',Auth::user()->id],
				['lmsseries.delete_status',0]
			])
			->get();
		$data['series_selected'] = DB::table('lmsseries')
			->join('lms_class','lmsseries.id','=','lms_class.lmsseries_id')
			->join('classes','lms_class.classes_id','=','classes.id')
			->join('classes_user','classes_user.classes_id','=','classes.id')
			->join('lms_class_series','lms_class_series.series_id','=','lmsseries.id')
			->where([
				['classes_user.student_id',Auth::user()->id],
				['lmsseries.delete_status',0]
			])
			->get();
		// dump($data['series']);
		$data['layout']              = getLayout();
	   // return view('student.exams.exam-series-list', $data);
		$view_name = 'admin.student.lms.lms-categories-list';
		return view($view_name, $data);
	}

	public function listSeries($slug = ''){

		 if(!globalCheck(0,$slug)){
              flash('error','Thông báo sẽ tự đóng sau 1s', 'error');
            return redirect('dashboard');
         }

		$data['active_class']       = 'lms';
		$data['title']              = 'Series Khóa học';
		// $data['series']         	= LmsSeries::paginate((new App\GeneralSettings())->getPageLength());
		$data['series'] = DB::table('lmsseries')
			->select('lmsseries.*')
			->join('lmscategories','lmscategories.id','=','lmsseries.lms_category_id')
			->join('lms_class','lmscategories.id','=','lms_class.lmscategories_id')
			->join('classes','lms_class.classes_id','=','classes.id')
			->join('classes_user','classes_user.classes_id','=','classes.id')
			->where([
				['classes_user.student_id',Auth::user()->id],
				['lmscategories.slug',$slug]
			])
			->get();
		$data['categories'] = DB::table('lmscategories')
			->select(['category','slug'])
			->where('slug',$slug)
			->get()->first();
		// dd($data['series']);
		if($data['series']->isEmpty()){
			flash('Ooops...!', getPhrase("page_not_found"), 'error');
        	return back();
		}
		$data['layout']              = getLayout();
		$data['url_categories'] 		 = PREFIX.'lms/exam-categories/list';
	   // return view('student.exams.exam-series-list', $data);
		$view_name = 'admin.student.lms.lms-series-list';
		return view($view_name, $data);
	}
	/**
	 * This method displays all the details of selected exam series
	 * @param  [type] $slug [description]
	 * @return [type]       [description]
	 */
	public function viewItem($slug)
	{
		$record = LmsSeries::getRecordWithSlug($slug);
		if($isValid = $this->isValidRecord($record))
			return redirect($isValid);
		$data['active_class']       = 'exams';
		$data['pay_by']             = '';
		$data['title']              = $record->title;
		$data['item']               = $record;
		$data['right_bar']          = TRUE;
		$data['right_bar_path']     = 'student.exams.exam-series-item-view-right-bar';
		$data['right_bar_data']     = array(
			'item' => $record,
		);
		$data['layout']              = getLayout();
	   // return view('student.exams.exam-series-view-item', $data);
		$view_name = 'admin.student.exams.exam-series-view-item';
		return view($view_name, $data);
	}

	public function importExcel(Request $request){
    if($request->hasFile('file')){
      $path = $request->file('file')->getRealPath();
      config(['excel.import.startRow' => 1]);
      $data = Excel::selectSheetsByIndex(0)->load($path,function($reader){
        $reader->noHeading();
      })->get();
			dd($data);
      if(!empty($data) && $data->count()){

        $list_content_q = DB::table('lmscontents')
        ->select(['lmsseries_data.lmscontent_id','lmscontents.stt'])
        ->join('lmsseries_data','lmsseries_data.lmscontent_id','=','lmscontents.id')
        ->join('lmsseries','lmsseries_data.lmsseries_id','=','lmsseries.id')
        ->where([
          ['lmsseries.slug',$request->series_slug],
          ['lmscontents.parent_id','<>',0]
        ])
        ->orderBy('stt','asc')
        ->get();

        $content = [];
        $i = 1;
        foreach($list_content_q as $r){
          $content[$i] = $r->lmscontent_id;
          $i++;
        }
        if($content == []){
          flash('error','record_import_error', 'error');
          return back();
        }
        $ignoreHeading = 'label';
        DB::beginTransaction();
        try{
          foreach($data as $r){
            if($r[2] != $ignoreHeading){
              $check = DB::table('lms_exams')->insertGetId([
                'content_id'    =>  $content[$r[1]],
                'label'         =>  $r[2],
                'dang'          =>  $r[3],
                'cau'           =>  $r[4],
                'mota'          =>  $r[5],
                'luachon1'      =>  $r[6],
                'luachon2'      =>  $r[7],
                'luachon3'      =>  $r[8],
                'luachon4'      =>  $r[9],
                'dapan'         =>  $r[10],
                'created_by'    =>  Auth::id(),
              ]);
            }
          }
          DB::commit();
        }catch(Exception $e){
          DB::rollBack();
          dd($e);
        }
      }
    }
    flash('success','record_import_successfully', 'success');
    return back();
  }

    /**
     * Get the review of an exam of student
     * @param  Request $request
     * @param  string  $quiz_result_id
     * @return string
     */
    public function getQuizReview(string $quiz_result_id)
    {
        $quiz_result_review = QuizResultReview::with('teacher')->where('quiz_result_id', $quiz_result_id)->first();
        $quiz_result_finish = QuizResultfinish::findOrFail($quiz_result_id);
        $html = '';

        if (!empty($quiz_result_review)) {
            $html = '
                <h4>'
                    . $quiz_result_review->teacher->name .
                    '<i class="fa fa-check-circle-o text-success"></i>
                </h4>
                <small>
                    <i class="fa fa-calendar"></i> ' . $quiz_result_review->created_at .
                '</small>
                <form action="" method="POST" id="reviewForm">
                    <div class="form-value">
                        <input type="text" name="quiz_result_id" value="' . $quiz_result_id . '" hidden>
                        <input type="text" name="student_id" value="' . $quiz_result_review->student_id . '" hidden>
                        <input type="text" name="teacher_id" value="' . $quiz_result_review->teacher_id . '" hidden>
                        <textarea name="review" cols="30" rows="3" class="form-control">' . $quiz_result_review->review . '</textarea>
                    </div>
                </form>
                ';
        } else {
            $html =
                '<h4>'
                    . Auth::user()->name .
                    '<i class="fa fa-check-circle-o text-success"></i>
                </h4>
                <form action="" method="POST" id="reviewForm">
                    <div class="form-value">
                        <input type="text" name="quiz_result_id" value="' . $quiz_result_id . '" hidden>
                        <input type="text" name="student_id" value="' . $quiz_result_finish->user_id . '" hidden>
                        <input type="text" name="teacher_id" value="' . Auth::id() . '" hidden>
                        <textarea name="review" cols="30" rows="3" class="form-control"></textarea>
                    </div>
                </form>';
        }

        return response()->json([
            'sucess' => true,
            'html' => $html
        ]);
    }

    /**
     * Post the review of an exam of student
     *
     * @param  Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function reviewQuiz(Request $request)
    {
        if (!$request->isMethod('post')) {
            return response()->json([
                'sucess' => false,
                'message' => 'Wrong method!',
            ], 405);
        }

        $validator = Validator::make($request->all(), [
            'quiz_result_id' => ['required', 'exists:quizresultfinish,id'],
            'student_id' => ['required', 'exists:users,id'],
            'teacher_id' => ['required', 'exists:users,id'],
            'review' => ['required', 'string', 'max:255'],
        ]);
        if ($validator->fails()) {
            return response()->json([
                'sucess' => false,
                'message' => 'Thông tin nhập liệu sai!'
            ], 422);
        }

        QuizResultReview::updateOrCreate(
            [
                'quiz_result_id' => $request->quiz_result_id,
                'student_id' => $request->student_id,
                'teacher_id' => $request->teacher_id,
            ],
            [
                'review' => $request->review,
            ]
        );
        return response()->json([
            'sucess' => true,
            'message' => 'Review added successfully!',
        ], 200);
    }
}
