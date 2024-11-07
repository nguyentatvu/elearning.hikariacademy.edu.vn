<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\App;

// use Illuminate\Foundation\Auth\Access\AuthorizesResources;

/**
 * @SWG\Swagger(
 *     basePath="/api",
 *     schemes={"http", "https"},
 *     @SWG\Info(
 *         title="API MOBILE",
 *         version="1.0.0"
 *     )
 * )
 * @SWG\SecurityScheme(
 *      securityDefinition="bearer_token",
 *      type="apiKey",
 *      in="header",
 *      name="Authorization"
 * )
 */
class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function __construct()
    {
        App::setLocale('vi');
    }
}
