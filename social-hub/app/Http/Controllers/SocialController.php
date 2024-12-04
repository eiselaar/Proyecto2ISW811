<?php

namespace App\Http\Controllers;

use App\Models\SocialAccount;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
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
            if ($platform === 'linkedin') {
                return Socialite::driver($platform)
                    ->setScopes(['openid', 'profile', 'email', 'w_member_social'])
                    ->redirect();
            }

            if ($platform === 'mastodon') {
                if (auth()->user()->two_factor_enabled && !session('2fa_verified')) {
                    session([
                        'mastodon_connect_pending' => true,
                        'intended_platform' => $platform
                    ]);
                    return redirect()->route('2fa.verify');
                }

                return Socialite::driver($platform)
                    ->setScopes(['read', 'profile', 'write:statuses'])
                    ->redirect();
            }

            if ($platform === 'reddit') {
                return Socialite::driver($platform)
                    ->setScopes(['identity', 'submit', 'edit', 'read'])
                    ->with(['duration' => 'permanent'])
                    ->redirect();
            }

            return Socialite::driver($platform)->redirect();
        } catch (Exception $e) {
            Log::error('Social redirect error', [
                'platform' => $platform,
                'error' => $e->getMessage()
            ]);

            return redirect()->route('dashboard')
                ->with('error', 'Unable to connect to ' . ucfirst($platform) . '. Please try again.');
        }
    }

    public function callback(string $platform)
    {
        try {
            if (!auth()->check()) {
                throw new Exception('User not authenticated');
            }
    
            $user = Socialite::driver($platform)->stateless()->user();
            $tokenResponse = Socialite::driver($platform)->stateless()->getAccessTokenResponse(request()->get('code'));
    
            $data = [
                'user_id' => auth()->id(),
                'provider' => $platform,
                'provider_id' => $user->getId(),
                'provider_token' => $tokenResponse['access_token'],
                'provider_refresh_token' => $tokenResponse['refresh_token'] ?? null,
                'token_expires_at' => isset($tokenResponse['expires_in']) ?
                    now()->addSeconds($tokenResponse['expires_in']) : null,
            ];
    
            Log::info('Social Account Data:', array_merge($data, ['token_length' => strlen($data['provider_token'])]));
    
            $account = SocialAccount::updateOrCreate(
                [
                    'user_id' => $data['user_id'],
                    'provider' => $data['provider']
                ],
                $data
            );
    
            return redirect()->route('dashboard')
                ->with('status', ucfirst($platform) . ' account connected successfully.');
        } catch (Exception $e) {
            Log::error('OAuth callback error', [
                'platform' => $platform,
                'error' => $e->getMessage()
            ]);
    
            return redirect()->route('dashboard')
                ->with('error', 'Unable to connect to ' . ucfirst($platform) . '. Please try again.');
        }
    }

    public function resume2FACallback()
    {
        if (session('mastodon_callback_pending')) {
            $code = session('mastodon_callback_code');
            $platform = 'mastodon';

            if (!$code) {
                return redirect()->route('dashboard')
                    ->with('error', 'Invalid Mastodon connection attempt.');
            }

            return $this->callback($platform);
        }

        if (session('mastodon_connect_pending')) {
            return $this->redirect('mastodon');
        }

        return redirect()->route('dashboard')
            ->with('error', 'No pending social connection.');
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