<?php

namespace App\Exceptions;

use Illuminate\Http\JsonResponse;
use Throwable;

class Handler
{
    public function render($request, Throwable $exception): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => $exception->getMessage(),
        ], 500);
    }
}
