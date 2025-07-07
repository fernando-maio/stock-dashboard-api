<?php

namespace App\Swagger\Schemas;

use OpenApi\Attributes as OA;

/**
 * @OA\Info(
 *     title="Stock Dashboard API",
 *     version="1.0.0",
 *     description="Documentation for the Laravel Stock Dashboard API challenge using Sanctum + external APIs"
 * )
 */

/**
 * @OA\Server(
 *     url=L5_SWAGGER_CONST_HOST,
 *     description="Local server"
 * )
 */

/**
 * @OA\Schema(
 *     schema="Stock",
 *     @OA\Property(property="symbol", type="string", example="AAPL"),
 *     @OA\Property(property="name", type="string", example="Apple Inc."),
 *     @OA\Property(property="price", type="number", format="float", example=189.12)
 * )
 */
class StockSchema
{
    // ...
}
