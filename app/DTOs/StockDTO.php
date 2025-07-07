<?php

namespace App\DTOs;

class StockDTO
{
    public function __construct(
        public string $symbol,
        public string $name,
        public float $price
    ) {}
}
