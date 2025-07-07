<?php

namespace App\Services\Contracts;

use App\DTOs\StockDTO;
use Illuminate\Support\Collection;

interface StockServiceInterface
{
    public function getPopularStocks(): Collection;
    
    public function getBySymbol(string $symbol): StockDTO;

    public function addFavorite(int $userId, string $symbol): void;
    
    public function removeFavorite(int $userId, string $symbol): void;
    
    public function listFavorites(int $userId): Collection;
}
