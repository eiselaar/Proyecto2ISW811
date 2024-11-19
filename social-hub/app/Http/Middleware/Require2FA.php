<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class Require2FA
{
    public function handle(Request $request, Closure $next)
    {
        $user = auth()->user();

        if ($user && $user->two_factor_enabled && !session('2fa_verified')) {
            auth()->logout();
            $request->session()->put('2fa:user_id', $user->id);
            return redirect()->route('2fa.verify');
        }

        return $next($request);
    }
}