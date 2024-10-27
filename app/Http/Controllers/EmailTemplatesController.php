<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\EmailTemplate;
use Yajra\DataTables\DataTables;
use DB;
use Auth;
use App\Console\Commands\everyMinute;

class EmailTemplatesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
     /**
     * Course listing method
     * @return Illuminate\Database\Eloquent\Collection
     */
    //Spam email to users
	public function thongbaothithu(Request $request)
    {   
        if (!checkRole(getUserGrade(2))) {
            prepareBlockUserMessage();
            return back();
        }
        $everyMinute = new everyMinute();
        $everyMinute->handle();
		flash('success', 'Gửi Email thành công', 'success');    
		return redirect('/exams/exam-series-free');
    }

	public function guimaithithu(Request $request)
    {   
        if (!checkRole(getUserGrade(2))) {
            prepareBlockUserMessage();
            return back();
        }
      $everyMinute = new everyMinute();
      $result = $everyMinute->sendMail($request->email, $request->slug);
	 return json_encode($result);
    }

	public function sendmailadmin(Request $request)
    {   
        if (!checkRole(getUserGrade(2))) {
            prepareBlockUserMessage();
            return back();
        }
      $everyMinute = new everyMinute();
      $result = $everyMinute->sendMailAdmin($request->slug);
	 return json_encode($result);
    }

    public function danhsachthithu(Request $request)
    {   
      if (!checkRole(getUserGrade(2))) {
          prepareBlockUserMessage();
          return back();
      }

        // $data['title'] = getPhrase('email_templates');
        
        $data['active_class']       = 'master_settings';
        $users = DB::table('users')
				->select(['id','name', 'email', 'slug'])
				->where('is_register','=',1)
				->where('sendmail_free','=', (int)$request->slug)
				->orderBy('id','desc')
				->get();
        $data['users'] = $users;

		
		$examFree = DB::table('exam_free')
			->select(['id','name', 'start_date'])
			->where('id','=', (int)$request->slug)
			->first();
		
		$data['title'] = 'Gửi mail thi thử';
		$data['titles'] = 'Gửi mail thi thử';
		$data['dateTime'] = '';
		$data['examFree'] = $examFree;
		if($examFree != null) {
			$data['titles'] = 'Gửi mail thi thử - ' .$examFree->name;
			$date = date_create($examFree->start_date);
			$dateTime = date_sub($date,date_interval_create_from_date_string("1 days"));
			$data['dateTime'] = date_format($dateTime, 'd/m/Y 00:00:00') ;
		}
        $data['slug'] = $request->slug;
        $view_name = 'admin.emails.templates.users';
        return view($view_name, $data);
    }

    public function getUserEmailList(Request $request)
    {   
      if (!checkRole(getUserGrade(2))) {
          prepareBlockUserMessage();
          return back();
      }

      $records = DB::table('users')
				->select(['id','name', 'email'])
				->where('is_register','=',1)
				->where('sendmail_free','=', $request->slug)
				->orderBy('id','desc')
				->get();

        return DataTables::of($records)
        ->make();
    }

    public function index()
    {
        if(!checkRole(getUserGrade(2)))
      {
        prepareBlockUserMessage();
        return back();
      }

      // echo "<pre>";
      //   print_r ($_SERVER);
      //   echo "</pre>"; exit;

        
        $data['active_class']       = 'master_settings';
        $data['title']              = getPhrase('email_templates');
        // return view('emails.templates.list', $data);
         $view_name = 'admin.emails.templates.list';
        return view($view_name, $data);
    }
    /**
     * This method returns the datatables data to view
     * @return [type] [description]
     */
    public function getDatatable()
    {
        if(!checkRole(getUserGrade(2)))
      {
        prepareBlockUserMessage();
        return back();
      }
         $records = EmailTemplate::select([   
            'title', 'subject', 'type', 'from_email', 'from_name', 'id','slug'])
         ->orderBy('updated_at', 'DESC');
        return DataTables::of($records)
        ->addColumn('action', function ($records) {
            return '<div class="dropdown more">
                        <a id="dLabel" type="button" class="more-dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="mdi mdi-dots-vertical"></i>
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="dLabel">
                            <li><a href="'.URL_EMAIL_TEMPLATES_EDIT.'/'.$records->slug.'"><i class="fa fa-pencil"></i>'.getPhrase("edit").'</a></li>
                        </ul>
                    </div>';
            })
        ->removeColumn('id')
        ->removeColumn('slug')
        ->rawColumns(['action'])
        ->make();
    }
    /**
     * This method loads the create view
     * @return void
     */
    public function create()
    {
        if(!checkRole(getUserGrade(2)))
      {
        prepareBlockUserMessage();
        return back();
      }
        $data['record']             = FALSE;
        $data['active_class']       = 'master_settings';
        $data['title']              = getPhrase('create_template');
        // return view('emails.templates.add-edit', $data);
         $view_name = 'admin.emails.templates.add-edit';
        return view($view_name, $data);
    }
    /**
     * This method loads the edit view based on unique slug provided by user
     * @param  [string] $slug [unique slug of the record]
     * @return [view with record]       
     */
    public function edit($slug)
    {
        if(!checkRole(getUserGrade(2)))
      {
        prepareBlockUserMessage();
        return back();
      }
        $record = EmailTemplate::getRecordWithSlug($slug);
        if($isValid = $this->isValidRecord($record))
            return redirect($isValid);
        $data['record']             = $record;
        $data['active_class']       = 'master_settings';
        $data['title']              = getPhrase('edit_template');
        // return view('emails.templates.add-edit', $data);
           $view_name = 'admin.emails.templates.add-edit';
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
        if(!checkRole(getUserGrade(2)))
      {
        prepareBlockUserMessage();
        return back();
      }
        $record = EmailTemplate::getRecordWithSlug($slug);
         $rules = [
         'title'               => 'bail|required|max:130' ,
         'subject'             => 'bail|required|max:130' ,
         'from_email'          => 'bail|email|required|max:130' ,
         'from_name'           => 'bail|required|max:130' ,
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
        $record->title              = $name;
        $record->content            = $request->content;
        $record->subject            = $request->subject;
        $record->from_email         = $request->from_email;
        $record->from_name          = $request->from_name;
        $record->record_updated_by  = Auth::user()->id;
        $record->save();
        flash('success','record_updated_successfully', 'success');
        return redirect(URL_EMAIL_TEMPLATES);
    }
    /**
     * This method adds record to DB
     * @param  Request $request [Request Object]
     * @return void
     */
    public function store(Request $request)
    {
        if(!checkRole(getUserGrade(2)))
      {
        prepareBlockUserMessage();
        return back();
      }
        $rules = [
         'title'               => 'bail|required|max:130' ,
         'subject'             => 'bail|required|max:130' ,
         'from_email'          => 'bail|email|required|max:130' ,
         'from_name'           => 'bail|required|max:130' ,
         'content'             => 'bail|required' ,
            ];
        $this->validate($request, $rules);
        $record = new EmailTemplate();
        $name                       =  $request->title;
        $record->title              = $name;
        $record->slug               = createSlug($name);
        $record->content            = $request->content;
        $record->type               = $request->type;
        $record->subject            = $request->subject;
        $record->from_email         = $request->from_email;
        $record->from_name          = $request->from_name;
        $record->record_updated_by  = Auth::user()->id;
        $record->save();
        flash('success','record_added_successfully', 'success');
        return redirect(URL_EMAIL_TEMPLATES);
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
        return URL_EMAIL_TEMPLATES;
    }
}
