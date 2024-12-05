<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

// Middleware que verifica si el usuario tiene una cuenta social específica conectada
class EnsureHasSocialAccount
{
    // Método que maneja la verificación de la cuenta social
    public function handle(Request $request, Closure $next, string $provider)
    {
        // Verifica si el usuario autenticado NO tiene una cuenta social del proveedor especificado
        if (!auth()->user()->socialAccounts()
            ->where('provider', $provider)
            ->exists()) {
            // Si no tiene la cuenta conectada, redirige a la página de conexión
            return redirect()->route('social.connect', $provider)
                ->with('error', "Please connect your $provider account first.");
        }

        // Si tiene la cuenta conectada, continúa con la siguiente acción
        return $next($request);
    }
}