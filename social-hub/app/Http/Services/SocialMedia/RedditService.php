<?php

namespace App\Services\SocialMedia;

use App\Services\SocialMedia\Contracts\SocialMediaProvider;
use Illuminate\Support\Facades\Http;
use Exception;

class RedditService implements SocialMediaProvider
{
    protected $token;
    protected $apiUrl = 'https://oauth.reddit.com/api';
    protected $subreddit;

    public function __construct(string $token, string $subreddit = 'test')
    {
        $this->token = $token;
        $this->subreddit = $subreddit;
    }

    public function publish(string $content, array $media = []): array
    {
        try {
            $payload = [
                'sr' => $this->subreddit,
                'kind' => empty($media) ? 'self' : 'link',
                'title' => substr($content, 0, 300),
                'text' => $content,
            ];

            if (!empty($media)) {
                $payload['url'] = $this->uploadMedia($media[0]);
            }

            $response = Http::withToken($this->token)
                ->post("{$this->apiUrl}/submit", $payload);

            if (!$response->successful()) {
                throw new Exception('Reddit API Error: ' . $response->body());
            }

            $data = $response->json();
            return [
                'id' => $data['data']['id'],
                'url' => $data['data']['url'],
            ];
        } catch (Exception $e) {
            throw new Exception('Failed to publish to Reddit: ' . $e->getMessage());
        }
    }

    protected function uploadMedia(string $mediaPath): string
    {
        // Implementar lógica de subida de medios
        return '';
    }

    public function delete(string $postId): bool
    {
        try {
            $response = Http::withToken($this->token)
                ->post("{$this->apiUrl}/del", ['id' => $postId]);

            return $response->successful();
        } catch (Exception $e) {
            return false;
        }
    }

    public function refreshToken(): bool
    {
        // Implementar lógica de refresh token
        return true;
    }

    public function getAccountInfo(): array
    {
        $response = Http::withToken($this->token)
            ->get('https://oauth.reddit.com/api/v1/me');

        return $response->json();
    }
}