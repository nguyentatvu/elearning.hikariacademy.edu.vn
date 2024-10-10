<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Resources\ContentResource;
use App\Services\LmsContentService;
use Illuminate\Http\Response;

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
}
