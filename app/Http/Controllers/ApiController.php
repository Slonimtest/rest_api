<?php

namespace App\Http\Controllers;

use App\Helpers\Responses;

/**
 * @OA\Info(
 *    title="ApplicationAPI",
 *    version="1.0.0",
 * )
 * @OA\SecurityScheme(
 *     securityScheme="ApiKeyAuth",
 *     type="apiKey",
 *     in="header",
 *     name="X-API-KEY"
 * )
 */
class ApiController extends Controller
{
    use Responses;
}
