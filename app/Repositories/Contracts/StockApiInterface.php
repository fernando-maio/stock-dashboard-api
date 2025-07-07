<?php

namespace App\Repositories\Contracts;

use App\DTOs\StockDTO;

interface StockApiInterface
{
    public function getQuote(string $symbol): StockDTO;
}
