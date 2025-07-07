<?php

namespace App\Exceptions;

use Exception;

class UnauthorizedException extends Exception
{
    public function render($request)
    {
        return response()->json([
            'message' => 'Invalid credentials',
        ], 401);
    }
}
