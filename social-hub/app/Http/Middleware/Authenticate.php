<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

// Middleware que verifica la autenticación de usuarios
class Authenticate extends Middleware
{
    // Método que maneja la redirección cuando un usuario no está autenticado
    protected function redirectTo(Request $request): ?string
    {
        // Si la petición espera JSON, retorna null (para APIs)
        // Si no, redirige al usuario a la ruta de login
        return $request->expectsJson() ? null : route('login');
    }
}