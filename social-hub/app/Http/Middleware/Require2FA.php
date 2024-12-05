<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

// Middleware que verifica la autenticación de dos factores (2FA)
class Require2FA
{
   /**
    * Handle an incoming request.
    */
   public function handle(Request $request, Closure $next)
   {
       // Verifica tres condiciones:
       // 1. El usuario está autenticado (auth()->check())
       // 2. El usuario tiene 2FA activado (two_factor_enabled)
       // 3. No se ha verificado 2FA en la sesión actual (!session('2fa_verified'))
       if (auth()->check() && auth()->user()->two_factor_enabled && !session('2fa_verified')) {
           // Si cumple las condiciones, redirige a la página de verificación 2FA
           return redirect()->route('2fa.verify');
       }

       // Si no requiere verificación 2FA, continúa con la siguiente acción
       return $next($request);
   }
}