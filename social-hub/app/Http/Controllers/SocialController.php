<?php

namespace App\Http\Controllers;

use App\Models\SocialAccount;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;

class SocialController extends Controller
{
    public function redirect($provider)
    {
        return Socialite::driver($provider)
            ->scopes($this->getScopes($provider))
            ->redirect();
    }

    public function callback($provider)
    {
        try {
            $socialUser = Socialite::driver($provider)->user();
            
            auth()->user()->socialAccounts()->updateOrCreate(
                [
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

            return redirect()->route('social.accounts')
                ->with('success', "Connected with $provider successfully!");
        } catch (\Exception $e) {
            return redirect()->route('social.accounts')
                ->with('error', "Failed to connect with $provider.");
        }
    }

    private function getScopes($provider): array
    {
        return [
            'twitter' => ['tweet.read', 'tweet.write', 'users.read'],
            'reddit' => ['identity', 'submit', 'edit'],
            'mastodon' => ['read', 'write'],
        ][$provider] ?? [];
    }
}