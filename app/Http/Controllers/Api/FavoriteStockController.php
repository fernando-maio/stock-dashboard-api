<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use App\Services\Contracts\StockServiceInterface;
use App\Http\Resources\StockResource;

class FavoriteStockController extends Controller
{
    public function __construct(
        protected StockServiceInterface $stockService
    ) {}

    /**
     * @OA\Get(
     *     path="/api/stocks/favorites",
     *     tags={"Favorites"},
     *     summary="List user's favorite stocks",
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="List of favorite stocks",
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Stock"))
     *     )
     * )
     */
    public function index(): JsonResponse
    {
        $favorites = $this->stockService->listFavorites(Auth::id());
        return response()->json(StockResource::collection($favorites));
    }

    /**
     * @OA\Post(
     *     path="/api/stocks/favorites/{symbol}",
     *     tags={"Favorites"},
     *     summary="Add a stock to favorites",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="symbol",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="string"),
     *         example="AAPL"
     *     ),
     *     @OA\Response(response=200, description="Stock favorited successfully")
     * )
     */
    public function store(string $symbol): JsonResponse
    {
        $this->stockService->addFavorite(Auth::id(), $symbol);
        return response()->json(['message' => "Favorited $symbol"]);
    }

    /**
     * @OA\Delete(
     *     path="/api/stocks/favorites/{symbol}",
     *     tags={"Favorites"},
     *     summary="Remove a stock from favorites",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="symbol",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="string"),
     *         example="AAPL"
     *     ),
     *     @OA\Response(response=200, description="Stock unfavorited")
     * )
     */
    public function destroy(string $symbol): JsonResponse
    {
        $this->stockService->removeFavorite(Auth::id(), $symbol);
        return response()->json(['message' => "Unfavorited $symbol"]);
    }
}
