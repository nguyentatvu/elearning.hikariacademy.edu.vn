<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Services\LmsTestService;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

/**
 * @SWG\Tag(
 *     name="Test",
 *     description="Operations related to test"
 * )
 */
class LmsTestController extends Controller
{
    private $lmsTestService;

    public function __construct(LmsTestService $lmsTestService)
    {
        parent::__construct();
        $this->lmsTestService = $lmsTestService;
    }


    /**
     * @SWG\Post(
     *     tags={"Test"},
     *     path="/test/{lessonId}/evaluate-test",
     *     summary="Calculate points and Evaluate for the test",
     *     description="This API calculates the points for a user's submitted answers for a specific lesson.",
     *     @SWG\Parameter(
     *         name="lessonId",
     *         in="path",
     *         description="ID of the lesson",
     *         required=true,
     *         type="integer",
     *         @SWG\Schema(type="integer", example=10389)
     *     ),
     *     @SWG\Parameter(
     *         name="submitted_answers",
     *         in="body",
     *         description="Submitted answers for the test",
     *         required=true,
     *         @SWG\Schema(
     *             type="object",
     *             @SWG\Property(
     *                 property="submitted_answers",
     *                 type="object",
     *                 additionalProperties={
     *                     "type": "integer"
     *                 },
     *                 example={
     *                     "1": 3,
     *                     "2": 1,
     *                     "3": 1
     *                 }
     *             )
     *         )
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="Successfully evaluate",
     *         @SWG\Schema(
     *             type="object",
     *             @SWG\Property(property="total_point", type="integer", example=100),
     *             @SWG\Property(property="max_point", type="integer", example=100),
     *             @SWG\Property(property="total_correct_answers", type="integer", example="40"),
     *             @SWG\Property(property="total_question", type="integer", example="73"),
     *             @SWG\Property(property="is_passed", type="boolean", example="true"),
     *             @SWG\Property(
     *                 property="correct_answers",
     *                 type="array",
     *                 @SWG\Items(
     *                     type="object",
     *                     @SWG\Property(property="question", type="string", example="1"),
     *                     @SWG\Property(property="answer", type="string", example="3")
     *                 )
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
     *         response=404,
     *         description="Lesson not found",
     *         @SWG\Schema(
     *             type="object",
     *             @SWG\Property(property="error", type="string", example="Lesson not found")
     *         )
     *     ),
     *     @SWG\Response(
     *         response=422,
     *         description="Unprocessable Entity",
     *         @SWG\Schema(
     *             type="object",
     *             @SWG\Property(property="error", type="object",
     *                 @SWG\Property(
     *                     property="submitted_answers",
     *                     type="array",
     *                     @SWG\Items(type="string"),
     *                     example={
     *                         "The submitted answers field is required.",
     *                         "The submitted answers must be an array."
     *                     }
     *                 ),
     *                 @SWG\Property(
     *                     property="submitted_answers.3",
     *                     description="Error for the submitted_answers where key = 3",
     *                     type="array",
     *                     @SWG\Items(type="string"),
     *                     example={
     *                         "The selected submitted_answers.3 is invalid."
     *                     }
     *                 )
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
     *     security={{"bearer_token":{}}}
     * )
     */
    public function evaluateTest(Request $request, int $lessonId)
    {
        $submittedAnswers = $request->input('submitted_answers');
        $validator = Validator::make(
            [
                'submitted_answers' => $submittedAnswers
            ],
            [
                'submitted_answers' => 'required|array',
                'submitted_answers.*' => 'in:1,2,3,4'
            ]
        );

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $result = $this->lmsTestService->evaluateTest($lessonId, $submittedAnswers);

        if (!$result) {
            return response()->json([
                'message' => 'Lesson not found'
            ], Response::HTTP_NOT_FOUND);
        }

        return response()->json($result, Response::HTTP_OK);
    }
}
