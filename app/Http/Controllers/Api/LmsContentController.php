<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Resources\ContentResource;
use App\LmsContent;
use App\Services\LmsContentService;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

/**
 * @SWG\Tag(
 *     name="Lesson",
 *     description="Operations related to lesson"
 * )
 */
class LmsContentController extends Controller
{
    private $lmsSContentService;

    public function __construct(LmsContentService $lmsSContentService)
    {
        parent::__construct();
        $this->lmsSContentService = $lmsSContentService;
    }

    /**
     * @SWG\Get(
     *     tags={"Lesson"},
     *     path="/lesson",
     *     summary="List of Lessons",
     *     description="Type of the content: 0=Lesson, 1=Vocabulary, 2=Structure, 3=Partial Exercise, 4=Summary Exercise, 5=Test, 6=Kanji, 7=Review Exercise, 8=Lesson Topic, 9=Summary and Introduction, 10=Flashcard",
     *     @SWG\Parameter(
     *         name="seriesComboId",
     *         in="query",
     *         type="integer",
     *         description="Series combo Id",
     *         required=true,
     *         @SWG\Schema(type="integer", example=4)
     *     ),
     *     @SWG\Parameter(
     *         name="seriesId",
     *         in="query",
     *         type="integer",
     *         description="Series Id",
     *         required=true,
     *         type="integer",
     *         @SWG\Schema(type="integer", example=43)
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="Successful operation",
     *         @SWG\Schema(
     *             type="object",
     *             @SWG\Property(property="id", type="integer", example=1),
     *             @SWG\Property(property="title", type="string", example="Bai 1"),
     *             @SWG\Property(property="type", type="integer", example="5", description="Type of the content: 0=Lesson, 1=Vocabulary, 2=Structure, 3=Partial Exercise, 4=Summary Exercise, 5=Test, 6=Kanji, 7=Review Exercise, 8=Lesson Topic, 9=Summary and Introduction, 10=Flashcard"),
     *             @SWG\Property(property="parent_id", type="integer", example=null),
     *             @SWG\Property(property="is_locked", type="boolean", example=true),
     *             @SWG\Property(
     *                 property="children",
     *                 type="array",
     *                 @SWG\Items(
     *                     type="object",
     *                     @SWG\Property(property="id", type="integer"),
     *                     @SWG\Property(property="title", type="string"),
     *                     @SWG\Property(property="type", type="integer"),
     *                     @SWG\Property(property="parent_id", type="integer"),
     *                     @SWG\Property(property="is_locked", type="boolean"),
     *                     @SWG\Property(property="children", type="array", @SWG\Items(type="object"))
     *                 ),
     *                 example={
     *                     {
     *                         "id": 2,
     *                         "title": "Tu vung bai 1",
     *                         "type": 1,
     *                         "parent_id": 1,
     *                         "is_locked": false,
     *                         "children": {}
     *                     },
     *                 }
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
     *         description="Series not found",
     *         @SWG\Schema(
     *             type="object",
     *             @SWG\Property(property="error", type="string", example="Series not found")
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
    public function getContents(Request $request)
    {
        $userId = auth()->guard('api')->user()->id;
        $seriesId = $request->query('seriesId');
        $seriesComboId = $request->query('seriesComboId');
        $series = $this->lmsSContentService->getContents($userId, $seriesComboId, $seriesId);

        if (!$series) {
            return response()->json(['error' => 'Series not found'], Response::HTTP_NOT_FOUND);
        }

        return response()->json($series, Response::HTTP_OK);
    }

    /**
     * @SWG\Get(
     *     tags={"Lesson"},
     *     path="/lesson/{lessonId}",
     *     summary="Lesson detail",
     *     description="Bài học có video: (10268, 4) - Bài tập: (15546, 6) - Bài kiểm tra: (10389, 4) - Flashcard: (17217, 14)",
     *     @SWG\Parameter(
     *         name="lessonId",
     *         in="path",
     *         type="integer",
     *         description="Lesson Id",
     *         required=true,
     *         @SWG\Schema(type="integer", example=15546)
     *     ),
     *     @SWG\Parameter(
     *         name="seriesComboId",
     *         in="query",
     *         type="integer",
     *         description="ID of the series combo",
     *         required=true,
     *         @SWG\Schema(type="integer", example=6)
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="Successful operation",
     *         @SWG\Schema(
     *             type="object",
     *             @SWG\Property(property="id", type="integer", example=1),
     *             @SWG\Property(property="title", type="string", example="Bai 1"),
     *             @SWG\Property(property="file_path", type="string", example="Link video"),
     *             @SWG\Property(property="description", type="string", example="Description"),
     *             @SWG\Property(property="type", type="integer", example="5", description="Type of the content: 0=Lesson, 1=Vocabulary, 2=Structure, 3=Partial Exercise, 4=Summary Exercise, 5=Test, 6=Kanji, 7=Review Exercise, 8=Lesson Topic, 9=Summary and Introduction, 10=Flashcard"),
     *             @SWG\Property(property="is_trial", type="integer", example="1", description="Is trial: 0=No, 1=Yes"),
     *             @SWG\Property(property="parent_id", type="integer", example=null),
     *             @SWG\Property(property="document", type="integer", example="public/uploads/lms/download/1_N3文字語彙_12回目.pdf"),
     *             @SWG\Property(property="content", type="array", example="[]", @SWG\Items(type="object")),
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
    public function getContentById(Request $request)
    {
        $id = $request->lessonId;
        $seriesComboId = $request->seriesComboId;
        $userId = auth()->guard('api')->user()->id;
        $content = $this->lmsSContentService->getContentById($userId, $seriesComboId, $id);

        if (!$content) {
            return response()->json(['error' => 'Lesson not found'], Response::HTTP_NOT_FOUND);
        }

        return new ContentResource($content);
    }

    /**
     * @SWG\Get(
     *     tags={"Lesson"},
     *     path="/lesson/in-progress",
     *     summary="Get lesson in progress",
     *     description="This API returns lesson that the student is currently working on, based on the selected course.",
     *     @SWG\Parameter(
     *         name="seriesComboId",
     *         in="query",
     *         type="integer",
     *         description="ID of the series combo",
     *         required=true,
     *         @SWG\Schema(type="integer", example=4)
     *     ),
     *     @SWG\Parameter(
     *         name="seriesId",
     *         in="query",
     *         type="integer",
     *         description="ID of the course",
     *         required=true,
     *         @SWG\Schema(type="integer", example=43)
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="Successful operation",
     *         @SWG\Schema(
     *             type="object",
     *             @SWG\Property(property="id", type="integer", example=1),
     *             @SWG\Property(property="title", type="string", example="Bai 1"),
     *             @SWG\Property(property="file_path", type="string", example="Link video"),
     *             @SWG\Property(property="description", type="string", example="Description"),
     *             @SWG\Property(property="type", type="integer", example="5", description="Type of the content: 0=Lesson, 1=Vocabulary, 2=Structure, 3=Partial Exercise, 4=Summary Exercise, 5=Test, 6=Kanji, 7=Review Exercise, 8=Lesson Topic, 9=Summary and Introduction, 10=Flashcard"),
     *             @SWG\Property(property="is_trial", type="integer", example="1", description="Is trial: 0=No, 1=Yes"),
     *             @SWG\Property(property="parent_id", type="integer", example=null),
     *             @SWG\Property(property="document", type="string", example="public/uploads/lms/download/1_N3文字語彙_12回目.pdf"),
     *             @SWG\Property(property="content", type="array", example="[]", @SWG\Items(type="object")),
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
    public function getInProgressContent(Request $request)
    {
        $seriesId = $request->seriesId;
        $seriesComboId = $request->seriesComboId;
        $userId = auth()->guard('api')->user()->id;
        $content = $this->lmsSContentService->getInProgressContent($userId, $seriesComboId, $seriesId);

        if (!$content) {
            return response()->json(['error' => 'Lesson not found'], Response::HTTP_NOT_FOUND);
        }

        return new ContentResource($content);
    }

    /**
     * @SWG\Post(
     *     tags={"Lesson"},
     *     path="/lesson/finish",
     *     summary="Finish lesson",
     *     @SWG\Parameter(
     *         name="body",
     *         in="body",
     *         description="Lesson finish",
     *         required=true,
     *         @SWG\Schema(
     *             @SWG\Property(property="lesson_id", type="integer", example=1),
     *             @SWG\Property(property="score_percentage", type="number", example=65.5),
     *         ),
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="Successfully",
     *         @SWG\Schema(
     *             type="object",
     *             @SWG\Property(property="message", type="string", example="Hoàn thành bài học"),
     *         )
     *     ),
     *     @SWG\Response(
     *         response=422,
     *         description="Unprocessable Entity",
     *         @SWG\Schema(
     *             type="object",
     *             @SWG\Property(property="error", type="object",
     *                 @SWG\Property(property="lesson_id", type="array", @SWG\Items(type="integer")),
     *                 @SWG\Property(property="score_percentage", type="array", @SWG\Items(type="integer"))
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
    public function finishContent(Request $request)
    {
        $data = $request->only(['lesson_id', 'score_percentage']);
        $validator = Validator::make($data, [
            'lesson_id' => 'bail|required|exists:lmscontents,id',
            'score_percentage' => 'bail|required|numeric|min:65|max:100',
        ], [
            'lesson_id.exists' => 'Bài học không tồn tại',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $contentType = $this->lmsSContentService->findById($data['lesson_id'])->type;
        $userId = auth()->guard('api')->user()->id;

        if (in_array($contentType, [LmsContent::VOCABULARY, LmsContent::STRUCTURE, LmsContent::KANJI, LmsContent::SUMMARY_AND_INTRODUCTION])) {
            $rewardPoints = getRewardPointRule('learning')['video']['completion_points'];
            $this->lmsSContentService->finishContent($userId, $data['lesson_id'], $rewardPoints, 'video');
        } else if (in_array($contentType, [LmsContent::PARTIAL_EXERCISE, LmsContent::SUMMARY_EXERCISE, LmsContent::REVIEW_EXERCISE])) {
            $pointRule = getRewardPointRule('learning')['exercise']['thresholds'];
            $rewardPoints = 0;

            for ($i = count($pointRule) - 1; $i >= 0; $i--) {
                if ($data['score_percentage'] >= $pointRule[$i]['percentage']) {
                    $rewardPoints = $pointRule[$i]['points'];
                    break;
                }
            }

            $this->lmsSContentService->finishContent($userId, $data['lesson_id'], $rewardPoints, 'exercise_test');
        } else if (in_array($contentType, [LmsContent::TEST])) {
            $pointRule = getRewardPointRule('learning')['test']['thresholds'];
            $rewardedPoint = 0;

            for ($i = 0; $i < count($pointRule); $i++) {
                if ($data['score_percentage'] >= $pointRule[$i]['percentage']) {
                    $rewardedPoint = $pointRule[$i]['points'];
                }
            }

            $this->lmsSContentService->finishContent($userId, $data['lesson_id'], $rewardedPoint, 'exercise_test');
        } else if (in_array($contentType, [LmsContent::FLASHCARD, LmsContent::HANDWRITING, LmsContent::PRONUNCIATION_ASSESSMENT])) {
            $this->lmsSContentService->finishContent($userId, $data['lesson_id'], 0, '');
        }

        return response()->json([
            'message' => 'Hoàn thành bài học'
        ], Response::HTTP_OK);
    }

    // V2

    /**
     * @SWG\Get(
     *     tags={"Lesson"},
     *     path="/v2/lesson",
     *     summary="List of Lessons",
     *     description="Type of the content: 0=Lesson, 1=Vocabulary, 2=Structure, 3=Partial Exercise, 4=Summary Exercise, 5=Test, 6=Kanji, 7=Review Exercise, 8=Lesson Topic, 9=Summary and Introduction, 10=Flashcard",
     *     @SWG\Parameter(
     *         name="seriesComboId",
     *         in="query",
     *         type="integer",
     *         description="Series combo Id",
     *         required=true,
     *         @SWG\Schema(type="integer", example=4)
     *     ),
     *     @SWG\Parameter(
     *         name="seriesId",
     *         in="query",
     *         type="integer",
     *         description="Series Id",
     *         required=true,
     *         type="integer",
     *         @SWG\Schema(type="integer", example=43)
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="Successful operation",
     *         @SWG\Schema(
     *             type="object",
     *             @SWG\Property(property="id", type="integer", example=1),
     *             @SWG\Property(property="title", type="string", example="Bai 1"),
     *             @SWG\Property(property="type", type="integer", example="5", description="Type of the content: 0=Lesson, 1=Vocabulary, 2=Structure, 3=Partial Exercise, 4=Summary Exercise, 5=Test, 6=Kanji, 7=Review Exercise, 8=Lesson Topic, 9=Summary and Introduction, 10=Flashcard"),
     *             @SWG\Property(property="parent_id", type="integer", example=null),
     *             @SWG\Property(property="is_locked", type="boolean", example=true),
     *             @SWG\Property(
     *                 property="children",
     *                 type="array",
     *                 @SWG\Items(
     *                     type="object",
     *                     @SWG\Property(property="id", type="integer"),
     *                     @SWG\Property(property="title", type="string"),
     *                     @SWG\Property(property="type", type="integer"),
     *                     @SWG\Property(property="parent_id", type="integer"),
     *                     @SWG\Property(property="is_locked", type="boolean"),
     *                     @SWG\Property(property="children", type="array", @SWG\Items(type="object"))
     *                 ),
     *                 example={
     *                     {
     *                         "id": 2,
     *                         "title": "Tu vung bai 1",
     *                         "type": 1,
     *                         "parent_id": 1,
     *                         "is_locked": false,
     *                         "children": {}
     *                     },
     *                 }
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
     *         description="Series not found",
     *         @SWG\Schema(
     *             type="object",
     *             @SWG\Property(property="error", type="string", example="Series not found")
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
    public function getContentsV2(Request $request)
    {
        $userId = auth()->guard('api')->user()->id;
        $seriesId = $request->query('seriesId');
        $seriesComboId = $request->query('seriesComboId');
        $series = $this->lmsSContentService->getContentsV2($userId, $seriesComboId, $seriesId);

        if (!$series) {
            return response()->json(['error' => 'Series not found'], Response::HTTP_NOT_FOUND);
        }

        return response()->json($series, Response::HTTP_OK);
    }

    /**
     * @SWG\Get(
     *     tags={"Lesson"},
     *     path="/v2/lesson/{lessonId}",
     *     summary="Lesson detail",
     *     description="Bài học có video: (10268, 4) - Bài tập: (15546, 6) - Bài kiểm tra: (10389, 4) - Flashcard: (17217, 14)",
     *     @SWG\Parameter(
     *         name="lessonId",
     *         in="path",
     *         type="integer",
     *         description="Lesson Id",
     *         required=true,
     *         @SWG\Schema(type="integer", example=15546)
     *     ),
     *     @SWG\Parameter(
     *         name="seriesComboId",
     *         in="query",
     *         type="integer",
     *         description="ID of the series combo",
     *         required=true,
     *         @SWG\Schema(type="integer", example=6)
     *     ),
     *     @SWG\Parameter(
     *         name="seriesId",
     *         in="query",
     *         type="integer",
     *         description="ID of the series",
     *         required=true,
     *         @SWG\Schema(type="integer", example=6)
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="Successful operation",
     *         @SWG\Schema(
     *             type="object",
     *             @SWG\Property(property="id", type="integer", example=1),
     *             @SWG\Property(property="title", type="string", example="Bai 1"),
     *             @SWG\Property(property="file_path", type="string", example="Link video"),
     *             @SWG\Property(property="description", type="string", example="Description"),
     *             @SWG\Property(property="type", type="integer", example="5", description="Type of the content: 0=Lesson, 1=Vocabulary, 2=Structure, 3=Partial Exercise, 4=Summary Exercise, 5=Test, 6=Kanji, 7=Review Exercise, 8=Lesson Topic, 9=Summary and Introduction, 10=Flashcard"),
     *             @SWG\Property(property="is_trial", type="integer", example="1", description="Is trial: 0=No, 1=Yes"),
     *             @SWG\Property(property="parent_id", type="integer", example=null),
     *             @SWG\Property(property="document", type="integer", example="public/uploads/lms/download/1_N3文字語彙_12回目.pdf"),
     *             @SWG\Property(property="content", type="array", example="[]", @SWG\Items(type="object")),
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
    public function getContentByIdV2(Request $request)
    {
        $id = $request->lessonId;
        $seriesComboId = $request->seriesComboId;
        $seriesId = $request->seriesId;
        $user = auth()->guard('api')->user();
        $content = $this->lmsSContentService->getContentById($user->id, $seriesComboId, $id, $seriesId);

        if (!$content) {
            return response()->json(['error' => 'Lesson not found'], Response::HTTP_NOT_FOUND);
        }

        return new ContentResource($content);
    }
}
