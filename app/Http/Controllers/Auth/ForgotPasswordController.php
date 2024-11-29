<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\UserService;
use Illuminate\Http\Request;

class ForgotPasswordController extends Controller
{
    private $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function forgotPassword(Request $request)
    {
        $columns = array(
            'email_forgot_password'    => 'bail|email|required|exists:users,email',
        );
        $messsages = array(
            'email_forgot_password.required' => 'Hãy nhập email!',
            'email_forgot_password.email' => 'Email không hợp lệ',
            'email_forgot_password.exists' => 'Email không tồn tại trong hệ thống. Vui lòng kiểm tra lại',
        );
        $this->validate($request, $columns, $messsages);

        $email = $request->email_forgot_password;

        $result = $this->userService->forgotPassword($email);

        if ($result) {
            return response()->json([
                'message' => 'Mật khẩu mới đã được gửi về địa chỉ ' . $email . '. (Vui lòng kiểm tra cả spambox).'
            ], 200);
        } else {
            return response()->json([
                'message' => 'Có lỗi xảy ra, vui lòng liên hệ với quản trị viên để khắc phục!'
            ], 500);
        }
    }
}
