<?php

namespace App\Helpers;

use Illuminate\Http\JsonResponse;

class ApiResponse
{
    public static function success($data = [], $message = null): JsonResponse
    {
        return response()->json([
            'message' => $message,
            'data' => $data
        ]);
    }

    public static function error($message = 'Error', $code = 400): JsonResponse
    {
        return response()->json([
            'message' => $message
        ], $code);
    }
}
