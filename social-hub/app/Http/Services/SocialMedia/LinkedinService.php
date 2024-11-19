<?php

namespace App\Services\SocialMedia;

use App\Services\SocialMedia\Contracts\SocialMediaProvider;
use Illuminate\Support\Facades\Http;
use Exception;

class LinkedinService implements SocialMediaProvider
{
    protected $token;
    protected $apiUrl = 'https://api.linkedin.com/v2';
    protected $personId;

    public function __construct(string $token)
    {
        $this->token = $token;
        $this->personId = $this->getPersonId();
    }

    public function publish(string $content, array $media = []): array
    {
        try {
            $payload = [
                'author' => "urn:li:person:{$this->personId}",
                'lifecycleState' => 'PUBLISHED',
                'specificContent' => [
                    'com.linkedin.ugc.ShareContent' => [
                        'shareCommentary' => [
                            'text' => $content
                        ],
                        'shareMediaCategory' => 'NONE'
                    ]
                ],
                'visibility' => [
                    'com.linkedin.ugc.MemberNetworkVisibility' => 'PUBLIC'
                ]
            ];

            if (!empty($media)) {
                $mediaIds = $this->uploadMedia($media);
                $payload['specificContent']['com.linkedin.ugc.ShareContent']['shareMediaCategory'] = 'IMAGE';
                $payload['specificContent']['com.linkedin.ugc.ShareContent']['media'] = $mediaIds;
            }

            $response = Http::withToken($this->token)
                ->post("{$this->apiUrl}/ugcPosts", $payload);

            if (!$response->successful()) {
                throw new Exception('LinkedIn API Error: ' . $response->body());
            }

            $postId = $this->extractPostId($response['id']);
            
            return [
                'id' => $postId,
                'url' => "https://www.linkedin.com/feed/update/{$postId}"
            ];
        } catch (Exception $e) {
            throw new Exception('Failed to publish to LinkedIn: ' . $e->getMessage());
        }
    }

    protected function getPersonId(): string
    {
        $response = Http::withToken($this->token)
            ->get("{$this->apiUrl}/me");

        if (!$response->successful()) {
            throw new Exception('Failed to get LinkedIn person ID');
        }

        return $response['id'];
    }

    protected function uploadMedia(array $media): array
    {
        $mediaAssets = [];
        foreach ($media as $mediaItem) {
            // Registrar el asset
            $registerResponse = Http::withToken($this->token)
                ->post("{$this->apiUrl}/assets?action=registerUpload", [
                    'registerUploadRequest' => [
                        'recipes' => ['urn:li:digitalmediaRecipe:feedshare-image'],
                        'owner' => "urn:li:person:{$this->personId}",
                        'serviceRelationships' => [
                            [
                                'relationshipType' => 'OWNER',
                                'identifier' => 'urn:li:userGeneratedContent'
                            ]
                        ]
                    ]
                ]);

            if ($registerResponse->successful()) {
                $uploadUrl = $registerResponse['value']['uploadMechanism']
                    ['com.linkedin.digitalmedia.uploading.MediaUploadHttpRequest']['uploadUrl'];
                $asset = $registerResponse['value']['asset'];

                // Subir el archivo
                Http::withToken($this->token)
                    ->attach('file', file_get_contents($mediaItem), 'image.jpg')
                    ->post($uploadUrl);

                $mediaAssets[] = [
                    'status' => 'READY',
                    'media' => $asset,
                ];
            }
        }

        return $mediaAssets;
    }

    public function delete(string $postId): bool
    {
        try {
            $response = Http::withToken($this->token)
                ->delete("{$this->apiUrl}/ugcPosts/{$postId}");

            return $response->successful();
        } catch (Exception $e) {
            return false;
        }
    }

    public function refreshToken(): bool
    {
        // Implementar lógica de refresh token si es necesario
        return true;
    }

    public function getAccountInfo(): array
    {
        $response = Http::withToken($this->token)
            ->get("{$this->apiUrl}/me");

        return $response->json();
    }

    protected function extractPostId(string $urn): string
    {
        // El URN tiene el formato "urn:li:ugcPost:XXXX"
        return str_replace('urn:li:ugcPost:', '', $urn);
    }
}