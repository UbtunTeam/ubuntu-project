<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;


/**
 * @OA\Info(
 *    title="Smart-Agro",
 *    version="1.0.0",
 *    description="Smart agro, a place where farmers and concumer meet",
 *    @OA\Contact(
 *      email="support@smartagro.com.ng"
 *    ),
 *    @OA\License(
 *       name="Apache 2.0",
 *       url="http://www.apache.org/licenses/LICENSE-2.0.html"
 *    ),
 * )
 *
 * /**
 * @OA\SecurityScheme(
 *     scheme="Bearer",
 *     securityScheme="Bearer",
 *     type="apiKey",
 *     in="header",
 *     name="Authorization",
 * )
 */
class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
}
