<?php
namespace App\Http\Controllers;
use App\Comment;
use App\EmailTemplate;
use App\ExamSeries;
use App\LmsSeries;
use App\LmsSeriesCombo;
use App\Payment;
use App\PaymentMethod;
// use App\Paypal;
use App\Quiz;
use App\User;
use Auth;
use Carbon;
use DB;
use Excel;
use Exception;
use Illuminate\Http\Request;
use Input;
use Razorpay\Api\Api;
use Softon\Indipay\Facades\Indipay;
use Yajra\DataTables\DataTables;
use \App;
use Illuminate\Support\Facades\Log;

class CommentController extends Controller
{
    public function __construct()
    {
        //$this->middleware('auth');
    }
    public function store(Request $request){
        $rules = ['body'  =>  'required',];
        $this->validate($request, $rules);
        $record = new Comment();

        try {
            DB::beginTransaction();
            $lmsseries_id = DB::table('lmsseries')
                ->select('id')
                ->where('slug' ,$request->lmsseries_slug)
                ->first();
            $lmscombo_id = DB::table('lmsseries_combo')
                ->select('id')
                ->where('slug' ,$request->lmscombo_slug)
                ->first();
            $record->user_id                  =  $request->user_id;
            $record->user_name                =  Auth::user()->name;
            $record->body                     =  $request->body;
            $record->lmsseries_id            =$lmsseries_id->id;
            $record->lmscombo_id            =  $lmscombo_id->id;
            $record->lmscontent_id            =  $request->lmscontent_id;
            $record->parent_id                =  $request->parent_id;
            $record->save();
            if ($request->parent_id != 0){
                DB::table('comments')
                    ->where('id', $request->parent_id)
                    ->update(
                        [
                            'status' => 0,
                            'updated_at' =>date("Y-m-d H:i:s"),
                        ]
                    );
            }
            DB::commit();
            $data = array('error'=>1,'message' =>'Đặt câu hỏi thành công');
            //$data = array('error'=>1,'message' =>$record);
            $email = sendEmail('thongbaocomment', array('name'=>'', 'to_email' => env('TO_NOTIFY_EMAIL')));
            return json_encode($record);
        }catch(Exception $e){
            DB::rollBack();
            // dd($e);
            Log::error("message: " . $e);
            $data = array('error'=>2, 'message'=>'Đặt câu hỏi thất bại');
            //$data = array('error'=>2, 'message'=>$e);
            return json_encode($data);
        }
    }
    public function update(Request $request){
        $rules = ['body'  =>  'required',];
        $this->validate($request, $rules);
        $record = new Comment();
        try {
            DB::beginTransaction();
            $record->user_id                  =  $request->user_id;
            $record->user_name                =  Auth::user()->name;
            $record->body                     =  $request->body;
            $record->lmsseries_slug           =  $request->lmsseries_slug;
            $record->lmscombo_slug            =  $request->lmscombo_slug;
            $record->parent_id                =  $request->parent_id;;
            $record->save();
            DB::commit();
            $data = array('error'=>1,'message' =>'Đặt câu hỏi thành công');
            //$data = array('error'=>1,'message' =>$record);
            return json_encode($data);
        }catch(Exception $e){
            DB::rollBack();
            // dd($e);
            $data = array('error'=>2, 'message'=>'Đặt câu hỏi thất bại');
            return json_encode($data);
        }
    }
    public function index(Request $request){


        $lmsseries_id = DB::table('lmsseries')
            ->select('id')
            ->where('slug' ,$request->slug)
            ->first();
        $lmscombo_id = DB::table('lmsseries_combo')
            ->select('id')
            ->where('slug' ,$request->combo_slug)
            ->first();
        $comment = DB::table('comments')
            ->where([
                ['user_id',Auth::id()],
                ['lmsseries_id',$lmsseries_id->id],
                ['lmscombo_id',$lmscombo_id->id],
                ['lmscontent_id',$request->id],
                ['parent_id',0],
            ])
            ->get();
        $comment_child = DB::table('comments')
            ->where([
                ['user_id',Auth::id()],
                ['lmsseries_id',$lmsseries_id->id],
                ['lmscombo_id',$lmscombo_id->id],
                ['lmscontent_id',$request->id],
                ['parent_id','!=',0],
            ])
            ->get();
        $result = '';
        if (count($comment) > 0){
            foreach ($comment as $r){
                $name = $r->user_name;
                if ($r ->admin_id !== null ){
                    $name = DB::table('users')
                        ->select('name')
                        ->where('id' ,$r ->admin_id)
                        ->first()->name;
                }
                $result .='
                                <div class="media mt-0 p-5 border-top">
                                        <div class="media-body">
                                            <h4 class="mt-0 mb-1 font-weight-bold">
                                                '.$name.'
                                                <span class="fs-14 ml-0" data-toggle="tooltip" data-placement="top" title="" data-original-title="verified"><i class="fa fa-check-circle-o text-success"></i></span>
                                            </h4>
                                            <small class="text-muted">
                                                <i class="fa fa-calendar"></i>
                                                '.date_format(date_create($r->created_at),"d-m-Y H:m:i").'
                                            </small>
                                            <p class="font-13  mb-2 mt-2">
                                               '.$r->body.'
                                            </p>
                                            <a href="javascript:void(0)" class="mr-2" onclick="myModal('.$r->id.')"><span class="badge badge-default">Comment</span></a>
                                            ';
                if (count($comment_child) > 0){
                    foreach($comment_child as $cr){
                        if($cr->parent_id == $r->id){
                            $cname = $cr->user_name;
                            if ($cr ->admin_id !== null ){
                                $cname = DB::table('users')
                                    ->select('name')
                                    ->where('id' ,$cr ->admin_id)
                                    ->first()->name;
                            }
                            $result .='<div class="media mt-5">
                                                        <div class="d-flex mr-5">
                                                        </div>
                                                        <div class="media-body">
                                                            <h4 class="mt-0 mb-1 font-weight-bold">'.$cname.' <span class="fs-14 ml-0" data-toggle="tooltip" data-placement="top" title="" data-original-title="verified"><i class="fa fa-check-circle-o text-success"></i></span></h4>
                                                            <small class="text-muted">
                                                                <i class="fa fa-calendar"></i> '.date_format(date_create($cr->created_at),"d-m-Y H:m:i").'
                                                            </small>
                                                            <p class="font-13  mb-2 mt-2">
                                                                '.$cr->body.'
                                                            </p>
                                                            <a href="javascript:void(0)" onclick="myModal('.$r->id.')"><span class="badge badge-default">Comment</span></a>
                                                        </div>
                                                    </div>';
                        }
                    }
                }
                $result .=    '</div>
                                </div>
                ';
            }
        }
        $data = array('error'=>1, 'message'=>$result);
        return json_encode($data);
    }
    public function countComments(Request $request){
        /* if(!checkRole(getUserGrade(2))){
             prepareBlockUserMessage();
             return back();
         }*/
        try {
            $last_session = true;
            // if (Session()->getId() != Auth::user()->last_session) {
            //     $last_session = false;                 
            // }
            $records = DB::table('comments')
                ->where([
                    ['user_id' , $request->id],
                    ['status' , 1],
                    ['parent_id', 0]
                ])
                ->count();
            $data = array('error'=>1,'message' =>'Count comment thành công', 'count' => $records, 'last_session'=>$last_session);
            return json_encode($data);
        }catch(Exception $e){
            //DB::rollBack();
            // dd($e);
            $data = array('error'=>2, 'message'=>'Count comment thất bại' , 'count' => null);
            //$data = array('error'=>2, 'message'=>$e);
            return json_encode($data);
        }
    }
    public function listComments(){
         // echo Session()->getId(); exit;
        $data['active_class']       = 'lmscomments';
        $data['title']              = 'Câu hỏi';
        $records = DB::table('comments')
            ->join('lmsseries_combo','lmsseries_combo.id','comments.lmscombo_id')
            ->join('users','users.id','comments.user_id')
            ->select([DB::raw('@rownum  := @rownum  + 1 AS stt'),
                'comments.body','users.name','lmsseries_combo.title','comments.updated_at','comments.status',
                'comments.id','comments.lmsseries_id','comments.lmscombo_id','comments.lmscontent_id'])
            ->where('comments.user_id',Auth::id())
            ->where('comments.parent_id',0)
            ->orderByRaw('FIELD(comments.status, "0", "2", "1")')
            ->orderBy('comments.updated_at', 'desc')
            ->get();
        $data['series'] = $records;
        $data['layout']              = 'admin.layouts.student.studentsettinglayout';
        // return view('student.exams.exam-series-list', $data);
        $view_name = 'admin.student.lms.lms-comments';
        return view($view_name, $data);
    }
    public function listgetDatatable(){
        $records = array();
        DB::statement(DB::raw('set @rownum=0'));
        $records = DB::table('comments')
            ->join('lmsseries_combo','lmsseries_combo.id','comments.lmscombo_id')
            ->join('users','users.id','comments.user_id')
            ->select([DB::raw('@rownum  := @rownum  + 1 AS stt'),
                'comments.body','users.name','lmsseries_combo.title','comments.updated_at','comments.status',
                'comments.id','comments.lmsseries_id','comments.lmscombo_id'])
            // ->where('comments.status',0)
            ->where('comments.parent_id',0)
            ->orderBy('comments.status', 'asc')
            ->orderBy('comments.updated_at', 'desc')
            ->get();
        return Datatables::of($records)
            ->addColumn('action', function ($records) {
                $link_data = '<div class="dropdown more">
            <a id="dLabel" type="button" class="more-dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="mdi mdi-dots-vertical"></i>
            </a>
            <ul class="dropdown-menu dropdown-menu-right" aria-labelledby="dLabel">
            <li><a href="javascript:void(0)" onclick="myModal('.$records->id.','.$records->lmsseries_id.','.$records->lmscombo_id.')"><i class="fa fa-pencil"></i>Reply</a></li>';
                $temp = '';
                if(checkRole(getUserGrade(1))) {
                    $temp .= ' <li><a href="javascript:void(0);" onclick="deleteRecord(\''.$records->id.'\');"><i class="fa fa-trash"></i>'. getPhrase("delete").'</a></li>';
                }
                $temp .='</ul></div>';
                $link_data .=$temp;
                return $link_data;
            })
            ->editColumn('status', function($records) {
                $dr_status = array(0 => 'Chưa đọc' , 1 => 'Đã phản hồi' , 2 =>'Đã xem' );
                return $dr_status[$records->status];
            })
            ->removeColumn('id')
            ->removeColumn('lmsseries_id')
            ->removeColumn('lmscombo_id')
            ->make();
    }
    // admin comments
    public function indexadmin(){
        if(!checkRole(getUserGrade(2))){
            prepareBlockUserMessage();
            return back();
        }
        $data['active_class']       = 'comments';
        $data['title']              = 'Comments';
        // dd($data);
        $view_name = 'admin.comments.list';
        return view($view_name, $data);
    }
    public function getDatatable(){
        if (!(checkRole(getUserGrade(2)) || checkRole(getUserGrade(4)))) {
            prepareBlockUserMessage();
            return back();
        }
        $records = array();
        DB::statement(DB::raw('set @rownum=0'));
        $records = DB::table('comments')
            ->join('lmsseries_combo','lmsseries_combo.id','comments.lmscombo_id')
            ->join('users','users.id','comments.user_id')
            ->select([DB::raw('@rownum  := @rownum  + 1 AS stt'),
                'comments.body','users.name','lmsseries_combo.title','comments.updated_at','comments.status',
                'comments.id','comments.lmsseries_id','comments.lmscombo_id','comments.lmscontent_id'])
            // ->where('comments.status',0)
            ->where('comments.parent_id',0)
           // ->orderBy('comments.status', 'asc')
            // ->orderByRaw('FIELD(comments.status, "0", "2", "1")')
            ->orderBy('comments.created_at', 'desc')
            ->get();
        $i =1 ;
        foreach ($records as $record){
            $record->stt = $i;
            $i++;
        }
        return DataTables::of($records)
            ->addColumn('action', function ($records) {
                $link_data = '<div class="dropdown more">
            <a id="dLabel" type="button" class="more-dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="mdi mdi-dots-vertical"></i>
            </a>
            <ul class="dropdown-menu dropdown-menu-right" aria-labelledby="dLabel">
            <li><a href="javascript:void(0)" onclick="myModal('.$records->id.','.$records->lmsseries_id.','.$records->lmscombo_id.')"><i class="fa fa-pencil"></i>Reply</a></li>';
                $temp = '';
                if(checkRole(getUserGrade(1))) {
                    $temp .= ' <li><a href="javascript:void(0);" onclick="deleteRecord(\''.$records->id.'\');"><i class="fa fa-trash"></i>'. getPhrase("delete").'</a></li>';
                }
                $temp .='</ul></div>';
                $link_data .=$temp;
                return $link_data;
            })
            ->editColumn('status', function($records) {
                $dr_status = array(0 => 'Chưa xem' , 1 => 'Đã phản hồi' , 2 =>'Đã xem' );
                return $dr_status[$records->status];
            })
            ->editColumn('title', function($records) {
                $lmscontent = DB::table('lmscontents')->select('bai')->where('id',$records->lmscontent_id)->first();
                //$dr_status = array(0 => 'Chưa đọc' , 1 => 'Đã phản hồi' , 2 =>'Đã xem' );
                return $records->title .'<br>' .$lmscontent->bai;
            })
            ->removeColumn('id')
            ->removeColumn('lmsseries_id')
            ->removeColumn('lmscontent_id')
            ->removeColumn('lmscombo_id')
            ->rawColumns(['action','title'])
            ->make();
    }
    public function getComments(Request $request){
        try {
            $comment = DB::table('comments')
                ->where([
                    ['id', $request->id],
                    ['parent_id', 0],
                ])
                ->get();
            $comment_child = DB::table('comments')
                ->where([
                    ['parent_id', '=', $request->id],
                ])
                ->get();
            $result = '';
            if (count($comment) > 0) {
                foreach ($comment as $r) {
                    $name = $r->user_name;
                    if ($r->admin_id !== null) {
                        $name = DB::table('users')
                            ->select('name')
                            ->where('id', $r->admin_id)
                            ->first()->name;
                    }
                    $result .= '
                                <div class="media mt-0 p-5 ">
                                        <div class="media-body">
                                            <h4 class="mt-0 ont-weight-bold">
                                                ' . $name . '
                                                <span class="fs-14 ml-0" data-toggle="tooltip" data-placement="top" title="" data-original-title="verified"><i class="fa fa-check-circle-o text-success"></i></span>
                                            </h4>
                                            <small class="text-muted">
                                                <i class="fa fa-calendar"></i>
                                                ' . date_format(date_create($r->created_at), "d-m-Y H:m:i") . '
                                            </small>
                                            <p class="font-13">
                                               ' . $r->body . '
                                            </p>  
                                            ';
                    if (count($comment_child) > 0) {
                        foreach ($comment_child as $cr) {
                            if ($cr->parent_id == $r->id) {
                                $cname = $cr->user_name;
                                if ($cr->admin_id !== null) {
                                    $cname = DB::table('users')
                                        ->select('name')
                                        ->where('id', $cr->admin_id)
                                        ->first()->name;
                                }
                                $result .= '<div class="border-top">
                                                        <div class="media-body"  style="padding-left: 30px">
                                                            <h4 class="mt-0 font-weight-bold">' . $cname . ' <span class="fs-14 ml-0" data-toggle="tooltip" data-placement="top" title="" data-original-title="verified"><i class="fa fa-check-circle-o text-success"></i></span></h4>
                                                            <small class="text-muted">
                                                                <i class="fa fa-calendar"></i> ' . date_format(date_create($r->created_at), "d-m-Y H:m:i") . '
                                                            </small>
                                                            <p class="font-13 ">
                                                                ' . $cr->body . '
                                                            </p>
                                                        </div>
                                                    </div>';
                            }
                        }
                    }
                    $result .= '</div>
                                </div>
                ';
                }
            }

            $data = array('error' => 1, 'message' => $result);
            return json_encode($data);
        }catch(Exception $e){
            DB::rollBack();
            // dd($e);
            $data = array('error'=>2, 'message'=>'');
            //$data = array('error'=>2, 'message'=>$e);
            return json_encode($data);
        }
    }
    public function reply(Request $request){
        $rules = ['body'  =>  'required',];
        $this->validate($request, $rules);
        $record = new Comment();
        try {
            DB::beginTransaction();
            $comments = DB::table('comments')
                ->where('id' ,$request->parent_id)
                ->first();
            $record->user_id                  =  $comments->user_id;
            $record->user_name                =  $comments->user_name;
            $record->body                     =  $request->body;
            $record->lmsseries_id             =  $comments->lmsseries_id;
            $record->lmscombo_id              =  $comments->lmscombo_id;
            $record->lmscontent_id            =  $comments->lmscontent_id;
            $record->parent_id                =  $request->parent_id;
            $record->admin_id                 =  $request->user_id;
            $record->save();
            if ($request->parent_id != 0){
                DB::table('comments')
                    ->where('id', $request->parent_id)
                    ->update(
                        [
                            'status' => 1,
                            'updated_at' =>date("Y-m-d H:i:s"),
                        ]
                    );
            }
            DB::commit();
            $data = array('error'=>1,'message' =>'Trả lời câu hỏi thành công');
            //$data = array('error'=>1,'message' =>$record);
            return json_encode($data);
        }catch(Exception $e){
            DB::rollBack();
            // dd($e);
            $data = array('error'=>2, 'message'=>'Trả lời câu hỏi thất bại');
            //$data = array('error'=>2, 'message'=>$e);
            return json_encode($data);
        }
    }
}
