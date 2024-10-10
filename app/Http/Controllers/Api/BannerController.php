<?php

namespace App\Http\Controllers\Api;

use App\Banner;
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
     *     summary="List of banners",
     *     @SWG\Response(
     *         response=200,
     *         description="List of banners",
     *         @SWG\Schema(
     *             type="object",
     *             @SWG\Property(property="id", type="integer", example="1"),
     *             @SWG\Property(property="title", type="string", example="Banner A"),
     *             @SWG\Property(property="description", type="string", example="Description of Banner A"),
     *             @SWG\Property(property="type", type="string", example="Type of Banner A"),
     *             @SWG\Property(property="image", type="string", example="Link image of Banner A"),
     *             @SWG\Property(property="created_at", type="datetime", example="2025-01-01 00:00:00"),
     *             @SWG\Property(property="updated_at", type="datetime", example="2025-01-01 00:00:00"),
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
    public function getAll()
    {
        $banners = Banner::all();
        return response()->json($banners);
    }
}
