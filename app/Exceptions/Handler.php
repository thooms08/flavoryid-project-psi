<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Illuminate\Http\Request;
use Illuminate\Auth\AuthenticationException;

class Handler extends ExceptionHandler
{
    public function register(): void
    {
        // keep default
    }

    /**
     * Redirect ke halaman login yang sesuai berdasarkan URL yang diakses.
     * Owner path → owner.login, selainnya → /login (kasir).
     */
    protected function unauthenticated($request, AuthenticationException $exception)
    {
        if ($request->expectsJson()) {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }

        // Jika akses path /owner/*, redirect ke halaman login owner
        if ($request->is('owner/*') || $request->is('owner')) {
            return redirect()->route('owner.login');
        }

        return redirect()->route('login');
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
