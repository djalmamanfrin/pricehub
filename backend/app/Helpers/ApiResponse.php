<?php

namespace App\Helpers;

use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class ApiResponse
{
    public static function success($data = [], $message = null): JsonResponse
    {
        return response()->json([
            'message' => $message,
            'data' => $data
        ]);
    }

    public static function error(string $message, Response $code): JsonResponse
    {
        return response()->json([
            'message' => $message
        ], $code);
    }
}
