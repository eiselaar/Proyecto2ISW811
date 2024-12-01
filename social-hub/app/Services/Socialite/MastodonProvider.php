<?php

namespace App\Services\Socialite;

use Laravel\Socialite\Two\AbstractProvider;
use Laravel\Socialite\Two\User;
use GuzzleHttp\Client;

class MastodonProvider extends AbstractProvider
{
    protected $scopeSeparator = ' ';
    protected $scopes = ['read', 'profile', 'write:statuses'];

    protected function getAuthUrl($state)
    {
        return $this->buildAuthUrlFromBase($this->getInstanceUrl() . '/oauth/authorize', $state);
    }

    protected function getTokenUrl()
    {
        return $this->getInstanceUrl() . '/oauth/token';
    }

    protected function getUserByToken($token)
    {
        $response = $this->getHttpClient()->get($this->getInstanceUrl() . '/api/v1/accounts/verify_credentials', [
            'headers' => [
                'Authorization' => 'Bearer ' . $token,
            ],
        ]);

        return json_decode($response->getBody(), true);
    }

    protected function mapUserToObject(array $user)
    {
        return (new User())->setRaw($user)->map([
            'id' => $user['id'],
            'nickname' => $user['username'],
            'name' => $user['display_name'],
            'email' => $user['email'] ?? null,
            'avatar' => $user['avatar'] ?? null,
        ]);
    }

    protected function getHttpClient()
    {
        if (is_null($this->httpClient)) {
            $this->httpClient = new Client([
                'base_uri' => $this->getInstanceUrl(),
                'verify' => false,
                'http_errors' => false,
            ]);
        }

        return $this->httpClient;
    }

    protected function getInstanceUrl()
    {
        return rtrim($this->config['instance_url'] ?? 'https://mastodon.social', '/');
    }

    protected function getCodeFields($state = null)
    {
        $fields = parent::getCodeFields($state);
        return array_merge($fields, [
            'scope' => $this->formatScopes($this->getScopes(), $this->scopeSeparator),
        ]);
    }
}