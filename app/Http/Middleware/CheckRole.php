<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckRole
{
    public function handle(Request $request, Closure $next, string $role): mixed
    {
        if (!auth()->check() || auth()->user()->role !== $role) {
            abort(403, 'Sin acceso.');
        }
        return $next($request);
    }
}