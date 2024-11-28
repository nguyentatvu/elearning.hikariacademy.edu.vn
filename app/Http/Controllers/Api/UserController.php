<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Resources\MyCourseResource;
use App\Http\Resources\UserResource;
use App\Services\ImageService;
use App\Services\UserService;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

/**
 * @SWG\Tag(
 *     name="User",
 *     description="Operations related to users"
 * )
 */
class UserController extends Controller
{
    private $userService;

    public function __construct(UserService $userService)
    {
        parent::__construct();
        $this->userService = $userService;
    }

    /**
     * @SWG\Get(
     *     tags={"User"},
     *     path="/user/detail",
     *     summary="User detail",
     *     @SWG\Response(
     *         response=200,
     *         description="User detail",
     *         @SWG\Schema(
     *             type="object",
     *             @SWG\Property(property="id", type="integer", example=1),
     *             @SWG\Property(property="name", type="string", example="Nguyen Tat Vu"),
     *             @SWG\Property(property="username", type="string", example="nguyentatvu"),
     *             @SWG\Property(property="email", type="string", example="vu.nguyentat@gmail.com"),
     *             @SWG\Property(property="phone", type="string", example="0907168989"),
     *             @SWG\Property(property="image", type="string", example="public/uploads/users/thumbnail/1957.png"),
     *             @SWG\Property(property="address", type="string", example="TPHCM"),
     *             @SWG\Property(property="reward_point", type="integer", example="18"),
     *         ),
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
     *     security={{"bearer_token":{}}}
     * )
     */
    public function detail()
    {
        $userId = auth()->guard('api')->user()->id;

        return new UserResource($this->userService->findById($userId));
    }

    /**
     * @SWG\Post(
     *     tags={"User"},
     *     path="/user",
     *     summary="Update user information",
     *     consumes={"multipart/form-data"},
     *     produces={"application/json"},
     *     @SWG\Parameter(
     *         name="name",
     *         in="formData",
     *         description="User's name",
     *         required=false,
     *         type="string",
     *     ),
     *     @SWG\Parameter(
     *         name="phone",
     *         in="formData",
     *         description="User's phone number",
     *         required=false,
     *         type="string",
     *     ),
     *     @SWG\Parameter(
     *         name="address",
     *         in="formData",
     *         description="User's address",
     *         required=false,
     *         type="string",
     *     ),
     *     @SWG\Parameter(
     *         name="image",
     *         in="formData",
     *         description="User's avatar image",
     *         required=false,
     *         type="file"
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="User updated successfully",
     *         @SWG\Schema(
     *             type="object",
     *             @SWG\Property(property="message", type="object", example="User updated successfully.")
     *         )
     *     ),
     *     @SWG\Response(
     *         response=400,
     *         description="Invalid input",
     *         @SWG\Schema(
     *             type="object",
     *             @SWG\Property(property="error", type="object",
     *                 @SWG\Property(property="name", type="array", @SWG\Items(type="string"), example={"The name may not be greater than 100 characters."}),
     *                 @SWG\Property(property="phone", type="array", @SWG\Items(type="string"), example={"The phone format is invalid."}),
     *                 @SWG\Property(property="address", type="array", @SWG\Items(type="string"), example={"The address may not be greater than 255 characters."}),
     *                 @SWG\Property(property="image", type="array", @SWG\Items(type="string"), example={"The image must be a file of type: png, jpg, jpeg.", "The image may not be greater than 2048 kilobytes."})
     *             )
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
     *     security={{"bearer_token":{}}}
     * )
     */
    public function update(Request $request)
    {
        $user = $request->only(['name', 'phone', 'address', 'image']);
        $validator = Validator::make($user, [
            'name' => 'bail|nullable|string|max:100',
            'phone' => 'bail|nullable|regex:/^[0-9]{10,15}$/',
            'address' => 'bail|nullable|string|max:255',
            'image' => 'bail|nullable|image|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], Response::HTTP_BAD_REQUEST);
        }

        $userId = auth()->guard('api')->user()->id;

        if (isset($user['image'])) {
            Log::info("Update avatar image: ", ['image' => $user['image']]);
            $filename = $this->userService->uploadImageFile($userId, $user['image']);
            $user['image'] = $filename;
        }

        $this->userService->update($userId, $user);

        return response()->json(['message' => 'User updated successfully.'], Response::HTTP_OK);
    }

    /**
     * @SWG\Get(
     *     tags={"User"},
     *     path="/user/my-courses",
     *     summary="Get my courses",
     *     @SWG\Parameter(
     *         name="page",
     *         in="query",
     *         description="Page",
     *         required=false,
     *         type="integer",
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="List of my courses",
     *         @SWG\Schema(
     *             type="array",
     *                 @SWG\Items(
     *                     type="object",
     *                     @SWG\Property(property="series_combo_id", type="integer", example="1"),
     *                     @SWG\Property(property="series_id", type="integer", example="43"),
     *                     @SWG\Property(property="title", type="string", example="Khóa học N5"),
     *                     @SWG\Property(property="purchase_date", type="datetime", example="2024-08-08 12:31:04"),
     *                     @SWG\Property(property="expiry_date", type="datetime", example="2025-03-08 12:31:04"),
     *                     @SWG\Property(property="is_active", type="boolean", example="true"),
     *                     @SWG\Property(property="total_lessons", type="integer", example="100"),
     *                     @SWG\Property(property="completed_lessons", type="integer", example="5")
     *                 ),
     *                 example={
     *                     {
     *                         "series_combo_id": 1,
     *                         "series_id": 43,
     *                         "title": "Khóa học N5",
     *                         "purchase_date": "2024-08-08 12:31:04",
     *                         "expiry_date": "2025-03-08 12:31:04",
     *                         "is_active": true,
     *                         "total_lessons": 100,
     *                         "completed_lessons": 5
     *                     }
     *                 }
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
     *     security={{"bearer_token":{}}}
     * )
     */
    public function getMyCourses()
    {
        $userId = auth()->guard('api')->user()->id;
        $mySeries = $this->userService->getMySeries($userId);
        $mySeries = collect($mySeries);

        return response()->json([
            'data' => MyCourseResource::collection($mySeries)
        ]);
    }
}
