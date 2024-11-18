<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\User;

class Require2FA
{
    public function handle(Request $request, Closure $next)
    {
        $user = auth()->user();

        if ($user && $user->two_factor_enabled && !session('2fa_verified')) {
            // Guardar la URL intentada para redireccionar después de la verificación
            session(['intended_url' => $request->url()]);
            return redirect()->route('2fa.verify');
        }

        return $next($request);
    }
}