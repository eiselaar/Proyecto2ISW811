<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureHasSocialAccount
{
    public function handle(Request $request, Closure $next, string $provider)
    {
        if (!auth()->user()->socialAccounts()
            ->where('provider', $provider)
            ->exists()) {
            return redirect()->route('social.connect', $provider)
                ->with('error', "Please connect your $provider account first.");
        }

        return $next($request);
    }
}