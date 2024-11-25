<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Require2FA
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        // Si el usuario tiene 2FA habilitado y no está verificado en la sesión actual
        if (auth()->check() && auth()->user()->two_factor_enabled && !session('2fa_verified')) {
            return redirect()->route('2fa.verify');
        }

        return $next($request);
    }
}