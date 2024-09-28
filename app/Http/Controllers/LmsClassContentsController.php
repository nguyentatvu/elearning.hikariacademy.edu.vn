<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \App;
use App\Http\Requests;
use App\LmsCategory;
use App\LmsSettings;
use Yajra\DataTables\DataTables;
use DB;
use Auth;
use Image;
use ImageSettings;
use File;

class LmsClassContentsController extends Controller{
    public function __construct()
    {
    	$this->middleware('auth');
    }

    protected  $examSettings;

    public function setSettings()
    {
        $this->examSettings = getSettings('lms');
    }

    public function getSettings()
    {
        return $this->examSettings;
    }

    /**
     * Course listing method
     * @return Illuminate\Database\Eloquent\Collection
     */
    public function index(){
        if(!checkRole(getUserGrade(2)))
        {
            prepareBlockUserMessage();
            return back();
        }

        $data['active_class']       = 'lms';
        $data['title']              = 'Lớp học chỉ định';
        $data['URL_LMS_CLASS']      = PREFIX.'lms/class-content/add';
        $data['URL_LMS_CLASS_ADD']  = PREFIX.'lms/class-content/add';

        $view_name = 'admin.lms.lmsclasscontent.list';
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

        $records = DB::table('lms_class_series')
        ->select('lms_class_series.id','classes.name','lmsseries.title')
        ->join('classes','classes.id','=','lms_class_series.class_id')
        ->join('lmsseries','lmsseries.id','=','lms_class_series.series_id')
        ->where([
            ['lms_class_series.delete_status',0]
        ])
        ->orderBy('lms_class_series.id','desc');

        return DataTables::of($records)
        ->addColumn('action', function ($records) {
          return '<a href="'.PREFIX.'lms/class-content/detail/'.$records->id.'"><i class="fa fa-eye"> Xem</a>';
        })
        ->editColumn('name', function($records){
            return '<a href="'.PREFIX.'lms/class-content/detail/'.$records->id.'">'.$records->name.'</a>';
        })
        ->removeColumn('id')
        ->rawColumns(['action','name'])
        ->make();
    }

    /**
     * This method loads the create view
     * @return void
     */
    public function create(){
        if(!checkRole(getUserGrade(2)))
        {
            prepareBlockUserMessage();
            return back();
        }
        $data['record']         	= FALSE;
        $data['active_class']       = 'lms';
        $data['title']              = 'Thêm mới lớp học chỉ định';

        $categories_q = DB::table('lmsseries')
        ->select('id','title')->get();

        $categories = [];
        foreach($categories_q as $r){
            $categories[$r->id] = $r->title;
        }

        $classes_q = DB::table('classes')
        ->select(['id','name'])->get();
        $class = [];
        foreach($classes_q as $r){
            $class[$r->id] = $r->name;
        }

        $data['URL_LMS_CLASS'] = PREFIX.'lms/class-content';
        $data['URL_LMS_CLASS_ADD'] = PREFIX.'lms/class-content/add';
        $data['categories'] = $categories;
        $data['class']  = $class;

        $view_name = 'admin.lms.lmsclasscontent.add-edit';
        return view($view_name, $data);
    }

    /**
     * This method loads the edit view based on unique slug provided by user
     * @param  [string] $slug [unique slug of the record]
     * @return [view with record]       
     */
    public function edit($slug){
        if(!checkRole(getUserGrade(2))){
            prepareBlockUserMessage();
            return back();
        }
        $record = LmsCategory::getRecordWithSlug($slug);
        if($isValid = $this->isValidRecord($record))
            return redirect($isValid);

        $data['record']       		= $record;
        $data['active_class']       = 'lms';
        $data['title']              = getPhrase('edit_category');
        $view_name = 'admin.lms.lmsclasscontent.add-edit';
        return view($view_name, $data);
    }

    /**
     * Update record based on slug and reuqest
     * @param  Request $request [Request Object]
     * @param  [type]  $slug    [Unique Slug]
     * @return void
     */
    public function update(Request $request, $slug){
        if(!checkRole(getUserGrade(2)))
        {
            prepareBlockUserMessage();
            return back();
        }
        $record = LmsCategory::getRecordWithSlug($slug);

        $rules = [
         'category'          	   => 'bail|required|max:60' ,
     ];
         /**
        * Check if the title of the record is changed, 
        * if changed update the slug value based on the new title
        */
         $name = $request->category;
         if($name != $record->category)
            $record->slug = createSlug(LmsCategory::class, $name);

       //Validate the overall request
        $this->validate($request, $rules);
        $record->category 			= $name;
        $record->description		= $request->description;
        $record->record_updated_by 	= Auth::user()->id;
        $record->save();
        $file_name = 'catimage';
        if ($request->hasFile($file_name)){

            $rules = array( $file_name => 'mimes:jpeg,jpg,png,gif|max:10000' );
            $this->validate($request, $rules);

            $record->image      = $this->processUpload($request, $record,$file_name);
            $record->save();
        }

        flash('success','record_updated_successfully', 'success');
        return redirect(URL_LMS_CATEGORIES);
    }

