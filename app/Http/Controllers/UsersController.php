<?php
namespace App\Http\Controllers;
use App\GeneralSettings as Settings;
use App\PointRule;
use App\User;
use DB;
use Excel;
use Exception;
use File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Image;
use ImageSettings;
use App\PaymentMethod;
use Input;
use Yajra\DataTables\DataTables;
use \App;
use App\ClassesUser;
use App\ExamRate;
use App\LmsContent;
use App\LmsSeries;
use App\LmsSeriesCombo;
use App\LmsStudentView;
use App\Payment;
use App\QuizResultfinish;
use App\Role;
use App\Services\LmsSeriesComboService;
use App\Services\LmsSeriesService;
use App\Services\UserService;
use App\WeeklyLeaderboard;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class UsersController extends Controller
{
    public $excel_data = array();
    private $userService;
    private $lmsSeriesService;

    public function __construct(
        UserService $userService,
        LmsSeriesComboService $lmsSeriesComboService,
        LmsSeriesService $lmsSeriesService
    ) {
        $currentUser = Auth::user();
        $this->userService = $userService;
        $this->lmsSeriesComboService = $lmsSeriesComboService;
        $this->lmsSeriesService = $lmsSeriesService;

        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index($role = 'student')
    {
        if (!checkRole(getUserGrade(2))) {
            prepareBlockUserMessage();
            return back();
        }
        $data['records'] = false;
        $data['layout'] = getLayout();
        $data['active_class'] = 'users';
        $data['heading'] = getPhrase('users');
        $data['title'] = getPhrase('users');
        $view_name = 'admin.users.list-users';
        return view($view_name, $data);
    }
    public function register($role = 'student')
    {
        // if(!checkRole(getUserGrade(2)))
        // {
        //   prepareBlockUserMessage();
        //   return back();
        // }
        $data['records'] = false;
        $data['layout'] = getLayout();
        $data['active_class'] = 'users';
        $data['heading'] = getPhrase('users');
        $data['title'] = getPhrase('users');
        // return view('users.list-users', $data);
        $view_name = 'admin.users.list-users-register';
        return view($view_name, $data);
    }
    public function registerjp($role = 'student')
    {
        if (!checkRole(getUserGrade(2))) {
            prepareBlockUserMessage();
            return back();
        }
        $data['records'] = false;
        $data['layout'] = getLayout();
        $data['active_class'] = 'users';
        $data['heading'] = getPhrase('users');
        $data['title'] = 'Học viên đăng ký tại Nhật';
        $view_name = 'admin.users.list-users-registerjp';
        return view($view_name, $data);
    }
    public function getregisterjpDatatable($slug = '')
    {
        $records = array();
        if ($slug == '') {
            $records = User::select([
                'users.name',
                'email',
                'country',
                'state',
                'city',
                'users.created_at',
                'login_enabled',
                'role_id',
                'slug',
                'users.id',
                'users.updated_at'
            ])
                ->where('is_register', '=', 1)
                ->where('country_code', '=', 'JP')
                ->orderBy('users.state', 'desc');
        } else {
            $role = App\Role::getRoleId($slug);
            $records = User::join('roles', 'users.role_id', '=', 'roles.id', 'roles.id', '=', $role->id)
                ->select(['users.name', 'email', 'country', 'state', 'city', 'users.created_at', 'login_enabled', 'role_id', 'slug', 'users.updated_at'])
                ->orderBy('users.updated_at', 'desc');
        }
        return DataTables::of($records)
            ->addColumn('action', function ($records) {
                $link_data = '<div class="dropdown more">
                         <a id="dLabel" type="button" class="more-dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                             <i class="mdi mdi-dots-vertical"></i>
                         </a>
                         <ul class="dropdown-menu" aria-labelledby="dLabel">
                            <li><a href="' . URL_USERS_EDIT . $records->slug . '"><i class="fa fa-pencil"></i>' . getPhrase("edit") . '</a></li>';
                $temp = '';
                //Show delete option to only the owner user
                if (checkRole(getUserGrade(1)) && $records->id != \Auth::user()->id) {
                    $temp = '<li><a href="javascript:void(0);" onclick="deleteRecord(\'' . $records->slug . '\');"><i class="fa fa-trash"></i>' . getPhrase("delete") . '</a></li>';
                }
                $temp .= '</ul> </div>';
                $link_data .= $temp;
                return $link_data;
            })
            ->editColumn('name', function ($records) {
                //return '<a href="'.URL_USER_DETAILS.$records->slug.'">'.ucfirst($records->name).'</a>';
                return ucwords($records->name);
            })
            ->removeColumn('login_enabled')
            ->removeColumn('role_id')
            ->removeColumn('id')
            ->removeColumn('slug')
            ->removeColumn('updated_at')
            ->rawColumns(['action'])
            // ->addAction('action',['printable' => false])
            ->make();
    }
    public function getregisterDatatable($slug = '')
    {
        $records = array();
        if ($slug == '') {
            $records = User::join('roles', 'users.role_id', '=', 'roles.id')
                ->select([
                    'email',
                    'users.name',
                    'login_enabled',
                    'role_id',
                    'slug',
                    'users.id',
                    'users.updated_at'
                ])
                ->where('is_register', '=', 1)
                ->orderBy('users.created_at', 'desc');
        } else {
            $role = App\Role::getRoleId($slug);
            $records = User::join('roles', 'users.role_id', '=', 'roles.id', 'roles.id', '=', $role->id)
                ->select(['email', 'name', 'roles.display_name', 'login_enabled', 'role_id', 'slug', 'users.updated_at'])
                ->orderBy('users.updated_at', 'desc');
        }
        return DataTables::of($records)
            ->addColumn('action', function ($records) {
                $link_data = '<div class="dropdown more">
                         <a id="dLabel" type="button" class="more-dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                             <i class="mdi mdi-dots-vertical"></i>
                         </a>
                         <ul class="dropdown-menu" aria-labelledby="dLabel">
                            <li><a href="' . URL_USERS_EDIT . $records->slug . '"><i class="fa fa-pencil"></i>' . getPhrase("edit") . '</a></li>';
                $temp = '';
                //Show delete option to only the owner user
                if (checkRole(getUserGrade(1)) && $records->id != \Auth::user()->id) {
                    $temp = '<li><a href="javascript:void(0);" onclick="deleteRecord(\'' . $records->slug . '\');"><i class="fa fa-trash"></i>' . getPhrase("delete") . '</a></li>';
                }
                $temp .= '</ul> </div>';
                $link_data .= $temp;
                return $link_data;
            })
            ->editColumn('name', function ($records) {
                //return '<a href="'.URL_USER_DETAILS.$records->slug.'">'.ucfirst($records->name).'</a>';
                return ucwords($records->name);
            })
            ->removeColumn('login_enabled')
            ->removeColumn('role_id')
            ->removeColumn('id')
            ->removeColumn('slug')
            ->removeColumn('updated_at')
            ->rawColumns(['action'])
            // ->addAction('action',['printable' => false])
            ->make();
    }
    /**
     * This method returns the datatables data to view
     * @return [type] [description]
     */
    public function getDatatable($slug = '')
    {
        $records = array();
        if ($slug == '') {
            $records = User::join('roles', 'users.role_id', '=', 'roles.id')
                ->select([
                    'users.hid',
                    'users.name',
                    'email',
                    'image',
                    'roles.display_name',
                    'login_enabled',
                    'role_id',
                    'slug',
                    'users.id',
                    'users.updated_at'
                ])
                ->orderBy('users.created_at', 'desc');
        } else {
            $role = App\Role::getRoleId($slug);
            $records = User::join('roles', 'users.role_id', '=', 'roles.id', 'roles.id', '=', $role->id)
                ->select(['name', 'image', 'email', 'roles.display_name', 'login_enabled', 'role_id', 'slug', 'users.updated_at'])
                ->orderBy('users.created_at', 'desc');
        }
        return DataTables::of($records)
            ->addColumn('action', function ($records) {
                $link_data = '<div class="dropdown more">
                        <a id="dLabel" type="button" class="more-dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="mdi mdi-dots-vertical"></i>
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="dLabel">
                        <li><a href="' . URL_USERS_EDIT . $records->slug . '"><i class="fa fa-pencil"></i>' . getPhrase("edit") . '</a></li>';
                // Add link Xem kết quả thi
                $linkResult = "user/exam-categories/result/" . $records->id;
                $link_data .= ' <li><a href="' . $linkResult . '"><i class="fa fa-certificate"></i>Xem kết quả thi</a></li>';
                if (getRoleData($records->role_id) == 'student') {
                    $link_data .= ' <li><a href="' . URL_USERS_UPDATE_PARENT_DETAILS . $records->slug . '"><i class="fa fa-user"></i>Cập nhật lớp</a></li>';
                }
                // Add link xem khoa hoc - khoa luyen thi
                $link = "/user/exam-categories/resource/" . $records->id;
                $link_data .= ' <li><a href="' . $link . '"><i class="fa fa-book"></i>Xem KH-KLT</a></li>';
                $temp = '';
                //Show delete option to only the owner user
                if (checkRole(getUserGrade(1)) && $records->id != \Auth::user()->id) {
                    $temp = '<li><a href="javascript:void(0);" onclick="deleteRecord(\'' . $records->slug . '\');"><i class="fa fa-trash"></i>' . getPhrase("delete") . '</a></li>';
                }
                $temp .= '</ul> </div>';
                $link_data .= $temp;
                return $link_data;
            })
            ->editColumn('name', function ($records) {
                $name = ucfirst($records->name);
                if (getRoleData($records->role_id) == 'student') {
                    $name = '<a href="' . URL_USER_DETAILS . $records->slug . '">' . $name . '</a>';
                }
                return $name;
            })
            ->editColumn('image', function ($records) {
                return '<img src="' . getProfilePath($records->image) . '"  />';
            })
            ->removeColumn('login_enabled')
            ->removeColumn('role_id')
            ->removeColumn('id')
            ->removeColumn('slug')
            ->removeColumn('updated_at')
            ->rawColumns(['action', 'name', 'image'])
            ->make(true);
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        // $last_uid = DB::table('users')
        //       ->whereYear('created_at','=',date('Y'))
        //       ->where('hid','<>',null)
        //       ->orderBy('created_at', 'desc')
        //       ->first();
        //     dd($last_uid);
        // $user           = new User();
        // $slug->name     = $user->makeSlug('tim anh');
        // $slug->email     = $user->makeSlug('tim anh');
        // $user->save();
        // dd($user);
        if (!checkRole(getUserGrade(4))) {
            prepareBlockUserMessage();
            return back();
        }
        $data['record'] = false;
        $data['active_class'] = 'users';
        // $data['roles']        = $this->getUserRoles();
        $roles = \App\Role::select('display_name', 'id', 'name')->get();
        $final_roles = [];
        foreach ($roles as $role) {
            if (!checkRole(getUserGrade(1))) {
                if (!(strtolower($role->name) == 'admin' || strtolower($role->name) == 'owner')) {
                    $final_roles[$role->id] = $role->display_name;
                }
            } else {
                $final_roles[$role->id] = $role->display_name;
            }
        }
        $data['roles'] = $final_roles;
        $data['title'] = 'Thêm thành viên';
        $data['layout'] = getLayout();
        $view_name = 'admin.users.add-edit-user';
        return view($view_name, $data);
    }
    /**
     * This method returns the roles based on the user type logged in
     * @param  [type] $roles [description]
     * @return [type]        [description]
     */
    public function getUserRoles()
    {
        $roles = \App\Role::pluck('display_name', 'id');
        return array_where($roles, function ($key, $value) {
            if (!checkRole(getUserGrade(1))) {
                if (!($value == 'Admin' || $value == 'Owner')) {
                    return $value;
                }
            } else {
                return $value;
            }
        });
    }
    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(Request $request)
    {
        //dd($request);
        $columns = array(
            'name' => 'bail|required|max:200|',
            'email' => 'bail|required|unique:users,email',
            'password' => 'bail|required|min:6',
            'password_confirmation' => 'bail|required|min:6|same:password',
        );
        if ($request->role_id != 5) {
            $columns['username'] = 'bail|required|unique:users,username';
        }
        $this->validate($request, $columns);
        $role_id = getRoleData('student');
        if ($request->role_id) {
            $role_id = $request->role_id;
        }
        $user = new User();
        $name = $request->name;
        $user->name = $name;
        $user->email = $request->email;
        $password = $request->password;
        $user->password = bcrypt($password);
        $user->role_id = $role_id;
        $user->login_enabled = 1;
        $user->username = $request->username;
        $slug = createSlug($name);
        $user->slug = $slug;
        $user->phone = $request->phone;
        $user->address = '';
        if ($role_id == 5) {
            $last_uid = DB::table('users')
                ->whereYear('created_at', '=', date('Y'))
                ->where('uid', '<>', null)
                ->orderBy('created_at', 'desc')
                ->first();
            if ($last_uid) {
                $uid_code = $last_uid->uid;
                $uid_code = ++$uid_code;
                $uid_code = str_pad($uid_code, 5, '0', STR_PAD_LEFT);
                $user->uid = '' . $uid_code . '';
                $uid_code = 'HID' . date('y') . $uid_code;
            } else {
                $user->uid = '00001';
                $uid_code = 'HID' . date('y') . '00001';
            }
            $user->hid = $uid_code;
            $user->username = $uid_code;
        }
        // $uid  = new Uid();
        // $uid->uid  = $uid_code;
        // $uid->uid_u     = $request->uid_u;
        // $uid->uid_v     = $request->uid_v;
        // $uid->uid_email     = $request->uid_email;
        //       //$uid->uid_user_created  = $user->id;
        // $uid->save();
        // DB::commit();
        $user->save();
        $user->roles()->attach($user->role_id);
        $message = 'Tạo thành viên thành công';
        $exception = 0;
        $flash = app('App\Http\Flash');
        $flash->create($message, '', 'success', 'flash_overlay', false);
        return redirect(URL_USERS);
    }
    public function sendPushNotification($user)
    {
        if (getSetting('push_notifications', 'module')) {
            if (getSetting('default', 'push_notifications') == 'pusher') {
                $options = array(
                    'name' => $user->name,
                    'image' => getProfilePath($user->image),
                    'slug' => $user->slug,
                    'role' => getRoleData($user->role_id),
                );
                pushNotification(['owner', 'admin'], 'newUser', $options);
            } else {
                $this->sendOneSignalMessage('New Registration');
            }
        }
    }
    /**
     * This method sends the message to admin via One Signal
     * @param  string $message [description]
     * @return [type]          [description]
     */
    public function sendOneSignalMessage($new_message = '')
    {
        $gcpm = new OneSignalApp();
        $message = array(
            "en" => $new_message,
            "title" => 'New Registration',
            "icon" => "myicon",
            "sound" => "default",
        );
        $data = array(
            "body" => $new_message,
            "title" => "New Registration",
        );
        $gcpm->setDevices(env('ONE_SIGNAL_USER_ID'));
        $response = $gcpm->sendToAll($message, $data);
    }
    protected function processUpload(Request $request, User $user)
    {
        if (env('DEMO_MODE')) {
            return 'demo';
        }
        if ($request->hasFile('image')) {
            $imageObject = new ImageSettings();
            $destinationPath = $imageObject->getProfilePicsPath();
            $destinationPathThumb = $imageObject->getProfilePicsThumbnailpath();
            $fileName = $user->id . '.' . $request->image->guessClientExtension();
            $request->file('image')->move($destinationPath, $fileName);
            $user->image = $fileName;
            Image::make($destinationPath . $fileName)->fit($imageObject->getProfilePicSize())->save($destinationPath . $fileName);
            Image::make($destinationPath . $fileName)->fit($imageObject->getThumbnailSize())->save($destinationPathThumb . $fileName);
            $user->save();
        }
    }
    public function isValidRecord($record)
    {
        if ($record === null) {
            flash('Ooops...!', getPhrase("page_not_found"), 'error');
            return $this->getRedirectUrl();
        }
        return false;
    }
    public function getReturnUrl()
    {
        return URL_USERS;
    }
    /**
     * Display the specified resource.
     *
     *@param  unique string  $slug
     * @return Response
     */
    public function show($slug)
    {
        //
    }
    public function profile($slug)
    {
        $record = User::where('slug', $slug)->get()->first();
        if ($isValid = $this->isValidRecord($record)) {
            return redirect($isValid);
        }
        if (!isEligible($slug)) {
            return back();
        }
        $UserOwnAccount = false;
        $data['record'] = $record;
        $data['active_class'] = 'users_edit';
        $data['title'] = 'Trang cá nhân';
        // $data['layout']       = getLayout();
        $data['layout'] = 'admin.layouts.student.studentsettinglayout';
        $view_name = 'admin.users.profile';
        return view($view_name, $data);
    }
    public function updateProfile(Request $request, $slug)
    {
        $record = User::where('slug', $slug)->get()->first();
        $validation = [
            'name' => 'bail|required|max:20|',
            'image' => 'bail|mimes:png,jpg,jpeg|max:2048',
        ];
        if (!isEligible($slug)) {
            return back();
        }

        $this->validate($request, $validation);
        $name = $request->name;
        $record->name = $name;
        $record->phone = $request->phone;
        $record->level = 5;
        $record->address = $request->address;
        $record->save();

        if (!env('DEMO_MODE')) {
            $this->processUpload($request, $record);
        }
        flash('Cập nhật thành công', '', 'success');
        return redirect('/users/profile/' . $record->slug);
    }


    public function edit($slug)
    {
        $record = User::where('slug', $slug)->get()->first();
        if ($isValid = $this->isValidRecord($record)) {
            return redirect($isValid);
        }
        /**
         * Validate the non-admin user wether is trying to access other user profile
         * If so return the user back to previous page with message
         */
        if (!isEligible($slug)) {
            return back();
        }
        /**
         * Make sure the Admin or staff cannot edit the Admin/Owner accounts
         * Only Owner can edit the Admin/Owner profiles
         * Admin can edit his own account, in that case send role type admin on condition
         */
        $UserOwnAccount = false;
        if (\Auth::user()->id == $record->id) {
            $UserOwnAccount = true;
        }
        if (!$UserOwnAccount) {
            $current_user_role = getRoleData($record->role_id);
            if ((($current_user_role == 'admin' || $current_user_role == 'owner'))) {
                if (!checkRole(getUserGrade(1))) {
                    prepareBlockUserMessage();
                    return back();
                }
            }
        }
        $data['record'] = $record;
        // dd('hrere');
        // $data['roles']              = $this->getUserRoles();
        $roles = \App\Role::select('display_name', 'id', 'name')->get();
        $final_roles = [];
        foreach ($roles as $role) {
            if (!checkRole(getUserGrade(1))) {
                if (!(strtolower($role->name) == 'admin' || strtolower($role->name) == 'owner')) {
                    $final_roles[$role->id] = $role->display_name;
                }
            } else {
                $final_roles[$role->id] = $role->display_name;
            }
        }
        $data['roles'] = $final_roles;
        if ($UserOwnAccount && checkRole(['admin'])) {
            $data['roles'][getRoleData('admin')] = 'Admin';
        }
        $data['active_class'] = 'users_edit';
        $data['title'] = 'Trang cá nhân';
        $data['layout'] = getLayout();
        //$data['layout']       = 'admin.layouts.student.studentsettinglayout';
        $view_name = 'admin.users.add-edit-user';
        return view($view_name, $data);
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, $slug)
    {
        $record = User::where('slug', $slug)->get()->first();
        $validation = [
            'name' => 'bail|required|max:20|',
            'email' => 'bail|required|unique:users,email,' . $record->id,
            'image' => 'bail|mimes:png,jpg,jpeg|max:2048',
        ];
        if (!isEligible($slug)) {
            return back();
        }
        if (checkRole(getUserGrade(2))) {
            $validation['role_id'] = 'bail|required|integer';
        }
        $this->validate($request, $validation);
        $name = $request->name;
        $previous_role_id = $record->role_id;
        if ($name != $record->name) {
            $record->slug = createSlug($name);
        }
        $record->name = $name;
        $record->email = $request->email;
        if (checkRole(getUserGrade(2))) {
            $record->role_id = $request->role_id;
        }
        $record->phone = $request->phone;
        $record->level = 5;
        $record->address = '';
        $record->save();
        if ($request->password) {
            $password = $request->password;
            $record->password = bcrypt($password);
            $record->save();
        }
        DB::table('role_user')
            ->where('user_id', '=', $record->id)
            ->where('role_id', '=', $previous_role_id)
            ->delete();
        $record->roles()->attach($request->role_id);
        flash('Cập nhật thành công', '', 'success');
        if (checkRole(getUserGrade(2))) {
            return redirect(URL_USERS);
        }
        return redirect(URL_USERS_EDIT . $record->slug);
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  unique string  $slug
     * @return Response
     */
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
        $record = User::where('slug', $slug)->first();
        /**
         * Check if any exams exists with this category,
         * If exists we cannot delete the record
         */
        if (!env('DEMO_MODE')) {
            $imageObject = new ImageSettings();
            $destinationPath = $imageObject->getProfilePicsPath();
            $destinationPathThumb = $imageObject->getProfilePicsThumbnailpath();
            $this->deleteFile($record->image, $destinationPath);
            $this->deleteFile($record->image, $destinationPathThumb);
            $record->delete();
        }
        $response['status'] = 1;
        $response['message'] = getPhrase('record_deleted_successfully');
        return json_encode($response);
    }
    public function deleteFile($record, $path, $is_array = false)
    {
        if (env('DEMO_MODE')) {
            return;
        }
        $files = array();
        $files[] = $path . $record;
        File::delete($files);
    }
    public function listUsers($role_name)
    {
        $role = App\Role::getRoleId($role_name);
        $users = User::where('role_id', '=', $role->id)->get();
        $users_list = array();
        foreach ($users as $key => $value) {
            $r = array('id' => $value->id, 'text' => $value->name, 'image' => $value->image);
            array_push($users_list, $r);
        }
        return json_encode($users_list);
    }
    public function details($slug)
    {
        $record = User::where('slug', $slug)->get()->first();
        if ($isValid = $this->isValidRecord($record)) {
            return redirect($isValid);
        }
        /**
         * Validate the non-admin user wether is trying to access other user profile
         * If so return the user back to previous page with message
         */
        if (!isEligible($slug)) {
            return back();
        }
        $data['record'] = $record;
        $user = $record;
        //Overall performance Report
        $resultObject = new App\QuizResult();
        $records = $resultObject->getOverallSubjectsReport($user);
        $color_correct = getColor('background', rand(0, 999));
        $color_wrong = getColor('background', rand(0, 999));
        $color_not_attempted = getColor('background', rand(0, 999));
        $correct_answers = 0;
        $wrong_answers = 0;
        $not_answered = 0;
        foreach ($records as $record) {
            $record = (object) $record;
            $correct_answers += $record->correct_answers;
            $wrong_answers += $record->wrong_answers;
            $not_answered += $record->not_answered;
        }
        $labels = [getPhrase('correct'), getPhrase('wrong'), getPhrase('not_answered')];
        $dataset = [$correct_answers, $wrong_answers, $not_answered];
        $dataset_label[] = 'lbl';
        $bgcolor = [$color_correct, $color_wrong, $color_not_attempted];
        $border_color = [$color_correct, $color_wrong, $color_not_attempted];
        $chart_data['type'] = 'pie';
        //horizontalBar, bar, polarArea, line, doughnut, pie
        $chart_data['title'] = getphrase('overall_performance');
        $chart_data['data'] = (object) array(
            'labels' => $labels,
            'dataset' => $dataset,
            'dataset_label' => $dataset_label,
            'bgcolor' => $bgcolor,
            'border_color' => $border_color,
        );
        $data['chart_data'][] = (object) $chart_data;
        //Best scores in each quizzes
        $records = $resultObject->getOverallQuizPerformance($user);
        $labels = [];
        $dataset = [];
        $bgcolor = [];
        $bordercolor = [];
        foreach ($records as $record) {
            $color_number = rand(0, 999);
            $record = (object) $record;
            $labels[] = $record->title;
            $dataset[] = $record->percentage;
            $bgcolor[] = getColor('background', $color_number);
            $bordercolor[] = getColor('border', $color_number);
        }
        $labels = $labels;
        $dataset = $dataset;
        $dataset_label = getPhrase('performance');
        $bgcolor = $bgcolor;
        $border_color = $bordercolor;
        $chart_data['type'] = 'bar';
        //horizontalBar, bar, polarArea, line, doughnut, pie
        $chart_data['title'] = getPhrase('best_performance_in_all_quizzes');
        $chart_data['data'] = (object) array(
            'labels' => $labels,
            'dataset' => $dataset,
            'dataset_label' => $dataset_label,
            'bgcolor' => $bgcolor,
            'border_color' => $border_color,
        );
        $data['chart_data'][] = (object) $chart_data;
        $data['ids'] = array('myChart0', 'myChart1');
        $data['title'] = getPhrase('user_details');
        $data['layout'] = getLayout();
        $data['active_class'] = 'users';
        if (checkRole(['parent'])) {
            $data['active_class'] = 'children';
        }
        //   $data['right_bar']          = TRUE;
        // $data['right_bar_path']     = 'student.exams.right-bar-performance-chart';
        // $data['right_bar_data']     = array('chart_data' => $data['chart_data']);
        // return view('users.user-details', $data);
        $view_name = 'admin.users.user-details';
        return view($view_name, $data);
    }
    /**
     * This method will show the page for change password for user
     * @param  [type] $slug [description]
     * @return [type]       [description]
     */
    public function changePassword($slug)
    {
        $record = User::where('slug', $slug)->get()->first();
        if ($isValid = $this->isValidRecord($record)) {
            return redirect($isValid);
        }
        /**
         * Validate the non-admin user wether is trying to access other user profile
         * If so return the user back to previous page with message
         */
        if (!isEligible($slug)) {
            return back();
        }
        $data['record'] = $record;
        $data['active_class'] = 'profile';
        $data['title'] = getPhrase('change_password');
        $data['layout'] = getLayout();
        // return view('users.change-password.change-view', $data);
        $view_name = 'admin.users.change-password.change-view';
        return view($view_name, $data);
    }
    /**
     * This method updates the password submitted by the user
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function updatePassword(Request $request)
    {
        $this->validate($request, [
            'old_password' => 'required',
            'password' => 'required|confirmed',
        ]);
        $credentials = $request->only(
            'old_password',
            'password',
            'password_confirmation'
        );
        $user = \Auth::user();
        if (Hash::check($credentials['old_password'], $user->password)) {
            $password = $credentials['password'];
            $user->password = bcrypt($password);
            $user->save();
            flash('success', 'Thay đổi password thành công!', 'success');
            return redirect(URL_USERS_CHANGE_PASSWORD . $user->slug);
        } else {
            flash('error', 'Password cũ không chính xác!', 'error');
            return redirect()->back();
        }
    }
    /**
     * Display a Import Users page
     *
     * @return Response
     */
    public function importUsers($role = 'student')
    {
        if (!checkRole(getUserGrade(2))) {
            prepareBlockUserMessage();
            return back();
        }
        $data['records'] = false;
        $data['active_class'] = 'users';
        $data['heading'] = getPhrase('users');
        $data['title'] = getPhrase('import_users');
        $data['layout'] = getLayout();
        // return view('users.import.import', $data);
        $view_name = 'admin.users.import.import';
        return view($view_name, $data);
    }
    public function readExcel(Request $request)
    {
        $columns = array(
            'excel' => 'bail|required',
        );
        $this->validate($request, $columns);
        if (!checkRole(getUserGrade(2))) {
            prepareBlockUserMessage();
            return back();
        }
        $success_list = [];
        $failed_list = [];
        try {
            if (Input::hasFile('excel')) {
                $path = Input::file('excel')->getRealPath();
                $data = Excel::load($path, function ($reader) {
                })->get();
                $user_record = array();
                $users = array();
                $isHavingDuplicate = 0;
                if (!empty($data) && $data->count()) {
                    foreach ($data as $key => $value) {
                        foreach ($value as $record) {
                            unset($user_record);
                            $user_record['username'] = $record->username;
                            $user_record['name'] = $record->name;
                            $user_record['email'] = $record->email;
                            $user_record['password'] = $record->password;
                            $user_record['phone'] = $record->phone;
                            // $user_record['address'] = $record->address;
                            $user_record['address'] = '';
                            $user_record['role_id'] = STUDENT_ROLE_ID;
                            $user_record = (object) $user_record;
                            $failed_length = count($failed_list);
                            if ($this->isRecordExists($record->username, 'username')) {
                                $isHavingDuplicate = 1;
                                $temp = array();
                                $temp['record'] = $user_record;
                                $temp['type'] = 'Record already exists with this name';
                                $failed_list[$failed_length] = (object) $temp;
                                continue;
                            }
                            if ($this->isRecordExists($record->email, 'email')) {
                                $isHavingDuplicate = 1;
                                $temp = array();
                                $temp['record'] = $user_record;
                                $temp['type'] = 'Record already exists with this email';
                                $failed_list[$failed_length] = (object) $temp;
                                continue;
                            }
                            $users[] = $user_record;
                        }
                    }
                    if ($this->addUser($users)) {
                        $success_list = $users;
                    }
                }
            }
            $this->excel_data['failed'] = $failed_list;
            $this->excel_data['success'] = $success_list;
            flash('success', 'record_added_successfully', 'success');
            $this->downloadExcel();
        } catch (Exception $e) {
            if (getSetting('show_foreign_key_constraint', 'module')) {
                flash('oops...!', $e->errorInfo, 'error');
            } else {
                flash('oops...!', 'improper_sheet_uploaded', 'error');
            }
            return back();
        }
        // URL_USERS_IMPORT_REPORT
        $data['failed_list'] = $failed_list;
        $data['success_list'] = $success_list;
        $data['records'] = false;
        $data['layout'] = getLayout();
        $data['active_class'] = 'users';
        $data['heading'] = getPhrase('users');
        $data['title'] = getPhrase('report');
        // return view('users.import.import-result', $data);
        $view_name = 'admin.users.import.import-result';
        return view($view_name, $data);
    }
    public function getFailedData()
    {
        return $this->excel_data;
    }
    public function downloadExcel()
    {
        Excel::create('users_report', function ($excel) {
            $excel->sheet('Failed', function ($sheet) {
                $sheet->row(1, array('Reason', 'Name', 'Username', 'Email', 'Password', 'Phone', 'Address'));
                $data = $this->getFailedData();
                $cnt = 2;
                // dd($data['failed']);
                foreach ($data['failed'] as $data_item) {
                    $item = $data_item->record;
                    $sheet->appendRow($cnt++, array($data_item->type, $item->name, $item->username, $item->email, $item->password, $item->phone, $item->address));
                }
            });
            $excel->sheet('Success', function ($sheet) {
                $sheet->row(1, array('Name', 'Username', 'Email', 'Password', 'Phone', 'Address'));
                $data = $this->getFailedData();
                $cnt = 2;
                foreach ($data['success'] as $data_item) {
                    $item = $data_item;
                    $sheet->appendRow($cnt++, array($item->name, $item->username, $item->email, $item->password, $item->phone, $item->address));
                }
            });
        })->download('xlsx');
        return true;
    }
    /**
     * This method verifies if the record exists with the email or user name
     * If Exists it returns true else it returns false
     * @param  [type]  $value [description]
     * @param  string  $type  [description]
     * @return boolean        [description]
     */
    public function isRecordExists($record_value, $type = 'email')
    {
        return User::where($type, '=', $record_value)->get()->count();
    }
    public function addUser($users)
    {
        foreach ($users as $request) {
            $user = new User();
            $name = $request->name;
            $user->name = $name;
            $user->email = $request->email;
            $user->username = $request->username;
            $user->password = bcrypt($request->password);
            $user->role_id = $request->role_id;
            $user->login_enabled = 1;
            $user->slug = createSlug($name);
            $user->phone = $request->phone;
            // $user->address      = $request->address;
            $user->address = '';
            $user->save();
            $user->roles()->attach($user->role_id);
        }
        return true;
    }
    /**
     * This method shows the user preferences based on provided user slug and settings available in table.
     * @param  [type] $slug [description]
     * @return [type]       [description]
     */
    public function settings($slug)
    {
        $record = User::where('slug', $slug)->first();
        if ($isValid = $this->isValidRecord($record)) {
            return redirect($isValid);
        }
        /**
         * Validate the non-admin user wether is trying to access other user profile
         * If so return the user back to previous page with message
         */
        if (!isEligible($slug)) {
            return back();
        }
        /**
         * Make sure the Admin or staff cannot edit the Admin/Owner accounts
         * Only Owner can edit the Admin/Owner profiles
         * Admin can edit his own account, in that case send role type admin on condition
         */
        $UserOwnAccount = false;
        if (\Auth::user()->id == $record->id) {
            $UserOwnAccount = true;
        }
        if (!$UserOwnAccount) {
            $current_user_role = getRoleData($record->role_id);
            if ((($current_user_role == 'admin' || $current_user_role == 'owner'))) {
                if (!checkRole(getUserGrade(1))) {
                    prepareBlockUserMessage();
                    return back();
                }
            }
        }
        $data['record'] = $record;
        $data['quiz_categories'] = App\QuizCategory::get();
        $data['lms_category'] = App\LmsCategory::get();
        // dd($data);
        $data['layout'] = getLayout();
        $data['active_class'] = 'users';
        $data['heading'] = getPhrase('account_settings');
        $data['title'] = getPhrase('account_settings');
        // flash('success','record_added_successfully', 'success');
        // return view('users.account-settings', $data);
        $view_name = 'admin.users.account-settings';
        return view($view_name, $data);
    }
    /**
     * This method updates the user preferences based on the provided categories
     * All these settings will be stored under Users table settings field as json format
     * @param  Request $request [description]
     * @param  [type]  $slug    [description]
     * @return [type]           [description]
     */
    public function updateSettings(Request $request, $slug)
    {
        $record = User::where('slug', $slug)->first();
        if ($isValid = $this->isValidRecord($record)) {
            return redirect($isValid);
        }
        /**
         * Validate the non-admin user wether is trying to access other user profile
         * If so return the user back to previous page with message
         */
        if (!isEligible($slug)) {
            return back();
        }
        /**
         * Make sure the Admin or staff cannot edit the Admin/Owner accounts
         * Only Owner can edit the Admin/Owner profiles
         * Admin can edit his own account, in that case send role type admin on condition
         */
        $UserOwnAccount = false;
        if (\Auth::user()->id == $record->id) {
            $UserOwnAccount = true;
        }
        if (!$UserOwnAccount) {
            $current_user_role = getRoleData($record->role_id);
            if ((($current_user_role == 'admin' || $current_user_role == 'owner'))) {
                if (!checkRole(getUserGrade(1))) {
                    prepareBlockUserMessage();
                    return back();
                }
            }
        }
        $options = [];
        if ($record->settings) {
            $options = (array) json_decode($record->settings)->user_preferences;
        }
        $options['quiz_categories'] = [];
        $options['lms_categories'] = [];
        if ($request->has('quiz_categories')) {
            foreach ($request->quiz_categories as $key => $value) {
                $options['quiz_categories'][] = $key;
            }
        }
        if ($request->has('lms_categories')) {
            foreach ($request->lms_categories as $key => $value) {
                $options['lms_categories'][] = $key;
            }
        }
        $record->settings = json_encode(array('user_preferences' => $options));
        $record->save();
        flash('success', 'record_updated_successfully', 'success');
        return back();
    }
    public function viewParentDetails($slug)
    {
        if (!checkRole(getUserGrade(4))) {
            prepareBlockUserMessage();
            return back();
        }
        $record = User::where('slug', '=', $slug)->first();
        if ($isValid = $this->isValidRecord($record)) {
            return redirect($isValid);
        }
        $data['layout'] = getLayout();
        $data['active_class'] = 'users';
        $data['record'] = $record;
        $data['heading'] = 'Giáo viên';
        $data['title'] = 'Giáo viên';
        // return view('users.parent-details', $data);
        $view_name = 'admin.users.parent-details';
        return view($view_name, $data);
    }
    public function updateParentDetails(Request $request, $slug)
    {
        if (!checkRole(getUserGrade(4))) {
            prepareBlockUserMessage();
            return back();
        }
        $user = User::where('slug', '=', $slug)->first();
        $role_id = getRoleData('parent');
        $message = '';
        $hasError = 0;
        DB::beginTransaction();
        if ($request->account == 0) {
            //User is not having an account, create it and send email
            //Update the newly created user ID to the current user parent record
            $parent_user = new User();
            $parent_user->name = $request->parent_name;
            $parent_user->username = $request->parent_user_name;
            $parent_user->role_id = $role_id;
            $parent_user->slug = createSlug($request->parent_user_name);
            $parent_user->email = $request->parent_email;
            $parent_user->password = bcrypt('password');
            try {
                $parent_user->save();
                $parent_user->roles()->attach($role_id);
                $user->parent_id = $parent_user->id;
                $user->save();
                sendEmail('registration', array('user_name' => $user->name, 'username' => $user->username, 'to_email' => $user->email, 'password' => $parent_user->password));
                DB::commit();
                $message = 'record_updated_successfully';
            } catch (Exception $ex) {
                DB::rollBack();
                $hasError = 1;
                $message = $ex->getMessage();
            }
        }
        if ($request->account == 1) {
            try {
                $user->parent_id = $request->parent_user_id;
                $user->save();
                DB::commit();
            } catch (Exception $ex) {
                $hasError = 1;
                DB::rollBack();
                $message = $ex->getMessage();
            }
        }
        if (!$hasError) {
            flash('success', $message, 'success');
        } else {
            flash('Ooops', $message, 'error');
        }
        return back();
    }
    public function getParentsOnSearch(Request $request)
    {
        $term = $request->search_text;
        $role_id = getRoleData('parent');
        $records = App\User::
            where('name', 'LIKE', '%' . $term . '%')
            ->orWhere('username', 'LIKE', '%' . $term . '%')
            ->orWhere('phone', 'LIKE', '%' . $term . '%')
            ->groupBy('id')
            ->havingRaw('role_id=' . $role_id)
            ->select(['id', 'role_id', 'name', 'username', 'email', 'phone'])
            ->get();
        return json_encode($records);
    }
    /**
     * Course listing method
     * @return Illuminate\Database\Eloquent\Collection
     */
    public function SubscribedUsers()
    {
        if (!checkRole(getUserGrade(2))) {
            prepareBlockUserMessage();
            return back();
        }
        $data['active_class'] = 'users';
        $data['title'] = getPhrase('subscribed_users');
        // return view('exams.quizcategories.list', $data);
        $view_name = 'admin.users.subscribeduser';
        return view($view_name, $data);
    }
    /**
     * This method returns the datatables data to view
     * @return [type] [description]
     */
    public function SubscribersData()
    {
        if (!checkRole(getUserGrade(2))) {
            prepareBlockUserMessage();
            return back();
        }
        $records = array();
        $records = App\UserSubscription::select(['email', 'created_at'])
            ->orderBy('updated_at', 'desc');
        return DataTables::of($records)
            ->make();
    }

    public function getResource(Request $request)
    {
        $userId = $request->id;
        $data['active_class'] = 'resource';
        $data['title'] = 'Xem khóa học-khóa luyện thi';

        // Get data course
        $data['series'] = DB::table('lmsseries_combo')
            ->join('payment_method', 'payment_method.item_id', '=', 'lmsseries_combo.id')
            ->join('payments', 'payment_method.id', '=', 'payments.payments_method_id')
            ->join('lmsseries', 'lmsseries.id', '=', 'payments.item_id')
            ->select(
                'lmsseries.*',
                DB::raw("(lmsseries_combo.slug) as combo_slug"),
                'payments.time',
                'payment_method.created_at',
                'payment_method.id AS idPayment',
                'payment_method.status',
                'payment_method.month_extend',
                DB::raw("(SELECT COUNT(lmscontents.id)  FROM lmscontents
		WHERE lmscontents.delete_status = 0 AND lmscontents.type NOT IN(0,8) AND
			lmscontents.lmsseries_id IN (lmsseries_combo.n1,lmsseries_combo.n2,lmsseries_combo.n3,lmsseries_combo.n4,lmsseries_combo.n5) ) as total_course"),
                DB::raw("(SELECT COUNT(lms_student_view.id)  FROM lms_student_view
				join lmscontents on lms_student_view.lmscontent_id = lmscontents.id
		WHERE lmscontents.delete_status = 0 AND lmscontents.type NOT IN(0,8) AND lms_student_view.users_id = " . $userId . " AND lmscontents.lmsseries_id IN (lmsseries_combo.n1,lmsseries_combo.n2,lmsseries_combo.n3,lmsseries_combo.n4,lmsseries_combo.n5)
			 ) as current_course")
            )
            ->where([
                ['payment_method.user_id', $userId],
                ['lmsseries_combo.delete_status', 0],
                ['lmsseries_combo.type', 0]
            ])
            ->distinct()
            ->get();

        // Get data course test
        $couseList = DB::table('lmsseries_combo')
            ->join('payment_method', 'payment_method.item_id', '=', 'lmsseries_combo.id')
            ->join('payments', 'payment_method.id', '=', 'payments.payments_method_id')
            ->join('lmsseries', 'lmsseries.id', '=', 'payments.item_id')
            ->select(
                'lmsseries.*',
                DB::raw("(lmsseries_combo.slug) as combo_slug"),
                'payments.time',
                'payment_method.created_at',
                'payment_method.id AS idPayment',
                'payment_method.status',
                'payment_method.month_extend',
                DB::raw("(SELECT COUNT(lmscontents.id)  FROM lmscontents
		WHERE lmscontents.delete_status = 0 AND lmscontents.type NOT IN(0,8) AND
			lmscontents.lmsseries_id IN (lmsseries_combo.n1,lmsseries_combo.n2,lmsseries_combo.n3,lmsseries_combo.n4,lmsseries_combo.n5) ) as total_course"),
                DB::raw("(SELECT COUNT(lms_student_view.id)  FROM lms_student_view
				join lmscontents on lms_student_view.lmscontent_id = lmscontents.id
		WHERE lmscontents.delete_status = 0 AND lmscontents.type NOT IN(0,8) AND lms_student_view.users_id = " . $userId . " AND lmscontents.lmsseries_id IN (lmsseries_combo.n1,lmsseries_combo.n2,lmsseries_combo.n3,lmsseries_combo.n4,lmsseries_combo.n5)
			 ) as current_course")
            )
            ->where([
                ['payment_method.user_id', $userId],
                ['lmsseries_combo.delete_status', 0],
                ['lmsseries_combo.type', 1]
            ])
            ->distinct()
            ->get();

        $data['series_selected'] = DB::table('lmsseries')
            ->join('lms_class', 'lmsseries.id', '=', 'lms_class.lmsseries_id')
            ->join('classes', 'lms_class.classes_id', '=', 'classes.id')
            ->join('classes_user', 'classes_user.classes_id', '=', 'classes.id')
            ->where([
                ['classes_user.student_id', $userId],
                ['lmsseries.delete_status', 0],
                ['type_series', 1]
            ])
            ->orderBy('order_by')
            ->get();


        if ($couseList != null) {
            if ($data['series'] == null) {
                $data['series'] = collect([]);
            }

            foreach ($couseList as $r) {
                $data['series']->push($r);
            }
        }

        $data['user'] = DB::table('users')
            ->where('users.id', '=', $userId)
            ->first();

        $data['layout'] = getLayout();
        $view_name = 'admin.users.resource-list';
        return view($view_name, $data);
    }

    public function postDisable(Request $request)
    {
        $data['MessageCode'] = 500;
        $data['MessageText'] = 'Lỗi không thể vô hiệu lớp học này';

        $id = $request->id;
        $record = PaymentMethod::where('id', $id)->first();

        // Check record is exist
        if ($record == null) {
            $data['MessageText'] = 'Dữ liệu không tồn tại';
            return json_encode($data);
        }

        // Update status
        $record->status = 3;
        $record->save();

        $data['MessageCode'] = 200;
        $data['MessageText'] = 'Cập nhật thành công';
        return json_encode($data);
    }

    public function postExtend(Request $request)
    {
        $id = $request->id;
        $month = $request->month;
        $data['MessageCode'] = 500;

        $record = PaymentMethod::where('id', $id)->first();

        // Check record is exist
        if ($record == null) {
            $data['MessageText'] = 'Dữ liệu không tồn tại';
            return json_encode($data);
        }

        // Update status
        $record->month_extend = (int) $month;
        $record->save();

        $data['MessageCode'] = 200;
        $data['MessageText'] = 'Cập nhật thành công';
        return json_encode($data);
    }

    public function getResultExam(Request $request)
    {

        $userId = $request->id;
        $data['active_class'] = 'users';
        $data['title'] = 'Xem kết quả thi';
        // Get data result exam
        $data['results'] = DB::table('quizresultfinish')
            ->join('examseries', 'examseries.id', '=', 'quizresultfinish.examseri_id')
            ->where('quizresultfinish.user_id', '=', $userId)
            ->orderBy('quizresultfinish.created_at', 'desc')
            ->select(
                'examseries.title',
                'examseries.category_id',
                'quizresultfinish.id',
                'quizresultfinish.finish',
                'quizresultfinish.created_at',
                'quizresultfinish.quiz_1_total',
                'quizresultfinish.quiz_2_total',
                'quizresultfinish.quiz_3_total',
                'quizresultfinish.total_marks',
                'quizresultfinish.status'
            )
            ->get();

        $data['user'] = DB::table('users')
            ->where('users.id', '=', $userId)
            ->first();

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

        $data['layout'] = getLayout();
        $view_name = 'admin.users.result-exam-list';
        return view($view_name, $data);
    }


    public function postCertificate(Request $request)
    {
        $id = $request->id;
        $data = array();
        $view_name = 'admin.student.exams.finish-results-exam';
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
                return ($total_score > 100) ? true : false;
                break;
            case 2:
            case 4:
                return ($total_score > 90) ? true : false;
                break;
            case 3:
                return ($total_score > 95) ? true : false;
                break;
            case 5:
                return ($total_score > 80) ? true : false;
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
     * The detailed learning progress screen
     *
     * @param string $slug
     * @return view
     */
    public function detailsLearning(string $slug)
    {
        $record = User::where('slug', $slug)->first();
        if ($isValid = $this->isValidRecord($record)) {
            return redirect($isValid);
        }

        /**
         * Validate the non-admin user wether is trying to access other user profile
         * If so return the user back to previous page with message
         */
        if (!isEligible($slug)) {
            return back();
        }
        $userId = $record->id;
        $data = $this->initDataForLearning($userId);
        $data['record'] = $record;

        if (checkRole(['parent'])) {
            $data['active_class'] = 'children';
        }
        $view_name = 'admin.users.user-details-learning';

        return view($view_name, $data);
    }

    /**
     * Init data for learning function
     *
     * @param int $userId
     * @return array
     */
    private function initDataForLearning(int $userId)
    {
        $data = [];
        $data['title'] = getPhrase('user_details_learning');
        $data['layout'] = getLayout();
        $data['active_class'] = 'users';

        // Get data related to video
        $recordVideos = $this->getVideoByUserId($userId, true);
        $data['total_videos'] = $recordVideos->total_videos;
        $data['learning_path'] = $recordVideos->learning_path;
        $data['video_watched'] = $recordVideos->video_watched;
        $data['last_lesson'] = $recordVideos->last_lesson;
        $data['series_completion'] = $recordVideos->series_completion;
        $data['overall_completion'] = $recordVideos->overall_completion;
        $data['average_videos_per_month'] = $recordVideos->average_videos_per_month;

        // Get data related to score
        $avgScore = $this->calculateAverageScore($userId);
        $data['total_score'] = $avgScore->average_all_time;
        $data['total_score_of_month'] = $avgScore->average_per_month;

        // Get data related to learning
        $timeLearning = $this->takeTheTimeLearning($userId);
        $data['study'] = $timeLearning->study;
        $data['exams'] = $timeLearning->exams;
        $data['exercises'] = $timeLearning->exercises;

        // Get score
        $data['scores'] = $this->getScore($userId);

        // Get data for chart
        $dataChartTotalNumOfCourses = $this->getDataForTotalNumOfCourses($userId);
        $dataChartTotalNumOfCoursesByUser = $this->getDataForPercentageOfCourseByUser($userId);
        $data['number_of_courses'] = $dataChartTotalNumOfCourses['number_of_courses'];
        $data['percentage_of_course'] = $dataChartTotalNumOfCoursesByUser['percentage_of_course_by_user'];

        return $data;
    }

    /**
     * Get all videos by user id
     *
     * @param int $userId
     * @param bool $excludeUnwatchedCourses
     * @param Carbon $time
     * @return stdClass
     */
    public function getVideoByUserId(int $userId, bool $excludeUnwatchedCourses = false, Carbon $time = null)
    {
        // Get all videos that the user has viewed
        $query = LmsStudentView::join('lmscontents', 'lmscontents.id', '=', 'lms_student_view.lmscontent_id')
            ->join('lmsseries', 'lmsseries.id', '=', 'lmscontents.lmsseries_id')
            ->select('lmsseries.title', 'lmscontents.bai', 'lmscontents.file_path', 'lms_student_view.finish', 'lmscontents.id as content_id', 'lms_student_view.created_date')
            ->where('lms_student_view.users_id', $userId);

        if ($time) {
            $startOfMonth = $time->startOfMonth()->format('Y-m-d');
            $endOfMonth = $time->endOfMonth()->format('Y-m-d');

            // Filter by month
            $query->whereBetween('lms_student_view.created_date', [$startOfMonth, $endOfMonth]);
        }

        $userVideos = $query->get();

        // Get all videos in the series (not just the ones watched by the user)
        $allVideos = LmsContent::join('lmsseries', 'lmsseries.id', '=', 'lmscontents.lmsseries_id')
            ->select('lmsseries.title', 'lmscontents.bai', 'lmscontents.file_path', 'lmscontents.id as content_id')
            ->whereNotNull('lmscontents.file_path')
            ->get();

        // Filter out series where the user has not watched any videos
        if ($excludeUnwatchedCourses) {
            $watchedSeriesTitles = $userVideos->pluck('title')->unique();
            $allVideos = $allVideos->filter(function ($video) use ($watchedSeriesTitles) {
                return $watchedSeriesTitles->contains($video->title);
            });
        }

        // Calculate completion percentages by series
        $seriesCompletion = $allVideos->groupBy('title')->map(function ($group) use ($userVideos) {
            $totalInSeries = $group->count();
            $completedInSeries = $group->filter(function ($video) use ($userVideos) {
                return $userVideos->pluck('content_id')->contains($video->content_id);
            })->count();
            $completionPercentage = $totalInSeries > 0 ? ($completedInSeries / $totalInSeries) * 100 : 0;
            return round($completionPercentage, 2);
        });

        // Get the last video watched by the user
        $firstSeries = $userVideos->sortBy('created_date')->first()->title ?? null;
        $lastVideo = $userVideos->sortByDesc('created_date')->first()->bai ?? null;

        // Calculate overall course completion percentage
        $totalVideosWithFilePath = $allVideos->count();
        $videoWatchedWithFilePath = $userVideos->count();
        $totalWatchedFinish = $userVideos->where('finish', LmsStudentView::FINISH)->count();
        $overallCompletion = $videoWatchedWithFilePath > 0 ? ($totalWatchedFinish / $videoWatchedWithFilePath) * 100 : 0;
        $overallCompletionPercentage = $totalVideosWithFilePath > 0 ? ($videoWatchedWithFilePath / $totalVideosWithFilePath) * 100 : 0;

        // Calculate average videos watched per month
        $userVideosByMonth = $userVideos->groupBy(function ($item) {
            return \Carbon\Carbon::parse($item->created_date)->format('Y-m'); // Group by month
        });

        $monthlyVideosCount = $userVideosByMonth->map(function ($group) {
            return $group->count();
        });

        $averageVideosPerMonth = $monthlyVideosCount->isNotEmpty() ? $monthlyVideosCount->avg() : 0;

        // Create result object
        $result = new \stdClass();
        $result->total_videos = $totalVideosWithFilePath;
        $result->video_watched = $videoWatchedWithFilePath;
        $result->total_videos_finish = $totalWatchedFinish;
        $result->overall_completion = round($overallCompletion, 2);
        $result->learning_path = $firstSeries; // Safe check if videos exist
        $result->last_lesson = $lastVideo; // Get the last video watched
        $result->series_completion = $seriesCompletion;
        $result->total_overall_completion = round($overallCompletionPercentage, 2); // Round to 2 decimal places
        $result->average_videos_per_month = round($averageVideosPerMonth, 2); // Average videos watched per month

        return $result;
    }

    /**
     * Caculator average score of test
     *
     * @param int $userId
     * @param Carbon $time
     * @return object
     */
    public function calculateAverageScore(int $userId, Carbon $time = null)
    {
        // Create the query to fetch test results
        $query = User::join('lms_test_result', 'lms_test_result.users_id', '=', 'users.id')
            ->where('users.id', $userId);

        // If a time is provided, filter the results by the given time period
        if ($time) {
            $startOfMonth = $time->startOfMonth()->format('Y-m-d');
            $endOfMonth = $time->endOfMonth()->format('Y-m-d');
            $query->whereBetween('lms_test_result.created_at', [$startOfMonth, $endOfMonth]);
        }

        // Get test results
        $testResults = $query->get();

        // If no data is found, return an object with zero averages
        if ($testResults->isEmpty()) {
            return (object) [
                'average_all_time' => 0.0,
                'average_per_month' => 0.0
            ];
        }

        // Calculate averages
        $totalPointsAchieved = $testResults->sum('point');
        $totalPointsPossible = $testResults->sum('total_point');
        $averageAllTime = $totalPointsPossible > 0 ? ($totalPointsAchieved / $totalPointsPossible) * 100 : 0;

        // Group results by month
        $monthlyScores = collect();
        $startDate = $testResults->min('created_at');
        $endDate = $testResults->max('created_at');

        $startDate = Carbon::parse($startDate);
        $endDate = Carbon::parse($endDate);

        while ($startDate->lte($endDate)) {
            $monthStart = $startDate->copy()->startOfMonth();
            $monthEnd = $startDate->copy()->endOfMonth();

            $monthlyResults = $testResults->filter(function ($result) use ($monthStart, $monthEnd) {
                $createdAt = Carbon::parse($result->created_at);
                return $createdAt->between($monthStart, $monthEnd);
            });

            $totalPointsAchievedMonth = $monthlyResults->sum('point');
            $totalPointsPossibleMonth = $monthlyResults->sum('total_point');

            $monthlyScores->push([
                'total_points_achieved' => $totalPointsAchievedMonth,
                'total_points_possible' => $totalPointsPossibleMonth,
            ]);

            $startDate->addMonth();
        }

        $monthlyAverages = $monthlyScores->map(function ($monthlyScore) {
            return $monthlyScore['total_points_possible'] > 0 ?
                ($monthlyScore['total_points_achieved'] / $monthlyScore['total_points_possible']) * 100 :
                0;
        });

        $averagePerMonth = $monthlyAverages->avg();

        return (object) [
            'average_all_time' => round($averageAllTime, 2),
            'average_per_month' => round($averagePerMonth, 2)
        ];
    }

    /**
     * Take the time when students start (study, take exams, and do exercises)
     *
     * @param int $userId
     * @return object
     */
    private function takeTheTimeLearning(int $userId)
    {
        // Init data
        $classUser = ClassesUser::where('student_id', $userId)->orderBy('created_at', 'asc');
        $lmsView = LmsStudentView::where('users_id', $userId)->orderBy('created_date', 'asc');
        $exam = DB::table('examseries_rate')->where('user_id', $userId)->orderBy('created_at', 'asc');
        $quiz = QuizResultfinish::where('user_id', $userId)->orderBy('created_at', 'asc');

        // Study (If not join class -> find first video watched)
        $timeBeginStudy = $classUser->first()->created_at
            ?? ($lmsView->first()->created_date ?? config('messenger.message_learning.no_join'));

        // Exams
        $timeBeginExam = $exam->first()->created_at ?? config('messenger.message_learning.no_join');

        // Exercises
        $timeBeginExercises = $quiz->first()->created_at ?? config('messenger.message_learning.no_join');

        return (object) [
            'study' => $timeBeginStudy,
            'exams' => $timeBeginExam,
            'exercises' => $timeBeginExercises
        ];
    }

    /**
     * Get scores of tests and exams in the course
     *
     * @param int $userId
     * @return collection|null
     */
    private function getScore(int $userId)
    {
        // Create the query to fetch test results
        return User::join('lms_test_result', 'lms_test_result.users_id', '=', 'users.id')
            ->join('lmscontents', 'lmscontents.id', '=', 'lms_test_result.lmscontent_id')
            ->join('lmsseries', 'lmsseries.id', '=', 'lmscontents.lmsseries_id')
            ->select('lmscontents.bai', 'lms_test_result.point', 'lms_test_result.total_point', 'lmsseries.title')
            ->where('users.id', $userId)
            ->get();
    }

    /**
     * Get data total number of course for chart
     *
     * @param int $userId
     * @return array
     */
    private function getDataForTotalNumOfCourses(int $userId)
    {
        $recordsPayment = LmsSeries::join('payments', 'lmsseries.id', '=', 'payments.item_id')
            ->join('users', 'users.id', '=', 'payments.user_id')
            ->select([
                'users.id as user_id',
                'users.name',
                'users.username',
                'lmsseries.title',
                'lmsseries.id',
                'users.slug',
                DB::raw("(SELECT COUNT(lmscontents.id) FROM lmscontents
                        WHERE lmscontents.delete_status = 0 AND lmscontents.type NOT IN(0,8)
                        AND lmscontents.lmsseries_id = lmsseries.id ) as total_course"),
                DB::raw("(SELECT COUNT(lms_student_view.id) FROM lms_student_view
                        join lmscontents on lms_student_view.lmscontent_id = lmscontents.id
                        WHERE lmscontents.delete_status = 0 AND lmscontents.type NOT IN(0,8)
                        AND lms_student_view.users_id = users.id AND lmscontents.lmsseries_id = lmsseries.id)
                        as current_course")
            ])
            ->where('users.id', $userId)
            ->distinct()  // Thêm DISTINCT để loại bỏ bản ghi trùng lặp
            ->orderBy('users.id', 'desc')
            ->get();

        $listSeriesIds = $recordsPayment->pluck('id');
        $listSeries = LmsSeries::select(['id', 'title'])->get();
        $listRecordNotPayment = $listSeries->whereNotIn('id', $listSeriesIds);

        $dataset = [];
        $bgcolor = [];
        $border_color = [];
        $labelsVideo = [
            'Khoá học đã tham gia',
            'Khoá học chưa tham gia',
        ];

        $color_number = rand(0, 999);
        $dataset[] = $recordsPayment->count();
        $dataset[] = $listRecordNotPayment->count();
        $bgcolor[] = getColor('', $color_number);
        $border_color[] = getColor('background', $color_number);
        $dataset_label[] = '';
        $numberOfCourses['type'] = 'bar';
        $numberOfCourses['title'] = 'Tổng số khóa học: ' . $listSeries->count();
        //horizontalBar, bar, polarArea, line, doughnut, pie
        $numberOfCourses['data'] = (object) array(
            'labels' => $labelsVideo,
            'dataset' => $dataset,
            'dataset_label' => $dataset_label,
            'bgcolor' => $bgcolor,
            'border_color' => $border_color
        );
        // Init data total number of courses students are participating
        $data['number_of_courses'][] = (object) $numberOfCourses;

        return $data;
    }

    /**
     * Get data percentage of course by user for charts
     *
     * @param int $userId
     * @return array
     */
    private function getDataForPercentageOfCourseByUser(int $userId)
    {
        $lmsSeries = LmsSeries::select([
            'lmsseries.id',
            'lmsseries.title',
            'lmsseries.slug',
            DB::raw("(SELECT COUNT(lmscontents.id) FROM lmscontents
                    WHERE lmscontents.delete_status = 0 AND lmscontents.type NOT IN(0,8)
                    AND lmscontents.lmsseries_id = lmsseries.id) as total_course")
        ])
            ->get();

        $userViewedCourses = LmsStudentView::select([
            'lmscontents.lmsseries_id',
            DB::raw("COUNT(DISTINCT lms_student_view.lmscontent_id) as viewed_courses")
        ])
            ->join('lmscontents', 'lms_student_view.lmscontent_id', '=', 'lmscontents.id')
            ->where('lms_student_view.users_id', $userId)
            ->groupBy('lmscontents.lmsseries_id')
            ->pluck('viewed_courses', 'lmscontents.lmsseries_id');

        $userPurchasedCourses = Payment::select([
            'lmsseries.id as lmsseries_id',
            DB::raw("COUNT(DISTINCT payments.item_id) as purchased_courses")
        ])
            ->join('lmsseries', 'payments.item_id', '=', 'lmsseries.id')
            ->where('payments.user_id', $userId)
            ->groupBy('lmsseries.id')
            ->pluck('purchased_courses', 'lmsseries_id');

        $result = $lmsSeries->map(function ($series) use ($userViewedCourses, $userPurchasedCourses) {
            $seriesId = $series->id;
            $totalCourses = $series->total_course;
            $viewedCourses = $userViewedCourses->get($seriesId, 0);
            $notViewedCourses = max(0, $totalCourses - $viewedCourses);
            $purchasedCourses = $userPurchasedCourses->get($seriesId, 0);
            $notPurchasedCourses = $totalCourses - $purchasedCourses;

            return [
                'id' => $seriesId,
                'title' => $series->title,
                'total_courses' => $totalCourses,
                'viewed_courses' => $viewedCourses,
                'not_viewed_courses' => $notViewedCourses,
                'purchased_courses' => $purchasedCourses,
                'not_purchased_courses' => $notPurchasedCourses,
            ];
        });

        $bgcolor = [];
        $borderColor = [];
        $borderColorDefault = [];
        $color_number = rand(0, 999);

        // Make color to column
        foreach ($result as $index => $item) {
            $bgcolor[] = getColor('background', $index);
            $border_color[] = getColor('background', $index);
            $borderColorDefault[] = getColor('', $color_number);
        }

        $labelsVideo = $result->pluck('title')->toArray();
        $dataView = $result->pluck('viewed_courses')->toArray();
        $dataNotView = $result->pluck('not_viewed_courses')->toArray();

        $datasetLabel[] = 'Bài học đã học';
        $datasetLabelDefault[] = 'Bài học chưa học';
        $numberOfCourses['type'] = 'bar';
        $numberOfCourses['stack'] = true;
        $numberOfCourses['data'] = (object) array(
            'labels' => $labelsVideo,
            'data_view' => $dataView,
            'data_not_view' => $dataNotView,
            'dataset_label' => $datasetLabel,
            'dataset_label_default' => $datasetLabelDefault,
            'bgcolor' => $bgcolor,
            'border_color' => $borderColor,
            'border_color_default' => $borderColorDefault,
        );

        // Init data total number of courses students are participating
        $data['percentage_of_course_by_user'][] = (object) $numberOfCourses;

        return $data;
    }

    /**
     * Reward point
     *
     * @param string $slug
     * @return view
     */
    public function rewardPoint(string $slug)
    {
        $record = User::where('slug', $slug)->get()->first();
        if ($isValid = $this->isValidRecord($record)) {
            return redirect($isValid);
        }
        if (!isEligible($slug)) {
            return back();
        }
        $data['record'] = $record;
        $data['active_class'] = 'reward-point';
        $data['title'] = 'Điểm tích luỹ';
        $data['layout'] = 'admin.layouts.student.studentsettinglayout';
        $data['series_upload_path'] = config('constant.series.upload_path');
        $data['redeemed_series'] = $this->lmsSeriesComboService->getRedeemedSeries();
        $data['total_point'] = Auth::user()->reward_point + Auth::user()->recharge_point;

        $view_name = 'admin.users.reward-point';
        return view($view_name, $data);
    }

    /**
     * Reward point leaderboard
     *
     * @param string $slug
     * @return view
     */
    public function showLeaderboard(string $slug)
    {
        $startOfWeek = Carbon::now()->startOfWeek();
        $leaderboard = WeeklyLeaderboard::with('user')
            ->where('week_start', $startOfWeek)
            ->orderBy('rank')
            ->limit(NUMBER_OF_STUDENTS_ON_THE_LEADERBOARD)
            ->get();
        $user = WeeklyLeaderboard::getUserRank(Auth::user()->id, $startOfWeek);
        $data['user'] = $user;
        $data['leaderboard'] = $leaderboard;
        $data['active_class'] = 'reward-points-leaderboard';
        $data['title'] = 'Thành tích tuần';
        $data['layout'] = 'admin.layouts.student.studentsettinglayout';
        $view_name = 'admin.users.reward-points-leaderboard';
        return view($view_name, $data);
    }

    public function dailyStreak()
    {
        $loginStreak = Auth::user()->login_streak;
        $loginStreakConditions = config('constant.login.streak');
        $data = $this->userService->updateLoginStreak();

        return $data;

    }

    /**
     * Get my courses dropdown
     *
     * @return mixed
     */
    public function getMyCoursesDropdown()
    {
        $view_series_history = $this->lmsSeriesService
            ->getHistoryViews(Auth::user()->series_views_history ?? [], Auth::user());

        return response()->json([
            'html' => view('client.components.my-courses-dropdown', compact('view_series_history'))->render()
        ], 200);
    }

    public function getDataUser()
    {
        // Get the authenticated user
        $user = Auth::user();

        // Fetch latest streak data and milestone rewards
        $streakCurrent = $user->login_streak;
        $lastLoginDate = Carbon::parse($user->last_login_date); // Parse last login date
        $currentDate = Carbon::now();

        // Check if the login streak needs to be reset
        if ($lastLoginDate->diffInDays($currentDate) > 1) {
            // Reset the login streak
            $streakCurrent = 0;
            $user->login_streak = $streakCurrent;
            $arrayHistoryPoint = $user->point_history;
            $arrayHistoryPoint['streak'] = $streakCurrent;
            $user->point_history = $arrayHistoryPoint;
            $user->save();
        }
        $streakMilestones = collect(getRewardPointRule('daily_login')['milestones'])->pluck('days')->all();

        // Prepare data for response
        $data = [
            'streakCurrent' => $streakCurrent,
            'lastLoginDate' => $lastLoginDate,
            'streakMilestones' => $streakMilestones,
        ];

        // Return the data as a JSON response
        return response()->json($data);
    }
}
