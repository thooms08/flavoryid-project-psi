<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware {
    public function handle(Request $request, Closure $next, string ...$roles) {
        if (!Auth::check() || !in_array(Auth::user()->role, $roles)) {
            $role = Auth::check() ? Auth::user()->role : null;
            if ($role === 'owner') {
                return redirect()->route('owner.login')->with('error', 'Anda tidak memiliki akses.');
            }
            return redirect('/login')->with('error', 'Anda tidak memiliki akses.');
        }
        return $next($request);
    }
}