    /**
     * This method adds record to DB
     * @param  Request $request [Request Object]
     * @return void
     */
    public function store(Request $request){
        if(!checkRole(getUserGrade(2)))
        {
            prepareBlockUserMessage();
            return back();
        }

        try{
            DB::table('lms_class_series')->insert([
                'class_id'        =>  $request->class_id,
                'series_id'  =>  $request->categories_id,
                'type_series'  =>  $request->type_series,
                'created_by'        =>  Auth::id(),
            ]);
        }catch(Exception $e){
            flash('error','Error', 'error');
            return back();
        }


        flash('success','record_added_successfully', 'success');
        return redirect(PREFIX.'lms/class-content');
    }

    /**
     * Delete Record based on the provided slug
     * @param  [string] $slug [unique slug]
     * @return Boolean 
     */
    public function delete($slug){
        if(!checkRole(getUserGrade(2)))
        {
            prepareBlockUserMessage();
            return back();
        }
        $record = LmsCategory::where('slug', $slug)->first();

        try{
            $this->setSettings();

            $examSettings = $this->getSettings();
            $path = IMAGE_PATH_UPLOAD_LMS_CATEGORIES;
            $r =  $record;
            if(!env('DEMO_MODE')) {
                $record->delete();
                $this->deleteFile($r->image, $path);
            }

            $response['status'] = 1;
            $response['message'] = getPhrase('category_deleted_successfully');
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

    public function isValidRecord($record){
        if ($record === null) {

            flash('Ooops...!', getPhrase("page_not_found"), 'error');
            return $this->getRedirectUrl();
        }

        return FALSE;
    }

    public function getReturnUrl()
    {
        return URL_LMS_CATEGORIES;
    }

    public function deleteFile($record, $path, $is_array = FALSE)
    {

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

        if ($request->hasFile($file_name)) {
            $settings = json_decode((new LmsSettings())->getSettings());


            $destinationPath      = $settings->categoryImagepath;
            $fileName = $record->id.'-'.$file_name.'.'.$request->$file_name->guessClientExtension();

            $request->file($file_name)->move($destinationPath, $fileName);

         //Save Normal Image with 300x300
            Image::make($destinationPath.$fileName)->fit($settings->imageSize)->save($destinationPath.$fileName);
            return $fileName;
        }
    }

    public function detail($id){
        if(!checkRole(getUserGrade(2)))
        {
            prepareBlockUserMessage();
            return back();
        }

        $list_content = DB::table('lmscontents')
        ->select(['lmscontents.id'])
        ->join('lms_class_series','lmscontents.lmsseries_id','=','lms_class_series.series_id')
        ->where([
            ['lms_class_series.id',$id],
            ['lmscontents.delete_status',0],
        ])
        ->whereIn('type',[0,5])
        ->get();

        $list_class_series = DB::table('lms_class_series_data')
        ->select(['content_id'])
        ->where([
            ['class_series_id',$id],
            ['delete_status',0],
        ])
        ->get();
        $check_array = [];
        foreach($list_class_series as $r){
            $check_array[] = $r->content_id;
        }
        foreach($list_content as $r){
            if(!in_array($r->id, $check_array)){
                DB::table('lms_class_series_data')
                ->insert([
                    'content_id'        => $r->id,
                    'class_series_id'   => $id,
                    'created_by'        => Auth::user()->id,
                ]);
            }
        }
        
        $data['ajax']               = PREFIX.'lms/class-content/ajax-update-status';
        $data['active_class']       = 'lms';
        $data['title']              = 'Chi tiết khóa học';
        $data['url_datatable']      = PREFIX.'lms/class-content/detail-datatable/getList/'.$id;
        $view_name = 'admin.lms.lmsclasscontent.detail.list';
        return view($view_name, $data);
        
    }

    public function detailDatatable($id){
        if(!checkRole(getUserGrade(2))){
            prepareBlockUserMessage();
            return back();
        }
        
        $records = DB::table('lms_class_series_data')
        ->select('content_id','lmscontents.bai','show_status','lms_class_series_data.id as id')
        ->join('lmscontents','lmscontents.id','=','lms_class_series_data.content_id')
        ->join('lmsseries','lmsseries.id','=','lmscontents.lmsseries_id')
        ->join('lms_class_series','lms_class_series.series_id','=','lmsseries.id')
        ->where([
            ['lmscontents.delete_status',0],
            ['lms_class_series_data.class_series_id',$id],
        ])
        ->distinct('lmscontents.id')
        ->orderBy('lmscontents.stt','asc');

        return DataTables::of($records)
        ->editColumn('show_status',function($records){
            if($records->show_status == 1)
                return '<span class="label label-success">Đã hiển thị</span>';
            else
                return '<span class="label label-warning">Chưa hiển thị</span>';
        })
        ->addColumn('status',function($records){
            $check_status = ($records->show_status == 1) ? 'success' : 'warning';
            return "<button class='btn btn-primary btn-update-status' onclick='update_status(".$records->id.")'>Đổi trạng thái</button>"; 
        })
        ->removeColumn('id')
        ->rawColumns(['show_status','status'])
        ->make();
    }

    public function update_status(Request $request){
        try{
            $show_status = DB::table('lms_class_series_data')
            ->select('show_status')
            ->where('id',$request->id)
            ->get()->first();
            $up = ($show_status->show_status == '1') ? '0' : '1';

            DB::table('lms_class_series_data')
            ->where('id',$request->id)
            ->update([
                'show_status' => $up,
            ]);
            return 'success';
        }catch(Exception $e){
            return $e;
        }
        return 'err';
    }

}