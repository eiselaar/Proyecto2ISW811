<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

// Middleware que redirige a usuarios ya autenticados
class RedirectIfAuthenticated
{
    // Método que maneja la redirección de usuarios autenticados
    public function handle(Request $request, Closure $next, string ...$guards)
    {
        // Si no se especifican guards, usa el guard por defecto
        $guards = empty($guards) ? [null] : $guards;

        // Verifica cada guard especificado
        foreach ($guards as $guard) {
            // Si el usuario está autenticado en algún guard
            if (Auth::guard($guard)->check()) {
                // Redirige al dashboard
                return redirect('/dashboard');
            }
        }

        // Si no está autenticado, continúa con la siguiente acción
        return $next($request);
    }
}