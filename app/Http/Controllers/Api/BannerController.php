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
     *     description="This API returns Banners.",
     *     @SWG\Parameter(
     *         name="position",
     *         in="query",
     *         description="Position: home",
     *         required=true,
     *         type="string",
     *         enum={"home"}
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="Successful operation",
     *         @SWG\Schema(
     *             type="array",
     *             @SWG\Items(
     *                 type="object",
     *                 @SWG\Property(property="id", type="integer", example="1"),
     *                 @SWG\Property(property="title", type="string", example="Title"),
     *                 @SWG\Property(property="description", type="string", example="Description"),
     *                 @SWG\Property(property="display_type", type="integer", example="1"),
     *                 @SWG\Property(property="to_url", type="string", example="https://google.com.vn"),
     *                 @SWG\Property(property="image", type="string", example="['uploads/banners/67219d4ff3176.png','uploads/banners/67219d4ff3abd.png','uploads/banners/67219d5000502.png']"),
     *                 @SWG\Property(property="position", type="string", example="home_slider_banner"),
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
        $data = $request->only(['position']);

        $banners = $this->bannerService->getBannersByPosition($data['position']);

        return BannerResource::collection($banners);
    }
}
