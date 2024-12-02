<?php

namespace App\Services\Socialite;

use Laravel\Socialite\Two\AbstractProvider;
use Laravel\Socialite\Two\ProviderInterface;
use Laravel\Socialite\Two\User;

class RedditProvider extends AbstractProvider implements ProviderInterface
{
    protected $scopes = ['identity', 'submit', 'edit', 'read'];
    protected $scopeSeparator = ' ';

    protected function getAuthUrl($state)
    {
        return $this->buildAuthUrlFromBase('https://www.reddit.com/api/v1/authorize', $state);
    }

    protected function getTokenUrl()
    {
        return 'https://www.reddit.com/api/v1/access_token';
    }

    protected function getUserByToken($token)
    {
        $response = $this->getHttpClient()->get('https://oauth.reddit.com/api/v1/me', [
            'headers' => [
                'Authorization' => 'Bearer ' . $token,
                'User-Agent' => config('app.name') . '/1.0',
            ],
        ]);

        return json_decode($response->getBody(), true);
    }

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

    protected function getTokenFields($code)
    {
        return array_merge(parent::getTokenFields($code), [
            'grant_type' => 'authorization_code',
            'duration' => 'permanent',
        ]);
    }
}