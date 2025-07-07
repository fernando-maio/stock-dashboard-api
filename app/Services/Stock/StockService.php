<?php

namespace App\Services\Stock;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use App\DTOs\StockDTO;
use App\Enums\StockSymbolEnum;
use App\Services\Contracts\StockServiceInterface;
use App\Repositories\Contracts\StockApiInterface;
use App\Repositories\External\TwelveDataApi;
use App\Exceptions\StockApiException;
use App\Models\UserFavoriteStock;

class StockService implements StockServiceInterface
{
    public function __construct(
        protected StockApiInterface $primaryApi,
        protected TwelveDataApi $fallbackApi
    ) {}

    public function getPopularStocks(): Collection
    {
        return collect(StockSymbolEnum::values())
            ->map(fn(string $symbol) => Cache::remember(
                "stock_$symbol", now()->addMinutes(5),
                fn() => $this->getQuoteWithFallback($symbol)
            ));
    }

    public function getBySymbol(string $symbol): StockDTO
    {
        return Cache::remember(
            "stock_{$symbol}", now()->addMinutes(5),
            fn() => $this->getQuoteWithFallback($symbol)
        );
    }

    public function addFavorite(int $userId, string $symbol): void
    {
        UserFavoriteStock::firstOrCreate([
            'user_id' => $userId,
            'symbol' => strtoupper($symbol),
        ]);
    }

    public function removeFavorite(int $userId, string $symbol): void
    {
        UserFavoriteStock::where('user_id', $userId)
            ->where('symbol', strtoupper($symbol))
            ->delete();
    }

    public function listFavorites(int $userId): Collection
    {
        $symbols = UserFavoriteStock::where('user_id', $userId)
            ->pluck('symbol');

        return $symbols->map(fn(string $symbol) =>
            Cache::remember("stock_{$symbol}", now()->addMinutes(5),
                fn() => $this->getQuoteWithFallback($symbol)
            )
        );
    }

    protected function getQuoteWithFallback(string $symbol): StockDTO
    {
        try {
            return $this->primaryApi->getQuote($symbol);
        } catch (StockApiException $e) {
            // fallback attempt
            return $this->fallbackApi->getQuote($symbol);
        }
    }
}

