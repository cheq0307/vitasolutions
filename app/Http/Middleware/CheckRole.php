<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Verifica que el usuario tenga el rol requerido.
     * superadmin puede acceder a rutas de admin automáticamente.
     *
     * @param string $role — rol requerido por la ruta (admin, client, superadmin)
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        $user = auth()->user();

        if (!$user) {
            return redirect()->route('login');
        }

        // superadmin tiene acceso a todo
        if ($user->isSuperAdmin()) {
            return $next($request);
        }

        // Verificar rol exacto
        if ($user->role !== $role) {
            abort(403, 'No tienes permiso para acceder a esta sección.');
        }

        return $next($request);
    }
}