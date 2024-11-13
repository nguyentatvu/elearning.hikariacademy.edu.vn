<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Resources\SeriesComboResource;
use App\LmsSeries;
use App\Services\LmsSeriesComboService;
use Illuminate\Http\Response;

/**
 * @SWG\Tag(
 *     name="Series Combo",
 *     description="Operations related to series combo"
 * )
 */
class LmsSeriesComboController extends Controller
{
    private $lmsSeriesComboService;

    public function __construct(LmsSeriesComboService $lmsSeriesComboService)
    {
        parent::__construct();
        $this->lmsSeriesComboService = $lmsSeriesComboService;
    }

    /**
     * @SWG\Get(
     *     tags={"Series Combo"},
     *     path="/series-combo",
     *     summary="List of Series",
     *     @SWG\Parameter(
     *         name="type",
     *         in="query",
     *         description="Course type (course or exam). If not provided, will list all types.",
     *         required=false,
     *         type="string",
     *         enum={"course", "exam"}
     *     ),
     *     @SWG\Parameter(
     *         name="keyword",
     *         in="query",
     *         description="Search keyword",
     *         required=false,
     *         type="string",
     *     ),
     *     @SWG\Parameter(
     *         name="page",
     *         in="query",
     *         description="Page",
     *         required=false,
     *         type="integer",
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="List of Courses",
     *         @SWG\Schema(
     *             type="object",
     *             @SWG\Property(
     *                 property="data",
     *                 type="array",
     *                 @SWG\Items(
     *                     type="object",
     *                     @SWG\Property(property="id", type="integer"),
     *                     @SWG\Property(property="title", type="string"),
     *                     @SWG\Property(property="code", type="string"),
     *                     @SWG\Property(property="slug", type="string"),
     *                     @SWG\Property(property="cost", type="integer"),
     *                     @SWG\Property(property="selloff", type="string"),
     *                     @SWG\Property(property="short_description", type="string"),
     *                     @SWG\Property(property="description", type="string"),
     *                     @SWG\Property(property="image", type="string"),
     *                     @SWG\Property(property="type", type="integer"),
     *                     @SWG\Property(property="time", type="integer"),
     *                     @SWG\Property(property="series", type="array", @SWG\Items(type="object")),
     *                     @SWG\Property(property="timefrom", type="string", format="date-time"),
     *                     @SWG\Property(property="timeto", type="string", format="date-time"),
     *                     @SWG\Property(property="total_lessons", type="integer"),
     *                     @SWG\Property(property="trial_lessons", type="integer"),
     *                     @SWG\Property(property="payment", type="integer"),
     *                 ),
     *                 example={
     *                     {
     *                         "id": 1,
     *                         "title": "Tên Khóa học",
     *                         "code": "CB02",
     *                         "slug": "ten-khoa-hoc",
     *                         "cost": 1000000,
     *                         "selloff": 900000,
     *                         "short_description": "<p>Kh&oacute;a học&nbsp;tiếng Nhật N5+N4 của trung t&acirc;m Nhật ngữ Hikari Academy sẽ gi&uacute;p bạn chinh phục hiệu quả v&agrave; tiết kiệm chi ph&iacute; nhất.</p>\r\n",
     *                         "description": "<p>Kh&oacute;a học&nbsp;tiếng Nhật N5+N4 của trung t&acirc;m Nhật ngữ Hikari Academy sẽ gi&uacute;p bạn chinh phục hiệu quả v&agrave; tiết kiệm chi ph&iacute; nhất.</p>\r\n",
     *                         "image": "public/uploads/lms/combo/cb45.jpg",
     *                         "type": 0,
     *                         "time": 2,
     *                         "total_items": 2,
     *                         "series": {
     *                             {
     *                                 "id": 43,
     *                                 "title": "Khóa học N5",
     *                                 "short_description": "Khóa học N5",
     *                                 "image": "public/uploads/lms/series/n5.jpg",
     *                                 "total_lessons": 296,
     *                                 "teachers": {
     *                                     {
     *                                         "id": 65,
     *                                         "name": "GV. Do Linh"
     *                                     }
     *                                 }
     *                             },
     *                         },
     *                         "timefrom": "2022-02-13 22:00:00",
     *                         "timeto": "2022-02-14 21:59:59",
     *                         "total_lessons": 100,
     *                         "trial_lessons": 0,
     *                         "payment": 0
     *                     },
     *                 }
     *             ),
     *             @SWG\Property(
     *                 property="links",
     *                 type="object",
     *                 @SWG\Property(property="first", type="string", example="http://hikari.test:8091/api/lms-series-combo/courses?page=1"),
     *                 @SWG\Property(property="last", type="string", example="http://hikari.test:8091/api/lms-series-combo/courses?page=1"),
     *                 @SWG\Property(property="prev", type="string", example=null),
     *                 @SWG\Property(property="next", type="string", example=null)
     *             ),
     *             @SWG\Property(
     *                 property="meta",
     *                 type="object",
     *                 @SWG\Property(property="current_page", type="integer", example=1),
     *                 @SWG\Property(property="from", type="integer", example=1),
     *                 @SWG\Property(property="last_page", type="integer", example=1),
     *                 @SWG\Property(property="path", type="string", example="http://hikari.test:8091/api/lms-series-combo/courses"),
     *                 @SWG\Property(property="per_page", type="integer", example=10),
     *                 @SWG\Property(property="to", type="integer", example=8),
     *                 @SWG\Property(property="total", type="integer", example=8)
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
    public function getSeriesCombo(Request $request)
    {
        $temp = $request->query('type');

        if ($temp == 'exam') {
            $type = LmsSeries::EXAM;
        } else if ($temp == 'course') {
            $type = LmsSeries::COURSE;
        }

        $filters = [
            'type' => $type ?? null,
            'keyword' => $request->query('keyword'),
            'page' => $request->query('page')
        ];

        $userId = auth()->guard('api')->user()->id;
        $courses = $this->lmsSeriesComboService->getSeriesCombo($userId, $filters);

        if (isset($filters['page']) || isset($filters['keyword'])) {
            return SeriesComboResource::collection($courses);
        }

        return response()->json([
            "data" => SeriesComboResource::collection($courses)
        ],  Response::HTTP_OK);
    }
}
