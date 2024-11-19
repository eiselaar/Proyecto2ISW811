<?php

namespace App\Services\SocialMedia;

use App\Services\SocialMedia\Contracts\SocialMediaProvider;
use Illuminate\Support\Facades\Http;
use Exception;
use Illuminate\Http\UploadedFile;

class MastodonService implements SocialMediaProvider
{
    protected $token;
    protected $domain;
    protected $apiUrl;

    public function __construct(string $token, string $domain = null)
    {
        $this->token = $token;
        $this->domain = $domain ?? config('services.mastodon.domain', 'mastodon.social');
        $this->apiUrl = "https://{$this->domain}/api/v1";
    }

    public function publish(string $content, array $media = []): array
    {
        try {
            $params = [
                'status' => $content,
                'visibility' => 'public', // public, unlisted, private, direct
            ];

            // Si hay archivos multimedia, procesarlos primero
            if (!empty($media)) {
                $mediaIds = $this->uploadMedia($media);
                $params['media_ids'] = $mediaIds;
            }

            $response = Http::withToken($this->token)
                ->post("{$this->apiUrl}/statuses", $params);

            if (!$response->successful()) {
                throw new Exception('Mastodon API Error: ' . $response->body());
            }

            $data = $response->json();

            return [
                'id' => $data['id'],
                'url' => $data['url'],
            ];
        } catch (Exception $e) {
            throw new Exception('Failed to publish to Mastodon: ' . $e->getMessage());
        }
    }

    protected function uploadMedia(array $media): array
    {
        $mediaIds = [];

        foreach ($media as $mediaFile) {
            try {
                // Preparar el archivo para subir
                $response = Http::withToken($this->token)
                    ->attach('file', file_get_contents($mediaFile), 'media.jpg')
                    ->post("{$this->apiUrl}/media");

                if (!$response->successful()) {
                    throw new Exception('Failed to upload media: ' . $response->body());
                }

                $mediaIds[] = $response->json()['id'];

            } catch (Exception $e) {
                throw new Exception('Media upload failed: ' . $e->getMessage());
            }
        }

        return $mediaIds;
    }

    public function delete(string $postId): bool
    {
        try {
            $response = Http::withToken($this->token)
                ->delete("{$this->apiUrl}/statuses/{$postId}");

            return $response->successful();
        } catch (Exception $e) {
            return false;
        }
    }

    public function refreshToken(): bool
    {
        // Mastodon no usa refresh tokens, los tokens son permanentes
        return true;
    }

    public function getAccountInfo(): array
    {
        try {
            $response = Http::withToken($this->token)
                ->get("{$this->apiUrl}/accounts/verify_credentials");

            if (!$response->successful()) {
                throw new Exception('Failed to get account info');
            }

            return $response->json();
        } catch (Exception $e) {
            throw new Exception('Failed to get Mastodon account info: ' . $e->getMessage());
        }
    }

    public function getTimeline(string $type = 'home', array $params = []): array
    {
        try {
            $response = Http::withToken($this->token)
                ->get("{$this->apiUrl}/timelines/{$type}", $params);

            if (!$response->successful()) {
                throw new Exception('Failed to get timeline');
            }

            return $response->json();
        } catch (Exception $e) {
            throw new Exception('Failed to get Mastodon timeline: ' . $e->getMessage());
        }
    }

    public function boost(string $postId): array
    {
        try {
            $response = Http::withToken($this->token)
                ->post("{$this->apiUrl}/statuses/{$postId}/reblog");

            if (!$response->successful()) {
                throw new Exception('Failed to boost post');
            }

            return $response->json();
        } catch (Exception $e) {
            throw new Exception('Failed to boost Mastodon post: ' . $e->getMessage());
        }
    }

    public function favorite(string $postId): array
    {
        try {
            $response = Http::withToken($this->token)
                ->post("{$this->apiUrl}/statuses/{$postId}/favourite");

            if (!$response->successful()) {
                throw new Exception('Failed to favorite post');
            }

            return $response->json();
        } catch (Exception $e) {
            throw new Exception('Failed to favorite Mastodon post: ' . $e->getMessage());
        }
    }

    public function search(string $query, string $type = 'statuses'): array
    {
        try {
            $response = Http::withToken($this->token)
                ->get("{$this->apiUrl}/search", [
                    'q' => $query,
                    'type' => $type, // accounts, hashtags, statuses
                ]);

            if (!$response->successful()) {
                throw new Exception('Search failed');
            }

            return $response->json();
        } catch (Exception $e) {
            throw new Exception('Failed to search Mastodon: ' . $e->getMessage());
        }
    }

    protected function validateResponse($response, string $message = 'API Error'): void
    {
        if (!$response->successful()) {
            $errorBody = $response->json();
            $errorMessage = $errorBody['error'] ?? $response->body();
            throw new Exception("Mastodon {$message}: {$errorMessage}");
        }
    }
}
   