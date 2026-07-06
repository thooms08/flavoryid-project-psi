<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Illuminate\Http\Request;

class Handler extends ExceptionHandler
{
    public function register(): void
    {
        // keep default
    }

    public function render($request, Throwable $e)
    {
        if ($request->is('api/*') || $request->wantsJson()) {
            $status = 500;
            $message = 'Server Error';

            if ($e instanceof \Illuminate\Validation\ValidationException) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Validation Error',
                    'data' => $e->errors(),
                ], 422);
            }

            if (method_exists($e, 'getStatusCode')) {
                $status = $e->getStatusCode();
            }

            $message = config('app.debug') ? ($e->getMessage() ?: $message) : $message;

            return response()->json([
                'status' => 'error',
                'message' => $message,
                'data' => null,
            ], $status);
        }

        return parent::render($request, $e);
    }
}
