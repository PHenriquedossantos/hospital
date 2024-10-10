<?php


namespace App\Services;

use Illuminate\Http\JsonResponse;

class ApiResponse
{
    public static function success($data): JsonResponse
    {
        return response()->json(
            [
                'status_code' => 200,
                'message' => 'success',
                'data' => $data
            ],
            200
        );
    }

    public static function error($message): JsonResponse
    {
        return response()->json(
            [
                'status_code' => 500,
                'message' => $message
            ],
            500
        );
    }

    public static function unauthorized(): JsonResponse
    {
        return response()->json(
            [
                'status_code' => 401,
                'message' =>'unauthorized access'
            ],
            401
        );
    }
}