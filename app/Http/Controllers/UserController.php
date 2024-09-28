<?php
namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use \Auth;
use App\Uid;
use App\UidChange;
use App\UidTb;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use JWTAuth;
use JWTAuthException;
use DB;
use Config;
//use Hash;
class UserController extends Controller
{   
    private $user;
    public function __construct(User $user){
        $this->user = $user;
    }
    public function register(Request $request){
        $validator = Validator::make($request->all(), [
            'uid_u' => 'required|min:1|max:5|numeric',
            'uid_v' => 'required|min:0|max:5|numeric',
            'uid_email' => 'required|email',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status'=> "ERROR",
                'message'=> 'error_register',
                'data'=>$validator->errors()
            ], 404);
        }
        try {
            DB::beginTransaction();
            $user = JWTAuth::toUser($request->token);
            $check_email = Uid::where('uid_email','=',$request->uid_email)->first();
            if ($check_email) {
                $uid_change = new UidChange();
                $uid_change->uid = $check_email->uid;
                $uid_change->uid_u     = $request->uid_u;
                $uid_change->uid_v     = $request->uid_v;
                //$uid_change->user_updated  = $user->id;
                $uid_change->save();
                DB::commit();
                return response()->json([
                    'status'=> "SUCCESS",
                    'message'=> 'hid_exist',
                    'data'=>$check_email
                ]);
            } else {
                $last_uid = Uid::whereYear('created_at','=',date('Y'))->orderBy('created_at', 'desc')->first();
                if ($last_uid) {
                  $uid_code = $last_uid->uid;
                  $uid_code = ++$uid_code;
                } else {
                  $uid_code = 'HID'.date('y').'00001';
                }
                $uid  = new Uid();
                $uid->uid  = $uid_code;
                $uid->uid_u     = $request->uid_u;
                $uid->uid_v     = $request->uid_v;
                $uid->uid_email     = $request->uid_email;
                //$uid->uid_user_created  = $user->id;
                $uid->save();
                DB::commit();
                return response()->json([
                    'status'=> "SUCCESS",
                    'message'=> 'hid_created_successfully',
                    'data'=>$uid 
                ]);
            }
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'status'=> "ERROR",
                'message'=> $e->errorInfo,
                'data'=> ''
            ], 500);
        }
    }



    public function login(Request $request){
        $credentials = $request->only('username', 'password');
        $token = null;
        
        try {
           if (!$token = JWTAuth::attempt(['username' => $request->username, 'password' => $request->password, 'role_id' => 10])) {
            return response()->json([
                'status'=> "ERROR",
                'message'=> 'invalid_username_or_password',
                'data'=>""
            ]);
           }
        } catch (JWTAuthException $e) {
            return response()->json([
                'status'=> "ERROR",
                'message'=> 'failed_to_create_token',
                'data'=> ''
            ]);
        }

        return response()->json([
            'status'=> "SUCCESS",
            'message'=> 'token_created_successfully',
            'data'=>compact('token')
            ]);
                
    }
    public function uid(Request $request){
        $credentials = $request->only('uid', 'password');
        $uid = Auth::guard('uid')->attempt($credentials);
        if ($uid) {
            return response()->json([
                'status'=> "SUCCESS",
                'message'=> 'login_successfully',
                'data'=>true
            ]);
        } else {
            return response()->json([
                'status'=> "ERROR",
                'message'=> 'invalid_email_or_password',
                'data'=>false
            ]);
        }
    }
    public function listuid(Request $request){
        $listuid      =   Uid::all();
        return response()->json([
                'status'=> "SUCCESS",
                'message'=> 'list_uid',
                'data'=>$listuid
            ]);
    }
    public function getUserInfo(Request $request){
        $user = JWTAuth::user();
        return response()->json([
            'status'=> "SUCCESS",
            'message'=> 'HID',
            'data'=>$user
        ]);
    }
    public function logout(Request $request) {
        try {
            JWTAuth::invalidate($request->token);
                return response()->json([
                'status'=> "SUCCESS",
                'message'=> 'Logout successfully',
                'data'=>''
            ]);
        } catch (JWTException $e) {
            return response()->json('Failed to logout, please try again.', Response::HTTP_BAD_REQUEST);
        }
    }
    public function refresh()
    {
        return response()->json([
            'status'=> "SUCCESS",
            'message'=> 'HID',
            'data'=>JWTAuth::getToken()
        ]);


        /*
        //phải sử dụng (Request $request)
        $client = new Client();

        $res_login = $client->request('POST', 'https://test.hikariacademy.edu.vn/api/auth/login', [
            'form_params' => [
                'username' => 'phuocpham1988',
                'password' => 'Kjsasdlk1988',
            ]
        ]);
        $result = json_decode ($res_login->getBody()->getContents());
        if ($result->status == 'SUCCESS') {
          $token = $result->data->token;
          $request->session()->put('hid_token', $token);
          // get session
            // echo $request->session()->get('hid_token');
          $res_hid = $client->request('POST', 'https://test.hikariacademy.edu.vn/api/auth/register', [
            'form_params' => [
                'token' => $token,
                'uid_u' => '1',
                'uid_v' => '2',
                'uid_email' => 'phuoc11111111111@gmail.com',
            ]
          ]);
          $result_hid = json_decode ($res_hid->getBody()->getContents());
          echo "<pre>";
          print_r ($result_hid);
          echo "</pre>";

        }
        exit;
        */
    }
}  