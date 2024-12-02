<?php

declare(strict_types=1);

namespace App\Services\SocialMedia;

use App\Services\SocialMedia\Contracts\SocialMediaProvider;
use Illuminate\Support\Facades\Http;
use Exception;
use Illuminate\Support\Facades\Log;

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
            $title = $this->extractTitle($content);
            $text = strlen($content) > 300 ? substr($content, 300) : $content;

            $payload = [
                'sr' => $this->subreddit,
                'kind' => empty($media) ? 'self' : 'link',
                'title' => $title,
                'text' => $text,
            ];

            if (!empty($media)) {
                $payload['url'] = $this->uploadMedia($media[0]);
            }

            $response = Http::withToken($this->token)
                ->withHeaders(['User-Agent' => config('app.name') . '/1.0'])
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
            Log::error('Reddit publish error', [
                'error' => $e->getMessage(),
                'subreddit' => $this->subreddit
            ]);
            throw new Exception('Failed to publish to Reddit: ' . $e->getMessage());
        }
    }

    private function extractTitle(string $content): string
    {
        $lines = explode("\n", $content);
        $firstLine = trim($lines[0]);
        
        if (strlen($firstLine) < 20 && isset($lines[1])) {
            $firstLine .= ' - ' . trim($lines[1]);
        }

        return substr($firstLine, 0, 300);
    }

    protected function uploadMedia(string $mediaPath): string
    {
        try {
            $response = Http::withToken($this->token)
                ->withHeaders(['User-Agent' => config('app.name') . '/1.0'])
                ->attach('file', file_get_contents($mediaPath), basename($mediaPath))
                ->post('https://oauth.reddit.com/api/media/asset.json');

            if (!$response->successful()) {
                throw new Exception('Media upload failed: ' . $response->body());
            }

            $data = $response->json();
            return $data['asset']['asset_url'] ?? '';
        } catch (Exception $e) {
            Log::error('Reddit media upload error', [
                'error' => $e->getMessage(),
                'file' => basename($mediaPath)
            ]);
            throw new Exception('Failed to upload media: ' . $e->getMessage());
        }
    }

    public function delete(string $postId): bool
    {
        try {
            $response = Http::withToken($this->token)
                ->withHeaders(['User-Agent' => config('app.name') . '/1.0'])
                ->post("{$this->apiUrl}/del", ['id' => $postId]);

            return $response->successful();
        } catch (Exception $e) {
            Log::error('Reddit delete error', [
                'error' => $e->getMessage(),
                'post_id' => $postId
            ]);
            return false;
        }
    }

    public function refreshToken(): bool
    {
        return true;
    }

    public function getAccountInfo(): array
    {
        try {
            $response = Http::withToken($this->token)
                ->withHeaders(['User-Agent' => config('app.name') . '/1.0'])
                ->get('https://oauth.reddit.com/api/v1/me');

            if (!$response->successful()) {
                throw new Exception('Failed to get account info: ' . $response->body());
            }

            return $response->json();
        } catch (Exception $e) {
            Log::error('Reddit account info error', [
                'error' => $e->getMessage()
            ]);
            return [];
        }
    }
}