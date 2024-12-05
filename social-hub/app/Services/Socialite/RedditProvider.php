<?php

namespace App\Services\Socialite;

use Laravel\Socialite\Two\AbstractProvider;
use Laravel\Socialite\Two\ProviderInterface;
use Laravel\Socialite\Two\User;
use Illuminate\Support\Facades\Log;

/**
 * Proveedor de autenticación OAuth2 para Reddit
 */
class RedditProvider extends AbstractProvider implements ProviderInterface
{
    protected $scopes = ['identity', 'submit', 'edit', 'read'];
    protected $scopeSeparator = ' ';

    /**
     * URL de autorización OAuth
     */
    protected function getAuthUrl($state)
    {
        return $this->buildAuthUrlFromBase('https://www.reddit.com/api/v1/authorize', $state);
    }

    /**
     * URL para obtener el token
     */
    protected function getTokenUrl()
    {
        return 'https://www.reddit.com/api/v1/access_token';
    }

    /**
     * Obtiene datos del usuario mediante token
     */
    protected function getUserByToken($token)
    {
        try {
            $response = $this->getHttpClient()->get('https://oauth.reddit.com/api/v1/me', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $token,
                    'User-Agent' => config('app.name') . '/1.0',
                ],
            ]);

            return json_decode($response->getBody(), true);
        } catch (\Exception $e) {
            Log::error('Reddit User Data Request Failed', [
                'error' => $e->getMessage(),
                'token_length' => strlen($token),
            ]);
            throw $e;
        }
    }

    /**
     * Mapea respuesta API a objeto User
     */
    protected function mapUserToObject(array $user)
    {
        return (new User())->setRaw($user)->map([
            'id' => $user['id'],
            'nickname' => $user['name'],
            'name' => $user['name'],
            'email' => null,
            'avatar' => null,
        ]);
    }

    /**
     * Campos adicionales para token
     */
    protected function getTokenFields($code)
    {
        return array_merge(parent::getTokenFields($code), [
            'grant_type' => 'authorization_code',
            'duration' => 'permanent',
        ]);
    }

    /**
     * Obtiene respuesta del token de acceso con logging
     */
    public function getAccessTokenResponse($code)
    {
        Log::info('Attempting to get Reddit Access Token', [
            'code_length' => strlen($code),
            'token_url' => $this->getTokenUrl(),
        ]);

        try {
            $response = $this->getHttpClient()->post($this->getTokenUrl(), [
                'headers' => [
                    'Accept' => 'application/json',
                    'User-Agent' => config('app.name') . '/1.0',
                ],
                'auth' => [$this->clientId, $this->clientSecret],
                'form_params' => $this->getTokenFields($code),
            ]);

            $responseBody = json_decode((string) $response->getBody(), true);
            Log::info('Reddit Token Response Received', [
                'status_code' => $response->getStatusCode(),
                'response_body' => $responseBody,
            ]);

            return $responseBody;
        } catch (\Exception $e) {
            Log::error('Reddit Token Request Failed', [
                'error' => $e->getMessage(),
                'code_length' => strlen($code),
                'token_url' => $this->getTokenUrl(),
                'client_id_length' => strlen($this->clientId),
                'client_secret_length' => strlen($this->clientSecret),
            ]);
            throw $e;
        }
    }
}