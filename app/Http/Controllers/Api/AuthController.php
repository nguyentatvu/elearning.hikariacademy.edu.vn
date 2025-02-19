<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Resources\UserResource;
use App\Services\UserService;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenBlacklistedException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Facades\JWTAuth;

/**
 * @SWG\Tag(
 *     name="Auth",
 *     description="Operations related to auth"
 * )
 */
class AuthController extends Controller
{
    private $userService;

    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct(UserService $userService, JWTAuth $jwt)
    {
        parent::__construct();
        $this->middleware('auth:api', ['except' => ['login', 'register', 'refresh', 'forgotPassword']]);
        $this->userService = $userService;
    }

    /**
     * @SWG\Post(
     *     tags={"Auth"},
     *     path="/auth/login",
     *     summary="Login",
     *     @SWG\Parameter(
     *         name="body",
     *         in="body",
     *         required=true,
     *         type="string",
     *         description="User information to login",
     *         @SWG\Schema(
     *             @SWG\Property(property="email", type="string", example="vu.nguyentat@gmail.com"),
     *             @SWG\Property(property="password", type="string", example="123456"),
     *         ),
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="Successful login",
     *         @SWG\Schema(
     *             type="object",
     *             @SWG\Property(property="access_token", type="string", example="eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJzdWIiOiIxMjM0NTY3ODkwIiwibmFtZSI6IkpvaG4gRG9lIiwiaWF0IjoxNTE2MjM5MDIyfQ.SflKxwRJSMeKKF2QT4fwpMeJf36POk6yJV_adQssw5c"),
     *             @SWG\Property(property="token_type", type="string", example="bearer"),
     *             @SWG\Property(property="expires_in", type="integer", example=3600)
     *         )
     *     ),
     *     @SWG\Response(
     *         response=400,
     *         description="Bad Request - Invalid email or password",
     *         @SWG\Schema(
     *             type="object",
     *             @SWG\Property(property="error", type="string", example="Username or password is incorrect")
     *         )
     *     ),
     *     @SWG\Response(
     *         response=422,
     *         description="Unprocessable Entity",
     *         @SWG\Schema(
     *             type="object",
     *             @SWG\Property(property="error", type="object",
     *                 @SWG\Property(property="email", type="array", @SWG\Items(type="string"), example={"The email field is required.", "The email must be a valid email address."}),
     *                 @SWG\Property(property="password", type="array", @SWG\Items(type="string"), example={"The password field is required.", "The password must be at least 6 characters."})
     *             )
     *         )
     *     ),
     *     @SWG\Response(
     *         response=500,
     *         description="Internal Server Error",
     *         @SWG\Schema(
     *             type="object",
     *             @SWG\Property(property="error", type="string", example="Something went wrong.")
     *         )
     *     ),
     *     security={{"bearer_token": {}}},
     * )
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $credentials = request(['email', 'password']);
        if (!$token = auth()->guard('api')->attempt($credentials)) {
            return response()->json(['error' => 'Username or password is incorrect'], Response::HTTP_BAD_REQUEST);
        }

        return $this->respondWithToken($token);
    }

    /**
     * @SWG\Post(
     *     tags={"Auth"},
     *     path="/auth/register",
     *     summary="Register",
     *     @SWG\Parameter(
     *         name="body",
     *         in="body",
     *         description="User information to register.",
     *         required=true,
     *         @SWG\Schema(
     *             @SWG\Property(property="name", type="string", example="Nguyễn Văn A"),
     *             @SWG\Property(property="email", type="string", example="nguyenvana@gmail.com"),
     *         ),
     *     ),
     *     @SWG\Response(
     *         response=201,
     *         description="Successfully register",
     *         @SWG\Schema(
     *             type="object",
     *             @SWG\Property(property="id", type="integer", example=1),
     *             @SWG\Property(property="name", type="string", example="John Doe"),
     *             @SWG\Property(property="username", type="string", example="johndoe"),
     *             @SWG\Property(property="email", type="string", example="john.doe@example.com"),
     *             @SWG\Property(property="image", type="string", example="Link image"),
     *             @SWG\Property(property="address", type="string", example="Ho Chi Minh"),
     *             @SWG\Property(property="reward_point", type="integer", example=100),
     *         )
     *     ),
     *     @SWG\Response(
     *         response=422,
     *         description="Unprocessable Entity",
     *         @SWG\Schema(
     *             type="object",
     *             @SWG\Property(property="error", type="object",
     *                 @SWG\Property(property="name", type="array", @SWG\Items(type="string"), example={"The name field is required.", "The name may not be greater than 100 characters."}),
     *                 @SWG\Property(property="email", type="array", @SWG\Items(type="string"), example={"The email field is required.", "The email must be a valid email address.", "The email has already been taken."}),
     *             )
     *         )
     *     ),
     *     @SWG\Response(
     *         response=500,
     *         description="Internal Server Error",
     *         @SWG\Schema(
     *             type="object",
     *             @SWG\Property(property="error", type="string", example="Something went wrong.")
     *         )
     *     ),
     *     security={{"bearer_token": {}}},
     * )
     */
    public function register(Request $request)
    {
        $data = $request->only(['name', 'email', 'phone']);
        $validator = Validator::make($data, [
            'name' => 'bail|required|max:100',
            'email' => 'bail|email|required|unique:users,email',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $data['phone'] = null;
        $user = $this->userService->register($data);

        if (!$user) {
            return response()->json(['error' => 'Something went wrong'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return new UserResource($user);
    }

    /**
     * @SWG\Post(
     *     tags={"Auth"},
     *     path="/auth/logout",
     *     summary="Logout",
     *     description="Logout the user and invalidate the token",
     *     @SWG\Response(
     *         response=200,
     *         description="Successful logout",
     *         @SWG\Schema(
     *             type="object",
     *             @SWG\Property(property="message", type="string", example="Successfully logged out.")
     *         )
     *     ),
     *     @SWG\Response(
     *         response=401,
     *         description="Unauthorized - User not authenticated",
     *         @SWG\Schema(
     *             type="object",
     *             @SWG\Property(property="error", type="string", example="Unauthenticated")
     *         )
     *     ),
     *     @SWG\Response(
     *         response=500,
     *         description="Internal Server Error",
     *         @SWG\Schema(
     *             type="object",
     *             @SWG\Property(property="error", type="string", example="Something went wrong.")
     *         )
     *     ),
     *     security={{"bearer_token": {}}},
     * )
     */
    public function logout()
    {
        auth()->guard('api')->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * @SWG\Post(
     *     tags={"Auth"},
     *     path="/auth/refresh",
     *     summary="Refresh JWT Token",
     *     description="Refreshes the JWT token for the authenticated user using the provided refresh token.
     *                  The new access token and refresh token will be returned in the response.",
     *     operationId="refreshToken",
     *     @SWG\Response(
     *         response=200,
     *         description="Successful login",
     *         @SWG\Schema(
     *             type="object",
     *             @SWG\Property(property="access_token", type="string", example="eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJzdWIiOiIxMjM0NTY3ODkwIiwibmFtZSI6IkpvaG4gRG9lIiwiaWF0IjoxNTE2MjM5MDIyfQ.SflKxwRJSMeKKF2QT4fwpMeJf36POk6yJV_adQssw5c"),
     *             @SWG\Property(property="token_type", type="string", example="bearer"),
     *             @SWG\Property(property="expires_in", type="integer", example=3600)
     *         )
     *     ),
     *     @SWG\Response(
     *         response=500,
     *         description="Internal Server Error",
     *         @SWG\Schema(
     *             type="object",
     *             @SWG\Property(property="error", type="string", example="Something went wrong.")
     *         )
     *     ),
     *     security={{"bearer_token": {}}},
     * )
     */
    public function refresh(Request $request)
    {
        try {
            $newToken = auth()->guard('api')->refresh(true);
        } catch (JWTException $e) {
            if ($e instanceof TokenExpiredException) {
                return response()->json(['error' => $e->getMessage()], Response::HTTP_UNAUTHORIZED);
            } elseif ($e instanceof TokenInvalidException) {
                return response()->json(['error' => $e->getMessage()], Response::HTTP_UNAUTHORIZED);
            } elseif ($e instanceof TokenBlacklistedException) {
                return response()->json(['error' => $e->getMessage()], Response::HTTP_UNAUTHORIZED);
            } else {
                return response()->json(['error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return $this->respondWithToken($newToken);
    }

    /**
     * @SWG\Patch(
     *     tags={"Auth"},
     *     path="/auth/change-password",
     *     summary="Change password",
     *     @SWG\Parameter(
     *         name="body",
     *         in="body",
     *         description="User's current password and new password",
     *         required=true,
     *         @SWG\Schema(
     *             @SWG\Property(property="current_password", type="string", example="123456"),
     *             @SWG\Property(property="new_password", type="string", example="1234567"),
     *             @SWG\Property(property="new_password_confirmation", type="string", example="1234567"),
     *         ),
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="Successful change password",
     *         @SWG\Schema(
     *             type="object",
     *             @SWG\Property(property="message", type="string", example="Password has been successfully changed."),
     *         )
     *     ),
     *     @SWG\Response(
     *         response=400,
     *         description="Bad Request - The current password is incorrect",
     *         @SWG\Schema(
     *             type="object",
     *             @SWG\Property(property="error", type="string", example="The current password is incorrect.")
     *         )
     *     ),
     *     @SWG\Response(
     *         response=401,
     *         description="Unauthorized - User not authenticated",
     *         @SWG\Schema(
     *             type="object",
     *             @SWG\Property(property="error", type="string", example="Unauthenticated")
     *         )
     *     ),
     *     @SWG\Response(
     *         response=422,
     *         description="Unprocessable Entity",
     *         @SWG\Schema(
     *             type="object",
     *             @SWG\Property(property="error", type="object",
     *                 @SWG\Property(property="current_password", type="array", @SWG\Items(type="string"), example={"The current password field is required."}),
     *                 @SWG\Property(property="new_password", type="array", @SWG\Items(type="string"), example={"The new password field is required.",
     *                                              "The new password must be at least 6 characters.",
     *                                              "The new password confirmation does not match.",
     *                                              "The new password must be different from the current password."}),
     *             )
     *         )
     *     ),
     *     @SWG\Response(
     *         response=500,
     *         description="Internal Server Error",
     *         @SWG\Schema(
     *             type="object",
     *             @SWG\Property(property="error", type="string", example="Something went wrong.")
     *         )
     *     ),
     *     security={{"bearer_token": {}}},
     * )
     */
    public function changePassword(Request $request)
    {
        $data = $request->only(['current_password', 'new_password', 'new_password_confirmation']);

        $validator = Validator::make($data, [
            'current_password' => 'bail|required',
            'new_password' => [
                'bail',
                'required',
                'min:6',
                'confirmed',
                function ($attribute, $value, $fail) use ($data) {
                    if ($value === $data['current_password']) {
                        $fail('The new password must be different from the current password.');
                    }
                },
            ],
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        if (!Hash::check($data['current_password'], auth()->guard('api')->user()->password)) {
            return response()->json(['error' => 'The current password is incorrect.'], Response::HTTP_BAD_REQUEST);
        }

        $userId = auth()->guard('api')->user()->id;
        $this->userService->update($userId, ['password' => Hash::make($data['new_password'])]);

        return response()->json(['message' => 'Password has been successfully changed.'], Response::HTTP_OK);
    }

    /**
     * @SWG\Post(
     *    tags={"Auth"},
     *    path="/auth/forgot-password",
     *    summary="Forgot password",
     *    @SWG\Parameter(
     *         name="body",
     *         in="body",
     *         description="User's email",
     *         required=true,
     *         @SWG\Schema(
     *             @SWG\Property(property="email", type="string", example="vu.nguyentat@gmail.com"),
     *         ),
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="Successful change password",
     *         @SWG\Schema(
     *             type="object",
     *             @SWG\Property(property="message", type="string", example="Mật khẩu mới đã được gửi đi."),
     *         )
     *     ),
     *     @SWG\Response(
     *         response=400,
     *         description="Bad Request - Email not found",
     *         @SWG\Schema(
     *             type="object",
     *             @SWG\Property(property="message", type="string", example="This email address is not registered!"),
     *         )
     *     ),
     *     @SWG\Response(
     *         response=422,
     *         description="Unprocessable Entity",
     *         @SWG\Schema(
     *             type="object",
     *             @SWG\Property(property="error", type="object",
     *                 @SWG\Property(property="email", type="array", @SWG\Items(type="string"), example={"The email field is required.", "The email must be a valid email address."}),
     *             )
     *         )
     *     ),
     *     @SWG\Response(
     *         response=500,
     *         description="Internal Server Error",
     *         @SWG\Schema(
     *             type="object",
     *             @SWG\Property(property="error", type="string", example="Something went wrong.")
     *         )
     *     ),
     *     security={{"bearer_token": {}}},
     * )
     */
    public function forgotPassword(Request $request)
    {
        $data = $request->only(['email']);

        $validator = Validator::make($data, [
            'email' => 'bail|email|required',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $result = $this->userService->forgotPassword($data['email']);

        if ($result) {
            return response()->json([
                'message' => 'Mật khẩu mới đã được gửi đến địa chỉ ' . $data['email'] . '. (Vui lòng kiểm tra hộp thư rác).',
            ], Response::HTTP_OK);
        } else if ($result === null) {
            return response()->json([
                'message' => 'Địa chỉ mail này: ' . $data['email'] . ' chưa được dùng đăng ký tài khoản!',
            ], Response::HTTP_BAD_REQUEST);
        }
        else {
            return response()->json([
                'error' => 'Có lỗi xảy ra, vui lòng liên hệ với quản trị viên để khắc phục!',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken(string $token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->guard('api')->factory()->getTTL() * 60
        ]);
    }
}
