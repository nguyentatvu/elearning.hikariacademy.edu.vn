<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Requests;
use \App;
use App\Subject;
use App\LmsSeries;
use App\LmsCombo;
use Yajra\DataTables\DataTables;
use DB;
use Auth;
use Image;
use ImageSettings;
use File;
use Input;
use Excel;
class LmsComboController extends Controller
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

        $data['URL_IMPORT_CONTENT']     = PREFIX."lms//series/import-excel";
        $data['create_url']			= PREFIX.'lms/seriescombo/add';
        $data['datatbl_url']		= PREFIX.'lms/seriescombo/getExamList';
        $data['active_class']       = 'lms';
        $data['title']              = 'Khóa combo';
        // dd($data);
        $view_name = 'admin.lmscombo.lmsseries.list';
        return view($view_name, $data);
    }


    public function getDatatable(){
        if(!checkRole(getUserGrade(2))){
            prepareBlockUserMessage();
            return back();
        }
        $records = array();
        DB::statement(DB::raw('set @rownum=0'));

        // TODO: Add time FROM-TO issue 6
        $records = DB::table('lmsseries_combo')
            ->select([DB::raw('@rownum  := @rownum  + 1 AS stt'),'title','cost', 'redeem_point', 'selloff', 'timefrom', 'timeto', 'time','type','slug'])
            ->where('delete_status',0)
            ->get();
        return DataTables::of($records)
            ->addColumn('action', function ($records) {
                $link_data = '<div class="dropdown more">
			<a id="dLabel" type="button" class="more-dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
			<i class="mdi mdi-dots-vertical"></i>
			</a>
			<ul class="dropdown-menu dropdown-menu-right" aria-labelledby="dLabel">
			
			<li><a href="'.'seriescombo/edit/'.$records->slug.'"><i class="fa fa-pencil"></i>'.getPhrase("edit").'</a></li>';
                $temp = '';
                if(checkRole(getUserGrade(1))) {
                    $temp .= ' <li><a href="javascript:void(0);" onclick="deleteRecord(\''.$records->slug.'\');"><i class="fa fa-trash"></i>'. getPhrase("delete").'</a></li>';
                }
                $temp .='</ul></div>';
                $link_data .=$temp;
                return $link_data;
            })

            ->editColumn('cost', function($records)
            {

                return number_format($records->cost, 0, 0, '.') .'đ';
            })

            ->editColumn('selloff', function($records)
            {

                return number_format($records->selloff, 0, 0, '.') .'đ';
            })
            ->editColumn('redeem_point', function($records) {
                return ((int) $records->redeem_point) . ' coins';
            })
            ->editColumn('timefrom', function($records)
            {
                if($records->timefrom != null && $records->timeto)
                {
                    $timeFrom =  date("Y-m-d", strtotime($records->timefrom));
                    $timeTo =  date("Y-m-d", strtotime($records->timeto));
                    $result = $timeFrom . ' 〜 ' . $timeTo;
                    return $result ;
                }

            })

            ->editColumn('time', function($records)
            {
                $time_options = array(0 => '3 tháng', 1 => '6 tháng' , 2 => '12 tháng');
                return $time_options[$records->time];
            })
            ->editColumn('type', function($records)
            {
                $category_options = array(0 => 'Khóa học', 1 => 'Khóa luyện thi');
                return $category_options[$records->type];
            })
            /*->editColumn('title', function($records)
            {
                return '<a href="'.PREFIX.'lms/'.$records->slug.'/content'.'">'.$records->title.'</a>';
            })

            ->editColumn('is_paid', function($records)
            {
                return ($records->is_paid) ? '<span class="label label-primary">Trả phí</span>' : '<span class="label label-success">'.getPhrase('free').'</span>';
            })*/
            /*->removeColumn('id')

            ->removeColumn('updated_at')*/
            ->removeColumn('slug')
            ->removeColumn('timeto')
            ->rawColumns(['action'])
            ->make();
    }

    public function create(){
        if(!checkRole(getUserGrade(2))){
            prepareBlockUserMessage();
            return back();
        }
// khóa học
        $data['n1']         = array(null => 'Select')+ array_pluck(DB::table('lmsseries')->where([
                                            ['lms_category_id','=', 1],
                                            ['delete_status','=', 0],
                                            ['type_series','=', 0]
                                                ])->get(), 'title', 'id');
        $data['n2']         = array(null => 'Select')+ array_pluck(DB::table('lmsseries')->where([
            ['lms_category_id','=', 2],
            ['delete_status','=', 0],
            ['type_series','=', 0]
        ])->get(), 'title', 'id');
        $data['n3']         = array(null => 'Select')+ array_pluck(DB::table('lmsseries')->where([
            ['lms_category_id','=', 3],
            ['delete_status','=', 0],
            ['type_series','=', 0]
        ])->get(), 'title', 'id');
        $data['n4']         = array(null => 'Select')+ array_pluck(DB::table('lmsseries')->where([
            ['lms_category_id','=', 4],
            ['delete_status','=', 0],
            ['type_series','=', 0]
        ])->get(), 'title', 'id');
        $data['n5']         = array(null => 'Select')+ array_pluck(DB::table('lmsseries')->where([
            ['lms_category_id','=', 5],
            ['delete_status','=', 0],
            ['type_series','=', 0]
        ])->get(), 'title', 'id');

        /*$data['n6']         = array(null => 'Select')+ array_pluck(DB::table('lmsseries')->where([
                ['lms_category_id','=', 6],
                ['delete_status','=', 0],
            ])->get(), 'title', 'id');*/
// khóa luyện thi
        $data['en1']         = array(null => 'Select')+ array_pluck(DB::table('lmsseries')->where([
            ['lms_category_id','=', 1],
            ['delete_status','=', 0],
            ['type_series','=', 1]
        ])->get(), 'title', 'id');
        $data['en2']         = array(null => 'Select')+ array_pluck(DB::table('lmsseries')->where([
            ['lms_category_id','=', 2],
            ['delete_status','=', 0],
            ['type_series','=', 1]
        ])->get(), 'title', 'id');
        $data['en3']         = array(null => 'Select')+ array_pluck(DB::table('lmsseries')->where([
            ['lms_category_id','=', 3],
            ['delete_status','=', 0],
            ['type_series','=', 1]
        ])->get(), 'title', 'id');
        $data['en4']         = array(null => 'Select')+ array_pluck(DB::table('lmsseries')->where([
            ['lms_category_id','=', 4],
            ['delete_status','=', 0],
            ['type_series','=', 1]
        ])->get(), 'title', 'id');
        $data['en5']         = array(null => 'Select')+ array_pluck(DB::table('lmsseries')->where([
            ['lms_category_id','=', 5],
            ['delete_status','=', 0],
            ['type_series','=', 1]
        ])->get(), 'title', 'id');

       // dd($data['n1']);
        $data['URL_LMS_SERIES'] 	= PREFIX.'lms/seriescombo';
        $data['URL_LMS_SERIES_ADD'] = PREFIX.'lms/seriescombo/add';
        $data['record']           	= FALSE;
        $data['active_class']       = 'lms';
        $data['title']              = getPhrase('add_series');
        // return view('lms.lmsseries.add-edit', $data);
        $view_name = 'admin.lmscombo.lmsseries.add-edit';
        return view($view_name, $data);
    }

    public function store(Request $request)
    {
        if(!checkRole(getUserGrade(2)))
        {
            prepareBlockUserMessage();
            return back();
        }


        $rules = [
            'title'              => 'bail|required|max:400' ,
            'cost'              =>  'required',
            'time'       =>  'required',
            'type'       =>  'required',
            'timefrom'       =>  'required',
            'timeto'       =>  'required'
        ];
        $this->validate($request, $rules);

        $total = 0;

        if ($request->n1 != "" ){
            $total +=1;
        }
        if ($request->n2 != "" ){
            $total +=1;
        }
        if ($request->n3 != "" ){
            $total +=1;
        }
        if ($request->n4 != "" ){
            $total +=1;
        }
        if ($request->n5 != "" ){
            $total +=1;
        }
       /* if ($request->n6 != "" ){
            $total +=1;
        }*/
        $record = new LmsCombo();
        /*$record = new LmsCombo();

        dd($record->makeSlug($request->title, TRUE));
        dd($record);*/
        try{

            DB::beginTransaction();
            /*DB::table('lmsseries_combo')->insert([
                [
                    'lmsseries_combo'           =>  $request->title,
                    'slug'               =>  $request->ten,
                    'taitrong_thetich'  =>  $request->taitrong,
                    'cancu'             =>  $request->cancu,
                    'created_by'        =>  \Auth::user()->id,
                ],
            ]);*/
            $record->redeem_point = $request->redeem_point / config('constant.redeemed_coin.vnd_convert_rate');
            $name                 =  $request->title;
            $record->title        = $name;
            $record->slug         = createSlug($name);
            $record->cost         = $request->cost ;
            $record->selloff         = $request->selloff ;

            $record->total_items    = $total;
            $record->short_description  = $request->short_description;
            $description_object = [
                'content_description' => $request->content_description ?? '',
                'time_description' => $request->time_description ?? '',
                'curriculum_description' => $request->curriculum_description ?? '',
                'teacher_description' => $request->teacher_description ?? ''
            ];
            $record->description = json_encode($description_object);


            $record->time   = $request->time;
            $record->type   = $request->type;

            $record->n1   = ($request->n1 != "" ? $request->n1 : null);
            $record->n2   = ($request->n2 != "" ? $request->n2 : null);
            $record->n3   = ($request->n3 != "" ? $request->n3 : null);
            $record->n4   = ($request->n4 != "" ? $request->n4 : null);
            $record->n5   = ($request->n5 != "" ? $request->n5 : null);
            //$record->n6   = ($request->n6 != "" ? $request->n6 : null);


            $record->created_by  = Auth::user()->id;

            // TODO: Add time FROM-TO issue 6
            $timefrom = date_create($request->timefrom);
            $timeto = date_create($request->timeto);
            $timefromFormat = date_format($timefrom, 'Y-m-d 00:00:00');
            $timeToFormat = date_format($timeto, 'Y-m-d 23:59:59');
            $record->timefrom = $timefromFormat;
            $record->timeto = $timeToFormat;

            $record->save();
            /*$record->image   = $request->image;*/
            $file_name = 'image';
            if ($request->hasFile($file_name))
            {
                $rules = array( $file_name => 'mimes:jpeg,jpg,png,gif|max:10000' );
                $this->validate($request, $rules);
                //$examSettings = getSettings('lms');
                $path = 'uploads/lms/combo/';
                $this->deleteFile($record->image, $path);
                $record->image      = $this->processUpload($request, $record,$file_name);
                $record->save();
            }




            DB::commit();
            flash('Thêm thành công','', 'success');
            return redirect(url('lms/seriescombo'));
        }catch(Exception $e){
            // dd($e);
            DB::rollBack();
            flash('error','Thông báo sẽ tự đóng sau 1s', 'error');
            return redirect(url('lms/seriescombo/add'));
        }



    }



    public function edit($slug){
        if(!checkRole(getUserGrade(2)))
        {
            prepareBlockUserMessage();
            return back();
        }
        $record = LmsCombo::getRecordWithSlug($slug);
        if($isValid = $this->isValidRecord($record))
            return redirect($isValid);


// khóa học
        $data['n1']         = array(null => 'Select')+ array_pluck(DB::table('lmsseries')->where([
                ['lms_category_id','=', 1],
                ['delete_status','=', 0],
                ['type_series','=', 0]
            ])->get(), 'title', 'id');
        $data['n2']         = array(null => 'Select')+ array_pluck(DB::table('lmsseries')->where([
                ['lms_category_id','=', 2],
                ['delete_status','=', 0],
                ['type_series','=', 0]
            ])->get(), 'title', 'id');
        $data['n3']         = array(null => 'Select')+ array_pluck(DB::table('lmsseries')->where([
                ['lms_category_id','=', 3],
                ['delete_status','=', 0],
                ['type_series','=', 0]
            ])->get(), 'title', 'id');
        $data['n4']         = array(null => 'Select')+ array_pluck(DB::table('lmsseries')->where([
                ['lms_category_id','=', 4],
                ['delete_status','=', 0],
                ['type_series','=', 0]
            ])->get(), 'title', 'id');
        $data['n5']         = array(null => 'Select')+ array_pluck(DB::table('lmsseries')->where([
                ['lms_category_id','=', 5],
                ['delete_status','=', 0],
                ['type_series','=', 0]
            ])->get(), 'title', 'id');
// khóa luyện thi
        $data['en1']         = array(null => 'Select')+ array_pluck(DB::table('lmsseries')->where([
                ['lms_category_id','=', 1],
                ['delete_status','=', 0],
                ['type_series','=', 1]
            ])->get(), 'title', 'id');
        $data['en2']         = array(null => 'Select')+ array_pluck(DB::table('lmsseries')->where([
                ['lms_category_id','=', 2],
                ['delete_status','=', 0],
                ['type_series','=', 1]
            ])->get(), 'title', 'id');
        $data['en3']         = array(null => 'Select')+ array_pluck(DB::table('lmsseries')->where([
                ['lms_category_id','=', 3],
                ['delete_status','=', 0],
                ['type_series','=', 1]
            ])->get(), 'title', 'id');
        $data['en4']         = array(null => 'Select')+ array_pluck(DB::table('lmsseries')->where([
                ['lms_category_id','=', 4],
                ['delete_status','=', 0],
                ['type_series','=', 1]
            ])->get(), 'title', 'id');
        $data['en5']         = array(null => 'Select')+ array_pluck(DB::table('lmsseries')->where([
                ['lms_category_id','=', 5],
                ['delete_status','=', 0],
                ['type_series','=', 1]
            ])->get(), 'title', 'id');

        // TODO: Add time FROM-TO issue 6
        if($record->timefrom != null && $record->timeto != null)
        {
            $record->timefrom = date("Y/m/d", strtotime($record->timefrom));
            $record->timeto =date("Y/m/d", strtotime($record->timeto));
        } else {
            $record->timefrom = date("Y/m/d");
            $record->timeto =date("Y/m/d");
        }

        $data['URL_LMS_SERIES'] 	= PREFIX.'lms/seriescombo';
        $data['URL_LMS_SERIES_ADD'] = PREFIX.'lms/seriescombo/add';
        $data['URL_LMS_SERIES_EDIT']= PREFIX.'lms/seriescombo/edit/';
        $data['record']           	= $record;
        $data['active_class']     	= 'lms';
        $data['settings']         	= FALSE;
        $data['title']            	= getPhrase('edit_series');
        // return view('lms.lmsseries.add-edit', $data);
        $view_name = 'admin.lmscombo.lmsseries.add-edit';
        return view($view_name, $data);
    }


    public function update(Request $request,$slug){
        if(!checkRole(getUserGrade(2)))
        {
            prepareBlockUserMessage();
            return back();
        }
        //die();
        $record = LmsCombo::getRecordWithSlug($slug);

        $rules = [
            'title'              => 'bail|required|max:400' ,
            'cost'              =>  'required',
            'time'       =>  'required',
            'type'       =>  'required',
        ];

        $this->validate($request, $rules);

        $total = 0;

        if ($request->n1 != "" ){
            $total +=1;
        }
        if ($request->n2 != "" ){
            $total +=1;
        }
        if ($request->n3 != "" ){
            $total +=1;
        }
        if ($request->n4 != "" ){
            $total +=1;
        }
        if ($request->n5 != "" ){
            $total +=1;
        }
        /*if ($request->n6 != "" ){
            $total +=1;
        }*/

        /**
         * Check if the title of the record is changed,
         * if changed update the slug value based on the new title
         */
        //$record = new LmsCombo();
        /*$record = new LmsCombo();

        dd($record->makeSlug($request->title, TRUE));
        dd($record);*/
        try{

            DB::beginTransaction();
            /*DB::table('lmsseries_combo')->insert([
                [
                    'lmsseries_combo'           =>  $request->title,
                    'slug'               =>  $request->ten,
                    'taitrong_thetich'  =>  $request->taitrong,
                    'cancu'             =>  $request->cancu,
                    'created_by'        =>  \Auth::user()->id,
                ],
            ]);*/
            $name = $request->title;
            if($name != $record->title){
                $record->slug = createSlug($name);
            }


            $record->redeem_point = $request->redeem_point / config('constant.redeemed_coin.vnd_convert_rate');
            $record->title        = $name;
            $record->cost         = $request->cost ;
            $record->selloff         = $request->selloff ;

            $record->total_items    = $total;
            $record->short_description  = $request->short_description;
            $description_object = [
                'content_description' => $request->content_description ?? '',
                'time_description' => $request->time_description ?? '',
                'curriculum_description' => $request->curriculum_description ?? '',
                'teacher_description' => $request->teacher_description ?? ''
            ];
            $record->description = json_encode($description_object);


            $record->time   = $request->time;
            $record->type   = $request->type;

            $record->n1   = ($request->n1 != "" ? $request->n1 : null);
            $record->n2   = ($request->n2 != "" ? $request->n2 : null);
            $record->n3   = ($request->n3 != "" ? $request->n3 : null);
            $record->n4   = ($request->n4 != "" ? $request->n4 : null);
            $record->n5   = ($request->n5 != "" ? $request->n5 : null);
            //$record->n6   = ($request->n6 != "" ? $request->n6 : null);



            $record->updated_by  = Auth::user()->id;
            $record->updated_at = date("Y-m-d H:i:s");

            //dd($record);

            // TODO: Add time FROM-TO issue 6
            $timefrom = date_create($request->timefrom);
            $timeto = date_create($request->timeto);
            $timefromFormat = date_format($timefrom, 'Y-m-d 00:00:00');
            $timeToFormat = date_format($timeto, 'Y-m-d 23:59:59');
            $record->timefrom = $timefromFormat;
            $record->timeto = $timeToFormat;

            $record->save();
            /*$record->image   = $request->image;*/
            $file_name = 'image';
            if ($request->hasFile($file_name))
            {
                $rules = array( $file_name => 'mimes:jpeg,jpg,png,gif|max:10000' );
                $this->validate($request, $rules);
                //$examSettings = getSettings('lms');
                $path = 'uploads/lms/combo/';
                $this->deleteFile($record->image, $path);
                $record->image      = $this->processUpload($request, $record,$file_name);
                $record->save();
            }




            DB::commit();
            flash('Thêm thành công','', 'success');
            return redirect(url('lms/seriescombo'));
        }catch(Exception $e){
            // dd($e);
            DB::rollBack();
            flash('error','Thông báo sẽ tự đóng sau 1s', 'error');
            return redirect(url('lms/seriescombo/edit/'.$slug));
        }
    }

    public function delete($slug){
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
        $record = LmsCombo::where('slug', $slug)->first();
        if(!$record)
        {
            $response['status'] = 0;
            $response['message'] = getPhrase('invalid_record');
            return json_encode($response);
        }
        try{
            if(!env('DEMO_MODE')) {
                $record->delete_status = 1;
                $record->save();
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

    public function deleteFile($record, $path, $is_array = FALSE){
        if(env('DEMO_MODE')) {
            return;
        }
        $files = array();
        $files[] = $path.$record;
        File::delete($files);
    }

    public function processUpload(Request $request, $record, $file_name){
        if(env('DEMO_MODE')) {
            return 'demo';
        }
        if ($request->hasFile($file_name)) {
            $examSettings = getSettings('lms');
            $imageObject = new ImageSettings();
            $destinationPath            = 'uploads/lms/combo/';
            $destinationPathThumb       = $examSettings->seriesThumbImagepath;
            $fileName = $record->id.'-'.$file_name.'.'.$request->$file_name->guessClientExtension();
            $request->file($file_name)->move($destinationPath, $fileName);
            //Save Normal Image with 300x300
            Image::make($destinationPath.$fileName)->save($destinationPath.$fileName);
            //Image::make($destinationPath.$fileName)->fit($imageObject->getThumbnailSize())->save($destinationPathThumb.$fileName);
            return $fileName;
        }
    }

    public function isValidRecord($record)
    {
        if ($record === null) {
            flash('Ooops...!', getPhrase("page_not_found"), 'error');
            return $this->getRedirectUrl();
        }
        return FALSE;
    }
}
