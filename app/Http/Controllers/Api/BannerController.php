<?php

namespace App\Http\Controllers\Api;

use App\Banner;
use App\Http\Resources\BannerResource;
use Illuminate\Http\Request;
use App\Services\BannerService;

/**
 * @SWG\Tag(
 *     name="Banner",
 *     description="Operations related to banners"
 * )
 */
class BannerController extends Controller
{
    private $bannerService;

    public function __construct(BannerService $bannerService)
    {
        parent::__construct();
        $this->bannerService = $bannerService;
    }

    /**
     * @SWG\Get(
     *     tags={"Banner"},
     *     path="/banner",
     *     summary="Get Banners by group",
     *     description="This API returns Banners by group.
     *           Enum
     *           BannerGroup: LOGIN - 1, HOME - 2, CONTACT - 3, COURSE_DETAIL - 4
     *           BannerDisplayType: SINGLE - 1, SLIDER - 2",
     *     @SWG\Parameter(
     *         name="group",
     *         in="query",
     *         description="Banner Group: 1 - Login, 2 - Home, 3 - Contact, 4 - Course Detail",
     *         required=true,
     *         type="integer",
     *         enum={1, 2, 3, 4}
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="Successful operation",
     *         @SWG\Schema(
     *             type="array",
     *             @SWG\Items(
     *                 type="object",
     *                 @SWG\Property(property="id", type="integer", example="1"),
     *                 @SWG\Property(property="order", type="integer", example="1"),
     *                 @SWG\Property(property="title", type="string", example="Title"),
     *                 @SWG\Property(property="description", type="string", example="Description"),
     *                 @SWG\Property(property="display_type", type="integer", example="1"),
     *                 @SWG\Property(property="group", type="integer", example="1"),
     *                 @SWG\Property(property="image", type="string", example="Link image"),
     *                 @SWG\Property(property="to_url", type="string", example="Url"),
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
    public function getBannerByConditions(Request $request)
    {
        $data = $request->only(['group']);
        $banners = $this->bannerService->getByConditionsWithOrderBy(
            ['group' => $data['group']],
            ['*'],
            'order',
            'asc'
        );

        return BannerResource::collection($banners);
    }
}
