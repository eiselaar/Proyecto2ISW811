<?php

namespace App\Http\Controllers;

use App\Models\SocialAccount;
use Exception;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;

class SocialController extends Controller
{
    protected $allowedPlatforms = ['linkedin', 'mastodon', 'reddit'];

    public function redirect(string $platform)
    {
        if (!in_array($platform, $this->allowedPlatforms)) {
            return redirect()->route('dashboard')
                ->with('error', 'Platform not supported.');
        }

        try {
            return Socialite::driver($platform)->redirect();
        } catch (Exception $e) {
            return redirect()->route('dashboard')
                ->with('error', 'Unable to connect to ' . ucfirst($platform) . '. Please try again.');
        }
    }

    public function callback(string $platform)
    {
        try {
            $socialUser = Socialite::driver($platform)->user();

            $account = SocialAccount::updateOrCreate(
                [
                    'user_id' => auth()->id(),
                    'provider' => $platform,
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
                ->with('status', ucfirst($platform) . ' account connected successfully.');

        } catch (Exception $e) {
            return redirect()->route('dashboard')
                ->with('error', 'Unable to connect to ' . ucfirst($platform) . '. Please try again.');
        }
    }

    public function disconnect(string $platform)
    {
        try {
            auth()->user()->socialAccounts()
                ->where('provider', $platform)
                ->delete();

            return redirect()->route('dashboard')
                ->with('status', ucfirst($platform) . ' account disconnected successfully.');

        } catch (Exception $e) {
            return redirect()->route('dashboard')
                ->with('error', 'Unable to disconnect ' . ucfirst($platform) . '. Please try again.');
        }
    }
}