<?php

namespace App\Http\Controllers;

use App\Models\SocialAccount;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use App\Http\Controllers\Controller;  

class SocialController extends Controller
{
    public function __construct()
    {
        $this->middleware("auth");
    }

    public function connect($provider)
    {
        return Socialite::driver($provider)
            ->scopes($this->getScopes($provider))
            ->redirect();
    }

    public function callback($provider)
    {
        try {
            $socialUser = Socialite::driver($provider)->user();
            
            $account = SocialAccount::updateOrCreate(
                [
                    'user_id' => auth()->id(),
                    'provider' => $provider,
                    'provider_id' => $socialUser->getId(),
                ],
                [
                    'provider_token' => $socialUser->token,
                    'provider_refresh_token' => $socialUser->refreshToken,
                    'token_expires_at' => isset($socialUser->expiresIn) ? 
                        now()->addSeconds($socialUser->expiresIn) : null,
                ]
            );

            return redirect()->route('dashboard')
                ->with('success', "Successfully connected with {$provider}!");
        } catch (\Exception $e) {
            return redirect()->route('dashboard')
                ->with('error', "Failed to connect with {$provider}.");
        }
    }

    public function disconnect($provider)
    {
        auth()->user()->socialAccounts()
            ->where('provider', $provider)
            ->delete();

        return redirect()->route('dashboard')
            ->with('success', "Disconnected from {$provider}.");
    }

    private function getScopes($provider)
    {
        return [
            'twitter' => ['tweet.read', 'tweet.write', 'users.read'],
            'reddit' => ['submit', 'identity'],
            'mastodon' => ['write:statuses', 'read:accounts']
        ][$provider] ?? [];
    }
}