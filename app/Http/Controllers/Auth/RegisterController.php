<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Http\Controllers\Controller;
use App\Logger;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        $rules = [
            'name'     => 'bail|required|max:100|',
            'email'    => 'bail|email|required|unique:users,email',
            'phone'    => 'bail|required|regex:/^0[1-9]{1}[0-9]{8,9}$/|unique:users,phone',
            // 'g-recaptcha-response' => 'required|captcha',
        ];

        $messages = [
            'email.unique' => 'Email đã được đăng ký!',
            'phone.unique' => 'Số điện thoại đã được đăng ký!',
            'name.required' => 'Hãy nhập họ tên!',
            'email.required' => 'Hãy nhập email!',
            'phone.required' => 'Hãy nhập số điện thoại!',
            'phone.regex' => 'Số điện thoại không đúng định dạng!',
            'email' => 'Email chưa chính xác!',
            // 'g-recaptcha-response.required' => 'Hãy đánh dấu vào ô kiểm tra robot!',
        ];

        return Validator::make($data, $rules, $messages);
    }

    /**
     * Register a new user
     *
     * @param  \Illuminate\Http\Request  $request
     * @return mixed
     */
    public function register(Request $request)
    {
        if ($request->isMethod('get')) {
            return redirect()->to('/');
        }

        $validator = $this->validator($request->all());
        if ($validator->fails()) {
            $responseData =  [
                'errors' => $validator->errors()->messages(),
                'data' => $request->all(),
            ];
            return response()->json([
                'success' => false,
                'html' => view('client.components.failed-register-modal', $responseData)->render(),
                'messages' => 'Tạo tài khoản thất bại'
            ], 422);
        }

        //Kiểm tra country
        $excludedCountryCodesString = strtolower(env('EXCLUDED_COUNTRY_CODES'));
        $excludedCountryCodes = explode(',', $excludedCountryCodesString);

        $ip_info = ip_info('Visitor', "Location");

        if ($ip_info) {
            if (in_array(strtolower($ip_info['country_code']), $excludedCountryCodes)) {
                return redirect(env('APP_URL'));
            }
        }

        //Loại bỏ những email có đuôi .ru
        if (preg_match("/^.+@.+\.ru$/", $request->email)) {
            return redirect(env('APP_URL'));
        }

        DB::beginTransaction();
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->level = 5;
        $user->role_id = 5;
        $user->is_register = 1;
        $user->slug = createSlug(User::class, $request->name);
        $user->login_enabled = 1;
        $user->reward_point = getRewardPointRule('registration')['points'];
        $user->last_login_date = now();
        $user->login_streak = 1;
        $user->confirmation_code = str_random(30);

        $last_uid = DB::table('users')
            ->whereYear('created_at', date('Y'))
            ->whereNotNull('uid')
            ->latest('created_at')
            ->first();
        if ($last_uid) {
            $uid_code  = $last_uid->uid;
            $uid_code  = ++$uid_code;
            $uid_code  = str_pad($uid_code, 5, '0', STR_PAD_LEFT);
            $user->uid = '' . $uid_code . '';
            $uid_code  = 'HID' . date('y') . $uid_code;
        } else {
            $user->uid = '00001';
            $uid_code  = 'HID' . date('y') . '00001';
        }
        $user->hid = $uid_code;
        $user->username = $uid_code;

        if ($ip_info) {
            $user->country_code = $ip_info['country_code'];
            $user->country = $ip_info['country'];
            $user->city = $ip_info['city'];
            $user->state = $ip_info['state'];
            $user->ip = $ip_info['ip'];
        }

        $rawPassword = makeRandomPassword();
        $user->password = bcrypt($rawPassword);
        $user->save();
        $user->roles()->attach($user->role_id);

        $log = new Logger(env('MAIL_LOG_PATH'));
        try {
            sendEmail('registration', array('name' => $request->name, 'username' => $user->username, 'to_email' => $user->email, 'password' => $rawPassword, 'to_email_bcc' => env('TO_EMAIL_CC')));
            DB::commit();
            return response()->json([
                'success' => true
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            $log->putLog('An error occurred: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'messages' => 'Có lỗi xảy ra, vui lòng liên hệ BQT để khắc phục!'
            ], 500);
        }
    }
}
