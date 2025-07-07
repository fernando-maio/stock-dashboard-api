<?php

namespace App\Enums;

enum StockSymbolEnum: string
{
    case AAPL = 'AAPL';
    case GOOGL = 'GOOGL';
    case MSFT = 'MSFT';
    case AMZN = 'AMZN';
    case TSLA = 'TSLA';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
