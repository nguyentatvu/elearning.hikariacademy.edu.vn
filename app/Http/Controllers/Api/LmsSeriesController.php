<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Api\Controller;
use App\Http\Resources\SeriesDetailResource;
use App\Services\LmsSeriesService;
use Illuminate\Http\Response;

/**
 * @SWG\Tag(
 *     name="Series",
 *     description="Operations related to course and exam"
 * )
 */
class LmsSeriesController extends Controller
{
    private $lmsSeriesService;

    public function __construct(LmsSeriesService $lmsSeriesService)
    {
        parent::__construct();
        $this->lmsSeriesService = $lmsSeriesService;
    }

    /**
     * @SWG\Get(
     *     tags={"Series"},
     *     path="/series/{seriesId}",
     *     summary="Get series detail by id",
     *     description="This API returns series detail by id.",
     *     @SWG\Parameter(
     *         name="seriesId",
     *         in="path",
     *         type="integer",
     *         description="ID of the series",
     *         required=true,
     *         @SWG\Schema(type="integer", example=43)
     *     ),
     *     @SWG\Parameter(
     *         name="seriesComboId",
     *         in="query",
     *         type="integer",
     *         description="ID of the series combo",
     *         required=true,
     *         @SWG\Schema(type="integer", example=4)
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="Successful operation",
     *         @SWG\Schema(
     *             type="object",
     *             @SWG\Property(property="id", type="integer", example="1"),
     *             @SWG\Property(property="title", type="string", example="Title 1"),
     *             @SWG\Property(property="cost", type="integer", example="100000"),
     *             @SWG\Property(property="description", type="string", example="Description"),
     *             @SWG\Property(property="image", type="string", example="public/uploads/lms/combo/n5.jpg"),
     *             @SWG\Property(property="time", type="integer", example="6"),
     *             @SWG\Property(property="total_lessons", type="integer", example="100"),
     *             @SWG\Property(property="payment", type="integer", example="1"),
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
    public function getSeriesDetail(Request $request, int $seriesId)
    {
        $userId = auth()->guard('api')->user()->id;
        $seriesComboId = $request->get('seriesComboId', null);
        $detail = $this->lmsSeriesService->getSeriesDetail($userId, $seriesComboId, $seriesId);

        if (!$detail) {
            return response()->json(['error' => 'Series not found'], Response::HTTP_NOT_FOUND);
        }

        return new SeriesDetailResource($detail);
    }
}
