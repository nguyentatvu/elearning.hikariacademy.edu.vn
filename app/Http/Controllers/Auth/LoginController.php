<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
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
        $this->middleware('guest')->except('logout');
    }

    /**
     * Login user
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        if ($request->isMethod('get')) {
            return redirect()->to('/');
        }

        $columns = array(
            'email'    => 'bail|required',
            'password'    => 'bail|required',
            // 'g-recaptcha-response' => 'required|captcha',
        );
        $this->validate($request, $columns);

        $login_status = false;
        if (Auth::attempt(['username' => $request->email, 'password' => $request->password])) {
            $login_status = true;
        } elseif (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $login_status = true;
        } elseif (Auth::attempt(['hid' => $request->email, 'password' => $request->password])) {
            $login_status = true;
        }
        if (!$login_status) {
            return response()->json([
                'success' => false
            ], 422);
        }

        if ($login_status) {
            // Prevent multiple login
            if (!env('LOGIN_MULTI')) {
                $previous_session = Auth::user()->last_session;
                if ($previous_session) {
                    Session::getHandler()->destroy($previous_session);
                }
            }

            $user_update_session = User::find(Auth::user()->id);
            $user_update_session->last_session = Session()->getId();
            $user_update_session->save();

            return response()->json([
                'success' => true
            ], 200);
        }
    }

    /**
     * Logout user
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout()
    {
        if (Auth::check()) {
            Auth::logout();
        }

        session()->flash('logout_successful');
        return redirect('/');
    }
}
