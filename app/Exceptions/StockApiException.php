<?php

namespace App\Exceptions;

use Exception;

class StockApiException extends Exception
{
    public function __construct(string $message = "Error fetching stock data", int $code = 500)
    {
        parent::__construct($message, $code);
    }
}
