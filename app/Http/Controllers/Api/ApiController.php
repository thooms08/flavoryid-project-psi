<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

class ApiController extends Controller
{
    protected function success(string $message = 'OK', $data = null, int $code = 200): JsonResponse
    {
        return response()->json([
            'status' => 'success',
            'message' => $message,
            'data' => $data,
        ], $code);
    }

    protected function error(string $message = 'Error', $errors = null, int $code = 500): JsonResponse
    {
        return response()->json([
            'status' => 'error',
            'message' => $message,
            'data' => $errors,
        ], $code);
    }

    protected function validationError($validator, string $message = 'Validation Error') : JsonResponse
    {
        return $this->error($message, $validator->errors(), 422);
    }
}
