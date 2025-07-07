<?php

namespace App\Repositories\External;

use Illuminate\Support\Facades\Http;
use App\DTOs\StockDTO;
use App\Repositories\Contracts\StockApiInterface;
use App\Exceptions\StockApiException;

class AlphaVantageApi implements StockApiInterface
{
    public function getQuote(string $symbol): StockDTO
    {
        $apiKey = config('services.stocks.key');
        $response = Http::get("https://www.alphavantage.co/query", [
            'function' => 'GLOBAL_QUOTE',
            'symbol' => $symbol,
            'apikey' => $apiKey,
        ]);

        if ($response->failed() || !$response->json('Global Quote')) {
            throw new StockApiException("Failed to fetch data for $symbol");
        }

        $data = $response->json('Global Quote');

        return new StockDTO(
            symbol: $data['01. symbol'],
            name: $data['01. symbol'],
            price: (float) $data['05. price']
        );
    }
}
