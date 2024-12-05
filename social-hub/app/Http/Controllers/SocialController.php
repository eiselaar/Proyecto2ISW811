<?php

namespace App\Http\Controllers;

use App\Models\SocialAccount;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Laravel\Socialite\Facades\Socialite;

// Controlador que maneja la conexión con redes sociales
class SocialController extends Controller
{
    // Plataformas sociales permitidas
    protected $allowedPlatforms = ['linkedin', 'mastodon', 'reddit'];

    // Método para redirigir al usuario a la plataforma social para autenticación
    public function redirect(string $platform)
    {
        // Verifica si la plataforma está permitida
        if (!in_array($platform, $this->allowedPlatforms)) {
            return redirect()->route('dashboard')
                ->with('error', 'Platform not supported.');
        }

        try {
            // Configuración específica para LinkedIn
            if ($platform === 'linkedin') {
                return Socialite::driver($platform)
                    ->setScopes(['openid', 'profile', 'email', 'w_member_social'])
                    ->redirect();
            }

            // Configuración específica para Mastodon
            if ($platform === 'mastodon') {
                // Verifica autenticación de dos factores si está habilitada
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

            // Configuración específica para Reddit
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

    // Método que maneja el callback después de la autenticación
    public function callback(string $platform)
    {
        try {
            // Verifica que el usuario esté autenticado
            if (!auth()->check()) {
                throw new Exception('User not authenticated');
            }

            // Manejo especial para Mastodon con 2FA
            if ($platform === 'mastodon') {
                if (auth()->user()->two_factor_enabled && !session('2fa_verified')) {
                    session([
                        'mastodon_callback_pending' => true,
                        'mastodon_callback_code' => request()->get('code'),
                        'intended_platform' => $platform
                    ]);

                    return redirect()->route('2fa.verify');
                }

                session()->forget([
                    '2fa_verified',
                    'mastodon_callback_pending',
                    'mastodon_callback_code',
                    'intended_platform'
                ]);
            }

            // Obtención de datos específica para Reddit
            if ($platform === 'reddit') {
                Log::info("Platform: $platform");
                $user = Socialite::driver($platform)->stateless()->user();
                Log::info("User Account Data");
                $data = [
                    'user_id' => auth()->id(),
                    'provider' => $platform,
                    'provider_token' => $user->token,
                    'provider_refresh_token' => $user->refreshToken,
                    'token_expires_at' => now()->addMonth(),
                ];
            } else {
                // Obtención de tokens para otras plataformas
                $tokenResponse = Socialite::driver($platform)
                    ->stateless()
                    ->getAccessTokenResponse(request()->get('code'));

                $data = [
                    'user_id' => auth()->id(),
                    'provider' => $platform,
                    'provider_token' => $tokenResponse['access_token'],
                    'provider_refresh_token' => $tokenResponse['refresh_token'] ?? null,
                    'token_expires_at' => isset($tokenResponse['expires_in']) ?
                        now()->addSeconds($tokenResponse['expires_in']) : null,
                ];
            }

            Log::info('Social Account Data:', $data);

            // Actualiza o crea la cuenta social
            $account = SocialAccount::updateOrCreate(
                [
                    'user_id' => $data['user_id'],
                    'provider' => $data['provider']
                ],
                [
                    'provider_token' => $data['provider_token'],
                    'provider_refresh_token' => $data['provider_refresh_token'],
                    'token_expires_at' => $data['token_expires_at'],
                ]
            );

            return redirect()->route('dashboard')
                ->with('status', ucfirst($platform) . ' account connected successfully.');

        } catch (\GuzzleHttp\Exception\ClientException $e) {
            Log::error("Reddit API Error", [
                'response' => $e->getResponse()->getBody()->getContents(),
                'headers' => $e->getRequest()->getHeaders()
            ]);

            return redirect()->route('dashboard')
                ->with('error', 'Unable to connect to ' . ucfirst($platform) . '. Please try again.');
        }
    }

    // Método para reanudar el proceso después de la verificación 2FA
    public function resume2FACallback()
    {
        // Continúa el callback pendiente de Mastodon
        if (session('mastodon_callback_pending')) {
            $code = session('mastodon_callback_code');
            $platform = 'mastodon';

            if (!$code) {
                return redirect()->route('dashboard')
                    ->with('error', 'Invalid Mastodon connection attempt.');
            }

            return $this->callback($platform);
        }

        // Continúa la conexión pendiente con Mastodon
        if (session('mastodon_connect_pending')) {
            return $this->redirect('mastodon');
        }

        return redirect()->route('dashboard')
            ->with('error', 'No pending social connection.');
    }

    // Método para desconectar una cuenta social
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
