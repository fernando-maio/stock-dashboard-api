<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Services\Contracts\StockServiceInterface;
use App\Http\Resources\StockResource;

class StockController extends Controller
{
    public function __construct(
        protected StockServiceInterface $stockService
    ) {}

    /**
     * @OA\Get(
     *     path="/api/stocks",
     *     tags={"Stocks"},
     *     summary="List popular stocks",
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Successful response",
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Stock"))
     *     )
     * )
     */
    public function index(): JsonResponse
    {
        $stocks = $this->stockService->getPopularStocks();

        return response()->json(StockResource::collection($stocks));
    }

    /**
     * @OA\Get(
     *     path="/api/stocks/{symbol}",
     *     tags={"Stocks"},
     *     summary="Get stock by symbol",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="symbol",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="string"),
     *         example="AAPL"
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful response",
     *         @OA\JsonContent(ref="#/components/schemas/Stock")
     *     ),
     *     @OA\Response(response=404, description="Stock not found")
     * )
     */
    public function show(string $symbol): JsonResponse
    {
        // Normalize the symbol to uppercase
        $symbol = strtoupper($symbol);

        try {
            $stock = $this->stockService->getBySymbol($symbol);

            return response()->json(new StockResource($stock));
        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'Unable to fetch stock data',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
