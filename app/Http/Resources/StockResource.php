<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class StockResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'symbol' => $this->symbol,
            'name' => $this->name,
            'price' => $this->price,
        ];
    }
}
