<?php

namespace App\Repositories\External;

use Illuminate\Support\Facades\Http;
use App\DTOs\StockDTO;
use App\Repositories\Contracts\StockApiInterface;
use App\Exceptions\StockApiException;

class TwelveDataApi implements StockApiInterface
{
    public function getQuote(string $symbol): StockDTO
    {
        $apiKey = config('services.twelvedata.key');

        $response = Http::get("https://api.twelvedata.com/quote", [
            'symbol' => $symbol,
            'apikey' => $apiKey,
        ]);

        if ($response->failed()) {
            throw new StockApiException("HTTP error from TwelveData for {$symbol}");
        }

        $data = $response->json();

        if (isset($data['status']) && $data['status'] === 'error') {
            throw new StockApiException("TwelveData error: " . ($data['message'] ?? 'Unknown'));
        }

        if (empty($data['symbol']) || empty($data['close'])) {
            throw new StockApiException("TwelveData returned empty data for {$symbol}");
        }

        return new StockDTO(
            symbol: $data['symbol'],
            name: $data['name'] ?? $data['symbol'],
            price: (float) $data['close']
        );
    }
}
