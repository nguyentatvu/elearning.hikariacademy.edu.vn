<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Requests;
use \App;
use App\Comment;
use App\Exceptions\RedirectException;
use App\LmsContent;
use App\Subject;
use App\LmsSeries;
use App\LmsSeriesCombo;
use App\PaymentMethod;
use App\QuizResultReview;
use App\Services\LmsContentService;
use App\Services\LmsSeriesComboService;
use App\Services\LmsSeriesService;
use App\Services\PaymentMethodService;
use Yajra\DataTables\DataTables;
use DB;
use Auth;
use Image;
use ImageSettings;
use File;
use Input;
use Excel;
class LmsSeriesController extends Controller
{
    // Content preparation properties
    private $prepContent = [];

    private $lmsSeriesService;
    private $lmsSeriesComboService;
    private $paymentMethodService;
    private $lmsContentService;

	public function __construct(
        LmsSeriesService $lmsSeriesService,
        LmsSeriesComboService $lmsSeriesComboService,
        LmsContentService $lmsContentService,
        PaymentMethodService $paymentMethodService
    )
	{
		$this->middleware('auth')->except(['introductionDetail', 'introductionDetailForCombo']);
        $this->lmsSeriesService = $lmsSeriesService;
        $this->lmsSeriesComboService = $lmsSeriesComboService;
        $this->lmsContentService = $lmsContentService;
        $this->paymentMethodService = $paymentMethodService;
	}
	/**
	 * Course listing method
	 * @return Illuminate\Database\Eloquent\Collection
	 */
	public function index()
	{
		if (!checkRole(getUserGrade(2))) {
			prepareBlockUserMessage();
			return back();
		}
		$data['URL_IMPORT_CONTENT'] = PREFIX . "lms/series/import-excel";
		$data['create_url'] = PREFIX . 'lms/series/add';
		$data['datatbl_url'] = PREFIX . 'lms/series/getList/';
		$data['active_class'] = 'lms';
		$data['title'] = 'Khóa học';
		// dd($data);
		$view_name = 'admin.lms.lmsseries.list';
		return view($view_name, $data);
	}
	/**
	 * This method returns the datatables data to view
	 * @return [type] [description]
	 */
	public function getDatatable()
	{
		if (!checkRole(getUserGrade(2))) {
			prepareBlockUserMessage();
			return back();
		}
		$records = array();
		$records = LmsSeries::select(['lmsseries.title', 'lmsseries.slug', 'lmsseries.id', 'lmsseries.updated_at'])
			->where([
				['delete_status', 0],
				['type_series', 0]
			])
			->orderBy('lmsseries.id', 'desc');
		return DataTables::of($records)
			->addColumn('action', function ($records) {
				$link_data = '<div class="dropdown more">
			<a id="dLabel" type="button" class="more-dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
			<i class="mdi mdi-dots-vertical"></i>
			</a>
			<ul class="dropdown-menu dropdown-menu-right" aria-labelledby="dLabel">
			<li><a href="' . $records->slug . '/content"><i class="fa fa-spinner"></i>' . getPhrase("update") . '</a></li>
			<li><a href="' . 'series/edit/' . $records->slug . '"><i class="fa fa-pencil"></i>' . getPhrase("edit") . '</a></li>';
				$temp = '';
				if (checkRole(getUserGrade(2))) {
					$temp .= ' <li><a href="javascript:void(0);" onclick="deleteRecord(\'' . $records->slug . '\');"><i class="fa fa-trash"></i>' . getPhrase("delete") . '</a></li>';
				}
				$temp .= '</ul></div>';
				$link_data .= $temp;
				return $link_data;
			})
			->editColumn('title', function ($records) {
				return '<a href="' . PREFIX . 'lms/' . $records->slug . '/content' . '">' . $records->title . '</a>';
			})
			->editColumn('cost', function ($records) {
				return ($records->is_paid) ? $records->cost : '-';
			})
			->editColumn('is_paid', function ($records) {
				return ($records->is_paid) ? '<span class="label label-primary">Trả phí</span>' : '<span class="label label-success">' . getPhrase('free') . '</span>';
			})
			->removeColumn('id')
			->removeColumn('slug')
			->removeColumn('updated_at')
			->rawColumns(['action', 'is_paid', 'title'])
			->make();
	}
	/**
	 * This method loads the create view
	 * @return void
	 */
	public function create()
	{
		if (!checkRole(getUserGrade(2))) {
			prepareBlockUserMessage();
			return back();
		}
		$selectedTeacher = array();
		$data['selectedTeacher'] = $selectedTeacher;
		$user_gv = DB::table('users')->where('role_id', 4)->get();
		$data['user_gv'] = $user_gv;
		$data['URL_LMS_SERIES'] = PREFIX . 'lms/series';
		$data['URL_LMS_SERIES_ADD'] = PREFIX . 'lms/series/add';
		$data['record'] = FALSE;
		$data['active_class'] = 'lms';
		$data['title'] = 'Thêm mới khóa học';
		$data['type_series'] = 0;
		$view_name = 'admin.lms.lmsseries.add-edit';
		return view($view_name, $data);
	}
	/**
	 * This method loads the create view
	 * @return void
	 */
	public function createExam()
	{
		if (!checkRole(getUserGrade(2))) {
			prepareBlockUserMessage();
			return back();
		}
		$selectedTeacher = array();
		$data['selectedTeacher'] = $selectedTeacher;
		$user_gv = DB::table('users')->where('role_id', 4)->get();
		$data['user_gv'] = $user_gv;
		$data['URL_LMS_SERIES'] = PREFIX . 'lms/seriesexam';
		$data['URL_LMS_SERIES_ADD'] = PREFIX . 'lms/series/add';
		$data['record'] = FALSE;
		$data['active_class'] = 'lms';
		$data['title'] = 'Thêm mới khóa luyện thi';
		$data['type_series'] = 1;
		$view_name = 'admin.lms.lmsseries.add-edit';
		return view($view_name, $data);
	}
	/**
	 * This method loads the edit view based on unique slug provided by user
	 * @param  [string] $slug [unique slug of the record]
	 * @return [view with record]
	 */
	public function edit($slug)
	{
		if (!checkRole(getUserGrade(2))) {
			prepareBlockUserMessage();
			return back();
		}
		$record = LmsSeries::getRecordWithSlug($slug);
		$user_gv = DB::table('users')->where('role_id', 4)->get();
		$selectedTeacher = DB::table('lmsseries_teacher')->where('lmsseries_id', $record->id)->pluck('teacher_id')->toArray();
		$data['selectedTeacher'] = $selectedTeacher;
		$data['user_gv'] = $user_gv;
		$data['URL_LMS_SERIES'] = PREFIX . 'lms/series';
		$data['URL_LMS_SERIES_ADD'] = PREFIX . 'lms/series/add';
		$data['URL_LMS_SERIES_EDIT'] = PREFIX . 'lms/series/edit/';
		$data['record'] = $record;
		$data['active_class'] = 'lms';
		$data['settings'] = FALSE;
		$data['title'] = 'Chỉnh sửa khóa học';
		$data['type_series'] = 0;
		$view_name = 'admin.lms.lmsseries.add-edit';
		return view($view_name, $data);
	}
	/**
	 * This method loads the edit view based on unique slug provided by user
	 * @param  [string] $slug [unique slug of the record]
	 * @return [view with record]
	 */
	public function editstydy($slug)
	{
		if (!checkRole(getUserGrade(2))) {
			prepareBlockUserMessage();
			return back();
		}
		$record = LmsSeries::getRecordWithSlug($slug);
		$user_gv = DB::table('users')->where('role_id', 4)->get();
		$selectedTeacher = DB::table('lmsseries_teacher')->where('lmsseries_id', $record->id)->pluck('teacher_id')->toArray();
		$data['selectedTeacher'] = $selectedTeacher;
		$data['user_gv'] = $user_gv;
		$data['URL_LMS_SERIES'] = PREFIX . 'lms/series';
		$data['URL_LMS_SERIES_ADD'] = PREFIX . 'lms/series/add';
		$data['URL_LMS_SERIES_EDIT'] = PREFIX . 'lms/series/edit/';
		$data['record'] = $record;
		$data['active_class'] = 'lms';
		$data['settings'] = FALSE;
		$data['title'] = 'Chỉnh sửa khóa luyện thi';
		$data['type_series'] = 1;
		$view_name = 'admin.lms.lmsseries.add-edit';
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
		if (!checkRole(getUserGrade(2))) {
			prepareBlockUserMessage();
			return back();
		}
		$record = LmsSeries::getRecordWithSlug($slug);
		$rules = ['title' => 'bail|required|max:30'];
		$name = $request->title;
		if ($name != $record->title)
			$record->slug = createSlug(LmsSeries::class, $name);
		$this->validate($request, $rules);
		$record->title = $name;
		$record->is_paid = $request->is_paid;
		$record->lms_category_id = $request->lms_category_id;
		$record->validity = -1;
		$record->cost = 0;
		$record->is_paid = 1;
		$record->type_series = $request->type_series;
		$record->total_items = $request->total_items;
		$record->short_description = $request->short_description;
		$record->description = $request->description;
		$record->start_date = $request->start_date;
		$record->end_date = $request->end_date;
		$record->record_updated_by = Auth::user()->id;
		// dd($request);
		$record->save();
		if (isset($request->teachers) && count($request->teachers) > 0) {
			$deletedRows = DB::table('lmsseries_teacher')->where('lmsseries_id', $record->id)->delete();
			foreach ($request->teachers as $key => $value) {
				DB::table('lmsseries_teacher')->insert(
					['teacher_id' => $value, 'lmsseries_id' => $record->id]
				);
			}
		} else {
			$deletedRows = DB::table('lmsseries_teacher')->where('lmsseries_id', $record->id)->delete();
		}
		$file_name = 'image';
		if ($request->hasFile($file_name)) {
			$rules = array($file_name => 'mimes:jpeg,jpg,png,gif,JPEG,JPG,PNG|max:10000');
			$this->validate($request, $rules);
			$examSettings = getSettings('lms');
			$path = $examSettings->seriesImagepath;
			$this->deleteFile($record->image, $path);
			$record->image = $this->processUpload($request, $record, $file_name);
			$record->save();
		}
		flash('success', 'Cập nhật thành công', 'success');
		if ($request->type_series == 0) {
			return redirect(PREFIX . 'lms/series');
		} else {
			return redirect(PREFIX . 'lms/seriesexam');
		}
	}
	/**
	 * This method adds record to DB
	 * @param  Request $request [Request Object]
	 * @return void
	 */
	public function store(Request $request)
	{
		$rules = [
			'title' => 'bail|required|max:30',
		];
		$this->validate($request, $rules);
		$record = new LmsSeries();
		$name = $request->title;
		$record->title = $name;
		$record->slug = createSlug(LmsSeries::class, $name);
		$record->cost = 0;
		$record->lms_category_id = $request->lms_category_id;
		$record->type_series = $request->type_series;
		$record->total_items = 0;
		$record->short_description = $request->short_description;
		$record->description = $request->description;
		$record->start_date = $request->start_date;
		$record->end_date = $request->end_date;
		$record->record_updated_by = Auth::user()->id;
		$record->save();
		if (count($request->teachers) > 0) {
			foreach ($request->teachers as $key => $value) {
				DB::table('lmsseries_teacher')->insert(
					['teacher_id' => $value, 'lmsseries_id' => $record->id]
				);
			}
		}
		$file_name = 'image';
		if ($request->hasFile($file_name)) {
			$rules = array($file_name => 'mimes:jpeg,jpg,png,gif,PNG,JPEG,JPG|max:10000');
			$this->validate($request, $rules);
			$examSettings = getSettings('lms');
			$path = $examSettings->seriesImagepath;
			$this->deleteFile($record->image, $path);
			$record->image = $this->processUpload($request, $record, $file_name);
			$record->save();
		}
		flash('success', 'Thêm khóa học thành công', 'success');
		if ($request->type_series == 0) {
			return redirect(PREFIX . 'lms/series');
		} else {
			return redirect(PREFIX . 'lms/seriesexam');
		}
	}
	public function deleteFile($record, $path, $is_array = FALSE)
	{
		$files = array();
		$files[] = $path . $record;
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
		if ($request->hasFile($file_name)) {
			$examSettings = getSettings('lms');
			$imageObject = new ImageSettings();
			$destinationPath            = public_path($examSettings->seriesImagepath);
			$destinationPathThumb       = $examSettings->seriesThumbImagepath;
			$fileName = $record->id.'-'.$file_name.'.'.$request->$file_name->guessClientExtension();
			$request->file($file_name)->move($destinationPath, $fileName);
		 	//Save Normal Image with 300x300
			Image::make($destinationPath . $fileName)->fit($examSettings->imageSize)->save($destinationPath . $fileName);
			//Image::make($destinationPath.$fileName)->fit($imageObject->getThumbnailSize())->save($destinationPathThumb.$fileName);
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
		if (!checkRole(getUserGrade(2))) {
			prepareBlockUserMessage();
			return back();
		}
		/**
		 * Delete the questions associated with this quiz first
		 * Delete the quiz
		 * @var [type]
		 */
		$record = LmsSeries::where('slug', $slug)->first();
		if (!$record) {
			$response['status'] = 0;
			$response['message'] = getPhrase('invalid_record');
			return json_encode($response);
		}
		try {
			if (!env('DEMO_MODE')) {
				$record->delete_status = 1;
				$record->save();
			}
			$response['status'] = 1;
			$response['message'] = getPhrase('record_deleted_successfully');
		} catch (\Illuminate\Database\QueryException $e) {
			$response['status'] = 0;
			if (getSetting('show_foreign_key_constraint', 'module'))
				$response['message'] = $e->errorInfo;
			else
				$response['message'] = getPhrase('this_record_is_in_use_in_other_modules');
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
		$category_id = $request->category_id;
		$items = App\LmsContent::where('subject_id', '=', $category_id)
			->get();
		return json_encode(array('items' => $items));
	}
	/**
	 * Updates the questions in a selected quiz
	 * @param  [type] $slug [description]
	 * @return [type]       [description]
	 */
	public function updateSeries($slug)
	{
		if (!checkRole(getUserGrade(2))) {
			prepareBlockUserMessage();
			return back();
		}
		/**
		 * Get the Quiz Id with the slug
		 * Get the available questions from questionbank_quizzes table
		 * Load view with this data
		 */
		$record = LmsSeries::getRecordWithSlug($slug);
		$data['record'] = $record;
		$data['active_class'] = 'lms';
		$data['right_bar'] = TRUE;
		$data['right_bar_path'] = 'lms.lmsseries.right-bar-update-lmslist';
		$data['categories'] = array_pluck(App\Subject::all(), 'subject_title', 'id');
		$data['settings'] = FALSE;
		$previous_records = array();
		if ($record->total_items > 0) {
			$series = DB::table('lmsseries_data')
				->where('lmsseries_id', '=', $record->id)
				->get();
			foreach ($series as $r) {
				$temp = array();
				$temp['id'] = $r->lmscontent_id;
				$series_details = App\LmsContent::where('id', '=', $r->lmscontent_id)->first();
				// dd($series_details);
				$temp['content_type'] = $series_details->content_type;
				$temp['code'] = $series_details->code;
				$temp['title'] = $series_details->title;
				array_push($previous_records, $temp);
			}
			$settings['contents'] = $previous_records;
			$data['settings'] = json_encode($settings);
		}
		$data['exam_categories'] = array_pluck(
			App\QuizCategory::all(),
			'category',
			'id'
		);
		// $data['categories']        = array_pluck(QuizCategory::all(), 'category', 'id');
		$data['title'] = getPhrase('update_series_for') . ' ' . $record->title;
		// return view('lms.lmsseries.update-list', $data);
		$view_name = 'admin.lms.lmsseries.update-list';
		return view($view_name, $data);
	}
	public function storeSeries(Request $request, $slug)
	{
		if (!checkRole(getUserGrade(2))) {
			prepareBlockUserMessage();
			return back();
		}
		$lms_series = LmsSeries::getRecordWithSlug($slug);
		$lmsseries_id = $lms_series->id;
		$contents = json_decode($request->saved_series);
		$contents_to_update = array();
		foreach ($contents as $record) {
			$temp = array();
			$temp['lmscontent_id'] = $record->id;
			$temp['lmsseries_id'] = $lmsseries_id;
			array_push($contents_to_update, $temp);
		}
		$lms_series->total_items = count($contents);
		if (!env('DEMO_MODE')) {
			//Clear all previous questions
			DB::table('lmsseries_data')->where('lmsseries_id', '=', $lmsseries_id)->delete();
			//Insert New Questions
			DB::table('lmsseries_data')->insert($contents_to_update);
			$lms_series->save();
		}
		flash('success', 'record_updated_successfully', 'success');
		return redirect(URL_LMS_SERIES);
	}
	/**
	 * This method lists all the available exam series for students
	 *
	 * @return [type] [description]
	 */
	public function listCategories()
	{
		$data['active_class'] = 'lmscategories';
		$data['title'] = 'Khóa học';

		$data['series'] = DB::table('lmsseries_combo')
			/*-->join('lms_class','lmsseries.id','=','lms_class.lmsseries_id')
						   >join('classes','lms_class.classes_id','=','classes.id')
						   ->join('classes_user','classes_user.classes_id','=','classes.id')*/
			->join('payment_method', 'payment_method.item_id', '=', 'lmsseries_combo.id')
			->join('payments', 'payment_method.id', '=', 'payments.payments_method_id')
			->join('lmsseries', 'lmsseries.id', '=', 'payments.item_id')
			->select(
				'lmsseries.*',
				DB::raw("(lmsseries_combo.slug) as combo_slug"),
				'lmsseries_combo.image as combo_image',
				'payments.time',
				'payment_method.created_at',
				'payment_method.status',
				'payment_method.month_extend',
				DB::raw("(SELECT COUNT(lmscontents.id)  FROM lmscontents
	WHERE lmscontents.delete_status = 0 AND lmscontents.type NOT IN(0,8) AND
		lmscontents.lmsseries_id IN (lmsseries_combo.n1,lmsseries_combo.n2,lmsseries_combo.n3,lmsseries_combo.n4,lmsseries_combo.n5) ) as total_course"),
				DB::raw("(SELECT COUNT(lms_student_view.id)  FROM lms_student_view
			join lmscontents on lms_student_view.lmscontent_id = lmscontents.id
	WHERE lmscontents.delete_status = 0 AND lmscontents.type NOT IN(0,8) AND lms_student_view.users_id = " . Auth::id() . " AND lmscontents.lmsseries_id IN (lmsseries_combo.n1,lmsseries_combo.n2,lmsseries_combo.n3,lmsseries_combo.n4,lmsseries_combo.n5)
		 ) as current_course")
			)
			->where([
				['payment_method.user_id', Auth::id()],
				['payment_method.status', 1],
				['lmsseries_combo.delete_status', 0],
				['lmsseries_combo.type', 0]
			])
			->distinct()
			->orderBy('payment_method.created_at', 'desc')
			->get();
		//dump($data['series']);
		$data['series_selected'] = DB::table('lmsseries')
			->join('lms_class', 'lmsseries.id', '=', 'lms_class.lmsseries_id')
			->join('classes', 'lms_class.classes_id', '=', 'classes.id')
			->join('classes_user', 'classes_user.classes_id', '=', 'classes.id')
			->where([
				['classes_user.student_id', Auth::user()->id],
				['lmsseries.delete_status', 0],
				['type_series', 1]
			])
			//->orderBy('order_by')
			->get();
		// dd($data);
		$view_name = 'client.mypage.my-courses';
		return view($view_name, $data);
	}

	public function listCategoriesStudy()
	{
		$data['active_class'] = 'lmsstudy';
		$data['title'] = 'Khóa luyện thi';
		$data['series'] = DB::table('lmsseries_combo')
			->join('payment_method', 'payment_method.item_id', '=', 'lmsseries_combo.id')
			->join('payments', 'payment_method.id', '=', 'payments.payments_method_id')
			->join('lmsseries', 'lmsseries.id', '=', 'payments.item_id')
			->select(
				'lmsseries.*',
				DB::raw("(lmsseries_combo.slug) as combo_slug"),
				'lmsseries_combo.image as combo_image',
				'payments.time',
				'payment_method.created_at',
				'payment_method.status',
				'payment_method.month_extend',
				DB::raw("(SELECT COUNT(lmscontents.id)  FROM lmscontents
        WHERE lmscontents.delete_status = 0 AND lmscontents.type NOT IN(0,8) AND
            lmscontents.lmsseries_id IN (lmsseries_combo.n1,lmsseries_combo.n2,lmsseries_combo.n3,lmsseries_combo.n4,lmsseries_combo.n5) ) as total_course"),
				DB::raw("(SELECT COUNT(lms_student_view.id)  FROM lms_student_view
                join lmscontents on lms_student_view.lmscontent_id = lmscontents.id
        WHERE lmscontents.delete_status = 0 AND lmscontents.type NOT IN(0,8) AND lms_student_view.users_id = " . Auth::id() . " AND lmscontents.lmsseries_id IN (lmsseries_combo.n1,lmsseries_combo.n2,lmsseries_combo.n3,lmsseries_combo.n4,lmsseries_combo.n5)
             ) as current_course")
			)
			->where([
				['payment_method.user_id', Auth::id()],
				['payment_method.status', 1],
				['lmsseries_combo.delete_status', 0],
				['lmsseries_combo.type', 1]
			])
			->distinct()
			->orderBy('payment_method.created_at', 'desc')
			->get();
		$data['series_selected'] = DB::table('lmsseries')
			->join('lms_class', 'lmsseries.id', '=', 'lms_class.lmsseries_id')
			->join('classes', 'lms_class.classes_id', '=', 'classes.id')
			->join('classes_user', 'classes_user.classes_id', '=', 'classes.id')
			->where([
				['classes_user.student_id', Auth::user()->id],
				['lmsseries.delete_status', 0],
				['type_series', 1]
			])
			//->orderBy('order_by')
			->get();
		$view_name = 'client.mypage.my-courses';

		return view($view_name, $data);
	}

	/**
	 * Get course overview data
	 *
	 * @param Request $request
	 * @return \Illuminate\Contracts\Support\Renderable
	 */
	public function getSeriesOverviewContent(Request $request)
	{
		$lms_combo_series_slug = $request->get('lms_combo_series_slug');
		$lms_series_slug = $request->get('lms_series_slug');

		abort_if(is_null($lms_series_slug) || is_null($lms_combo_series_slug), 404);

		$lms_series = LmsSeries::where('slug', $lms_series_slug)->first();
		$lms_series_combo = LmsSeriesCombo::where('slug', $lms_combo_series_slug)->first();
		abort_if(is_null($lms_series) || is_null($lms_series_combo), 404);
		$lms_series_title = $lms_series->title;

		$url = route(
			'learning-management.lesson.show',
			['slug' => $lms_series_slug, 'combo_slug' => $lms_combo_series_slug]
		);

		$lms_contents = LmsContent::with(
			[
				'childContents.childContents.comments' => function ($query) use ($lms_series_combo) {
					$query
						->with('childComments')
						->where(
							[
								'user_id' => Auth::id(),
								'lmscombo_id' => $lms_series_combo->id,
								'parent_id' => 0,
							]
						);
				},
				'comments' => function ($query) use ($lms_series_combo) {
					$query
						->with('childComments')
						->where(
							[
								'user_id' => Auth::id(),
								'lmscombo_id' => $lms_series_combo->id,
								'parent_id' => 0,
							]
						);
				},
			]
		)->where(
				[
					'lmsseries_id' => $lms_series->id,
					'parent_id' => null,
					'delete_status' => 0
				]
			)->get();

		$lms_series_comments = $lms_contents->map(function ($child_content) {
			$comments = [];
			$this->getCommentsFromLmsContent($child_content, $comments, $child_content->bai);

			//If there are comment(s), add content title to the lms content
			if (!empty($comments)) {
				$comments = array_merge($comments, ['content_title' => $child_content->bai]);
			}

			return $comments;
		});

		return view(
			'admin.student.lms.overview-series-modal',
			compact('lms_combo_series_slug', 'lms_series_slug', 'lms_series_comments', 'lms_series_title')
		);
	}

	/**
	 * Get comments from lms content
	 *
	 * @param LmsContent $lms_content
	 * @param array $comments
	 * @param string $breadcrumb
	 * @return void
	 */
	private function getCommentsFromLmsContent($lms_content, &$comments = [], $breadcrumb = '')
	{
		if ($lms_content->comments->isNotEmpty()) {
			$content_comments = array_map(function ($comment) use ($breadcrumb, $lms_content) {
				return array_merge(
					$comment,
					[
						'breadcrumb' => $breadcrumb,
						'type' => $lms_content->type,
					]
				);
			}, $lms_content->comments->toArray());

			$comments = array_merge($comments, $content_comments);
		}

		if (!$lms_content->childContents->isEmpty()) {
			foreach ($lms_content->childContents as $childContent) {
				$this->getCommentsFromLmsContent(
					$childContent,
					$comments,
					$breadcrumb . ' >> ' . $childContent->bai
				);
			}
		}
	}

	public function listPayments()
	{
		$data['active_class'] = 'lmspayments';
		$data['title'] = 'Quản lý thanh toán';
		// $data['series']         	= LmsSeries::paginate((new App\GeneralSettings())->getPageLength());
		$data['series'] = DB::table('lmsseries_combo')
			/*-->join('lms_class','lmsseries.id','=','lms_class.lmsseries_id')
							  >join('classes','lms_class.classes_id','=','classes.id')
							  ->join('classes_user','classes_user.classes_id','=','classes.id')*/
			->join('payment_method', 'payment_method.item_id', '=', 'lmsseries_combo.id')
			->select('lmsseries_combo.*', 'payment_method.created_at', 'payment_method.status', 'payment_method.orderType', 'payment_method.status')
			->where([
				['payment_method.user_id', Auth::id()],
				['lmsseries_combo.delete_status', 0],
				/* ['type_series',0]*/
			])
			->orderBy('payment_method.created_at', 'desc')
			->get();
		//dump($data['series']);
		/*$data['series_selected'] = DB::table('lmsseries')
						->join('lms_class','lmsseries.id','=','lms_class.lmsseries_id')
						->join('classes','lms_class.classes_id','=','classes.id')
						->join('classes_user','classes_user.classes_id','=','classes.id')
						->where([
							['classes_user.student_id',Auth::user()->id],
							['lmsseries.delete_status',0],
							['type_series',1]
						])
						->orderBy('order_by')
						->get();*/
		// dump($data['series']);
		$data['layout'] = 'admin.layouts.student.studentsettinglayout';
		// return view('student.exams.exam-series-list', $data);
		$view_name = 'admin.student.lms.lms-payments';
		return view($view_name, $data);
	}
	public function listSeries($slug = '')
	{
		if (!globalCheck(0, $slug)) {
			flash('error', 'Thông báo sẽ tự đóng sau 1s', 'error');
			return redirect('dashboard');
		}
		$data['active_class'] = 'lms';
		$data['title'] = 'Series Khóa học';
		// $data['series']         	= LmsSeries::paginate((new App\GeneralSettings())->getPageLength());
		$data['series'] = DB::table('lmsseries')
			->select('lmsseries.*')
			->join('lmscategories', 'lmscategories.id', '=', 'lmsseries.lms_category_id')
			->join('lms_class', 'lmscategories.id', '=', 'lms_class.lmscategories_id')
			->join('classes', 'lms_class.classes_id', '=', 'classes.id')
			->join('classes_user', 'classes_user.classes_id', '=', 'classes.id')
			->where([
				['classes_user.student_id', Auth::user()->id],
				['lmscategories.slug', $slug]
			])
			->get();
		$data['categories'] = DB::table('lmscategories')
			->select(['category', 'slug'])
			->where('slug', $slug)
			->get()->first();
		// dd($data['series']);
		if ($data['series']->isEmpty()) {
			flash('Ooops...!', getPhrase("page_not_found"), 'error');
			return back();
		}
		$data['layout'] = getLayout();
		$data['url_categories'] = PREFIX . 'lms/exam-categories/list';
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
		if ($isValid = $this->isValidRecord($record))
			return redirect($isValid);
		$data['active_class'] = 'exams';
		$data['pay_by'] = '';
		$data['title'] = $record->title;
		$data['item'] = $record;
		$data['right_bar'] = TRUE;
		$data['right_bar_path'] = 'student.exams.exam-series-item-view-right-bar';
		$data['right_bar_data'] = array(
			'item' => $record,
		);
		$data['layout'] = getLayout();
		// return view('student.exams.exam-series-view-item', $data);
		$view_name = 'admin.student.exams.exam-series-view-item';
		return view($view_name, $data);
	}
	//Import exams
	public function importExcel(Request $request)
	{
		if ($request->hasFile('file')) {
			$path = $request->file('file')->getRealPath();
			config(['excel.import.startRow' => 1]);
			$data = Excel::selectSheetsByIndex(0)->load($path, function ($reader) {
				$reader->noHeading();
			})->get();
			dd($data);
			if (!empty($data) && $data->count()) {
				$list_content_q = DB::table('lmscontents')
					->select(['lmsseries_data.lmscontent_id', 'lmscontents.stt'])
					->join('lmsseries_data', 'lmsseries_data.lmscontent_id', '=', 'lmscontents.id')
					->join('lmsseries', 'lmsseries_data.lmsseries_id', '=', 'lmsseries.id')
					->where([
						['lmsseries.slug', $request->series_slug],
						['lmscontents.parent_id', '<>', 0]
					])
					->orderBy('stt', 'asc')
					->get();
				$content = [];
				$i = 1;
				foreach ($list_content_q as $r) {
					$content[$i] = $r->lmscontent_id;
					$i++;
				}
				if ($content == []) {
					flash('error', 'record_import_error', 'error');
					return back();
				}
				$ignoreHeading = 'label';
				DB::beginTransaction();
				try {
					foreach ($data as $r) {
						if ($r[2] != $ignoreHeading) {
							$check = DB::table('lms_exams')->insertGetId([
								'content_id' => $content[$r[1]],
								'label' => $r[2],
								'dang' => $r[3],
								'cau' => $r[4],
								'mota' => $r[5],
								'luachon1' => $r[6],
								'luachon2' => $r[7],
								'luachon3' => $r[8],
								'luachon4' => $r[9],
								'dapan' => $r[10],
								'created_by' => Auth::id(),
							]);
						}
					}
				} catch (Exception $e) {
					DB::rollBack();
					dd($e);
				}
				DB::commit();
			}
		}
		flash('success', 'record_import_successfully', 'success');
		return back();
	}

	public function getResultExam(Request $request)
	{

		$userId = Auth::user()->id;
		$data['active_class'] = 'users';
		$data['title'] = 'Kết quả thi';
		$data['active_class'] = 'resultexam';
		// Get data result exam
		$data['results'] = DB::table('quizresultfinish')
			->join('examseries', 'examseries.id', '=', 'quizresultfinish.examseri_id')
			->where('quizresultfinish.user_id', '=', $userId)
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
			->get();

		if ($data['results'] != null) {
			foreach ($data['results'] as $record) {
				# code...
				if ($record->category_id <= 3) {
					$style1 = ($this->checkKijunTen($record->category_id, 1, $record->quiz_1_total)) ? "info" : "danger";
					$style2 = ($this->checkKijunTen($record->category_id, 2, $record->quiz_2_total)) ? "info" : "danger";
					$style3 = ($this->checkKijunTen($record->category_id, 3, $record->quiz_3_total)) ? "info" : "danger";
					$detail = '言語知識（文字・語彙・文法）: <span class="label label-' . $style1 . '">' . $record->quiz_1_total . '</span><br><br>読解: <span class="label label-' . $style2 . '">' . $record->quiz_2_total . '</span><br><br>聴解: <span class="label label-' . $style3 . '">' . $record->quiz_3_total . '</span>';
				} else {
					$style1 = ($this->checkKijunTen($record->category_id, 1, $record->quiz_1_total)) ? "info" : "danger";
					$style3 = ($this->checkKijunTen($record->category_id, 2, $record->quiz_3_total)) ? "info" : "danger";
					$detail = '言語知識（文字・語彙・文法）: <span class="label label-' . $style1 . '">' . $record->quiz_1_total . '</span><br><br>聴解: <span class="label label-' . $style3 . '">' . $record->quiz_3_total . '</span>';
				}
				$record->detail = $detail;

				if ($record->finish == 3) {
					if ($this->checkPassingscore($record->category_id, $record->total_marks) && $this->checkKijunTenAnyKubun($record->category_id, $record->quiz_1_total, $record->quiz_2_total, $record->quiz_3_total)) {
						$ketqua = '<span class="label label-success">Đạt</span>';
					} else {
						$ketqua = '<span class="label label-warning">Chưa đạt</span>';
					}

				} else {
					$ketqua = '<span class="label label-danger">Chưa hoàn thành</span>';
				}
				$record->ketqua = $ketqua;
			}
		}

		$data['user'] = DB::table('users')
			->where('users.id', '=', $userId)
			->first();

		$data['layout'] = 'admin.layouts.student.studentsettinglayout';
		$view_name = 'admin.lms.result-exam-list';
		return view($view_name, $data);
	}

	/*
		  Check if the given score is beyond the jikunten
		  level: 1~5
		  kubun: 1: 言語知識（文字・語彙・文法）; 2: 読解; 3: 聴解
		  score: score to check
		  return: true if the given score is over the jikunten and else
		  */
	private function checkKijunTen($level, $kubun, $score)
	{
		switch ($level) {
			case 1:
			case 2:
			case 3:
				switch ($kubun) {
					case 1:
					case 2:
					case 3:
						return ($score > 19) ? true : false;
						break;
				}
				break;
			case 4:
			case 5:
				switch ($kubun) {
					case 1:
						return ($score > 38) ? true : false;
						break;
					case 2:
						return ($score > 19) ? true : false;
						break;
						break;
				}
		}
		return false;
	}

	/*
		  Check if the given total score is beyond the Passing score
		  level: 1~5
		  total_score: score to check
		  return: true if the given total score is over the Passing score and else
		  */
	private function checkPassingscore($level, $total_score)
	{
		switch ($level) {
			case 1:
				return ($total_score >= 100) ? true : false;
				break;
			case 2:
			case 4:
				return ($total_score >= 90) ? true : false;
				break;
			case 3:
				return ($total_score >= 95) ? true : false;
				break;
			case 5:
				return ($total_score >= 80) ? true : false;
				break;
		}
		return false;
	}

	/*
		  Check if the given scores is beyond the jikunten in any kubun
		  level: 1~5
		  score_kubun1~3: score to check
		  return: false if the given scores is under any jikunten and else
		  */
	private function checkKijunTenAnyKubun($level, $score_kubun1, $score_kubun2, $score_kubun3)
	{
		switch ($level) {
			case 1:
			case 2:
			case 3:
				if (!$this->checkKijunTen($level, 1, $score_kubun1))
					return false;
				if (!$this->checkKijunTen($level, 2, $score_kubun2))
					return false;
				if (!$this->checkKijunTen($level, 3, $score_kubun3))
					return false;
				return true;
				break;
			case 4:
			case 5:
				if (!$this->checkKijunTen($level, 1, $score_kubun1))
					return false;
				if (!$this->checkKijunTen($level, 2, $score_kubun3))
					return false;
				return true;
				break;
		}
		return false;
	}
	/**
			  * Handles the retrieval and processing of quiz result details for a specific exam series.
			  * This function fetches the quiz result details for a user, including quiz analysis data,
			  * and enriches the analysis with subject titles before returning the data as a rendered
			  * view to be displayed in a modal.

			  * @param  \Illuminate\Http\Request  $request  The incoming HTTP request containing the quiz result ID.
			  * @return \Illuminate\Http\JsonResponse  A JSON response containing the rendered view content and layout information.
			 */
	public function getResultExamDetail(Request $request)
	{
		$quizResultFinishId = $request->id;
		$userId = Auth::user()->id;

		// Fetch subjects with their titles
		$subjects = DB::table('subjects')
			->select('id', 'subject_title')
			->get()
			->keyBy('id') // Key by subject_id for easy lookup
			->toArray();

		// Fetch quiz result details
		$results = DB::table('quizresultfinish')
			->join('examseries', 'examseries.id', '=', 'quizresultfinish.examseri_id')
			->leftJoin('quizzes as quiz1', 'quiz1.id', '=', 'quizresultfinish.quiz_1')
			->leftJoin('quizzes as quiz2', 'quiz2.id', '=', 'quizresultfinish.quiz_2')
			->leftJoin('quizzes as quiz3', 'quiz3.id', '=', 'quizresultfinish.quiz_3')
			->where('quizresultfinish.user_id', $userId)
			->where('quizresultfinish.id', $quizResultFinishId)
			->select(
				'examseries.title',
				'examseries.category_id',
				'quizresultfinish.id',
				'quizresultfinish.created_at',
				'quizresultfinish.finish',
				'quizresultfinish.total_marks',
				'quizresultfinish.quiz_1_mark',
				'quizresultfinish.quiz_2_mark',
				'quizresultfinish.quiz_3_mark',
				'quizresultfinish.quiz_1_total',
				'quizresultfinish.quiz_2_total',
				'quizresultfinish.quiz_3_total',
				'quizresultfinish.total_marks',
				'quizresultfinish.status',
				'quizresultfinish.quiz_1_analysis',
				'quizresultfinish.quiz_2_analysis',
				'quizresultfinish.quiz_3_analysis',
				'quiz1.total_marks as quiz_1_total_marks',
				'quiz2.total_marks as quiz_2_total_marks',
				'quiz3.total_marks as quiz_3_total_marks'
			)
			->first();
		$quizResultReview = QuizResultReview::with('teacher')->where('quiz_result_id', $quizResultFinishId)->first();

		if ($results) {
			$unfinished = 0;
			$pass = 1;
			$notAchieved = 2;
			if ($results->finish == 3) {
				if ($this->checkPassingscore($results->category_id, $results->total_marks) && $this->checkKijunTenAnyKubun($results->category_id, $results->quiz_1_total, $results->quiz_2_total, $results->quiz_3_total)) {
					$evaluation = $pass;
				} else {
					$evaluation = $notAchieved;
				}

			} else {
				$evaluation = $unfinished;
			}
			$results->evaluation = $evaluation;
		}
		// Define a function to enrich analysis data with subject titles
		$enrichAnalysisWithSubjects = function ($analysis, $subjects) {
			$data = json_decode($analysis, true);
			if (is_array($data)) {
				foreach ($data as &$item) {
					$item['subject_title'] = $subjects[$item['subject_id']]->subject_title ?? 'Unknown Subject';
				}
			}
			return $data;
		};

		// Process each analysis field
		$data = [
			'results' => $results,
			'quiz_1_analysis' => $enrichAnalysisWithSubjects($results->quiz_1_analysis, $subjects),
			'quiz_2_analysis' => $enrichAnalysisWithSubjects($results->quiz_2_analysis, $subjects),
			'quiz_3_analysis' => $enrichAnalysisWithSubjects($results->quiz_3_analysis, $subjects),
			'quiz_result_review' => $quizResultReview,
		];

		// Render the view with enriched data
		$viewContent = view('admin.lms.modal_content.result-exam-detail', compact('data'))->render();

		return response()->json([
			'layout' => 'admin.layouts.student.studentsettinglayout',
			'view_name' => $viewContent,
		]);
	}

    /**
     * Introduction detail
     *
     * @param  \Illuminate\Http\Request  $request
	 * @param  string $combo_slug
	 * @param  string $slug
     * @return \Illuminate\Http\Response
     */
    function introductionDetail(Request $request, string $combo_slug, string $slug) {
        $data['is_multiple_combo'] = $this->checkMultipleSeriesCombo($combo_slug);
        if ($data['is_multiple_combo']) {
            abort(404);
        }

        $this->processLessonContent($combo_slug, $slug);
        $data['series_learning_description'] = $this->prepContent['series_combo']->description;
        $data['other_combo_series'] = $this->lmsSeriesComboService->getAllPaidSeriesByTypeExcludeComboId(
            $this->prepContent['series_combo']->type,
            $this->prepContent['series_combo']->id
        );

        $this->prepContent['series_combo']->content_count =
            $this->lmsContentService->getContentCountBySeries($this->prepContent['series']->id);
        $this->prepContent['series_combo']->chapter_count =
            $this->lmsContentService->getChapterCountBySeries($this->prepContent['series']->id);

        return view('client.pages.series-introduction', array_merge(
            $this->getPreparedContentVariables(),
            $data
        ));
    }

    /**
     * Introduction detail for combo
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string $combo_slug
     * @return \Illuminate\Http\Response
     */
    function introductionDetailForCombo(Request $request, string $combo_slug) {
        $data['is_multiple_combo'] = $this->checkMultipleSeriesCombo($combo_slug);
        if (!$data['is_multiple_combo']) {
            abort(404);
        }
        $data['seriesCombo'] = $this->lmsSeriesComboService->getSeriesComboBySlugWithSeries($combo_slug);
        $data['other_combo_series'] = $this->lmsSeriesComboService->getAllPaidSeriesByTypeExcludeComboId(
            $data['seriesCombo']->type,
            $data['seriesCombo']->id
        );
        $data['series_learning_description'] = $data['seriesCombo']->description;

        // Check valid payment

        $data['isValidPayment']
            = $this->paymentMethodService->checkSerieValidity(auth()->id() ?? -1, $data['seriesCombo']->id);

        return view('client.pages.series-introduction', $data);
    }



    /**
     * Check if multiple series combo is selected
     *
     * @param string $combo_slug
     * @return void
     */
    private function checkMultipleSeriesCombo(string $combo_slug) {
        $this->prepContent['series_combo'] =
            $this->lmsSeriesComboService->getByCondition('slug', $combo_slug);
        $this->prepContent['series_combo']->month_duration = config('constant.series_combo.month_duration_map')[$this->prepContent['series_combo']->time];

        if (!$this->prepContent['series_combo']) {
            abort(404);
        }

        $seriesCount = 0;
        for($index = 1; $index <= 5; $index++) {
            $this->prepContent['series_combo']->{"n{$index}"} ? $seriesCount++ : null;
        }

        return $seriesCount > 1;
    }

    /**
     * Get prepared content variables that will be used in view
     *
     * @return array
     */
    private function getPreparedContentVariables() {
        return [
            'contents' => $this->prepContent['contents'],
            'series_content' => $this->prepContent['contents'],
            'isValidPayment' => $this->prepContent['is_valid_payment'],
            'seriesType' => $this->prepContent['series_type'],
            'seriesCombo' => $this->prepContent['series_combo'],
        ];
    }

    /**
     * Prepare contents and view
     *
     * @param string $combo_slug
     * @param string $slug
     * @return void
     */
    private function processLessonContent(string $combo_slug, string $slug)
    {
        $params = compact('combo_slug', 'slug');

        $this->checkValidPayment($params);
        $this->prepareContentList($params);
    }

    /**
     * Check validity of payment,
     * if user (or guest) access the purchase content, redirect first content of the series
     *
     * @param array $params
     * @return mixed
     */
    private function checkValidPayment(array &$params)
    {
        $this->prepContent['series'] =
            $this->lmsSeriesService->getByCondition('slug', $params['slug']);
        $this->prepContent['series_id'] =
            optional($this->prepContent['series'])->id;

        $this->prepContent['series_combo_id'] =
            optional($this->prepContent['series_combo'])->id;
        $this->prepContent['series_type'] =
            optional($this->prepContent['series_combo'])->type;

        if (
            !$this->prepContent['series_id'] ||
            !$this->prepContent['series_combo_id']
        ) {
            throw new RedirectException(redirect()->to('/'));
        }

        // Both a guest user and a student who hasn't purchased the series have the same trial access.
        // A student who has purchased the series is granted full access to all content in the series.
        $userId = auth()->id() ?? -1;
        $seriesComboId = $this->prepContent['series_combo_id'];

        $this->prepContent['is_valid_payment']
            = $this->paymentMethodService->checkSerieValidity($userId, $seriesComboId);
    }

    /**
     * Prepare content list
     *
     * @param array $params
     * @return void
     */
    private function prepareContentList(array &$params)
    {
        $this->prepContent['contents'] =
            $this->lmsContentService->getListContents(
                $this->prepContent['series_id']
            )->sortBy('stt');

        $this->prepContent['contents']->each(function ($item) use ($params) {
            $this->setURLToPurchasedContents($item, $params);
        });
    }

    /**
     * Set URL to owned nested content
     *
     * @param App\LmsContent $lms_content
     * @param array $params
     * @return void
     */
    private function setURLToPurchasedContents(LmsContent &$lms_content, array &$params, int $chapter_index = 0)
    {
        $typeMap = config('constant.series.type_map');
        if (in_array($lms_content->type, $typeMap['title'])) {
            foreach ($lms_content->childContents as $childContent) {
                $this->setURLToPurchasedContents($childContent, $params, $chapter_index);
            }
        }

        // Set route
        $routes = config('constant.series.routes');
        $params['stt'] = $lms_content->id;
        foreach ($routes as $type => $route) {
            if (in_array($lms_content->type, $typeMap[$type])) {
                $lms_content->url = route($route, $params);
                break;
            }
        }

        if ($chapter_index > 0 && !$this->prepContent['is_valid_payment']) {
            return;
        }
    }
}
