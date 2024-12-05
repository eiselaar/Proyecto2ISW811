<?php

namespace App\Services\SocialMedia;

use App\Services\SocialMedia\Contracts\SocialMediaProvider;
use Illuminate\Support\Facades\Http;
use Exception;

/**
 * Servicio para interactuar con la API de LinkedIn
 */
class LinkedinService implements SocialMediaProvider
{
    protected $token;                                  // Token de autenticación
    protected $apiUrl = 'https://api.linkedin.com/v2'; // URL base de la API
    protected $personId;                              // ID del usuario de LinkedIn

    /**
     * Constructor: inicializa el servicio y obtiene el ID de usuario
     */
    public function __construct(string $token)
    {
        $this->token = $token;
        $this->personId = $this->getPersonId();
    }

    /**
     * Publica contenido en LinkedIn
     * @param string $content Texto del post
     * @param array $media Archivos multimedia opcionales
     * @return array ID y URL del post creado
     */
    public function publish(string $content, array $media = []): array
    {
        try {
            // Estructura base del post
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

            // Si hay archivos multimedia, los procesa
            if (!empty($media)) {
                $mediaIds = $this->uploadMedia($media);
                $payload['specificContent']['com.linkedin.ugc.ShareContent']['shareMediaCategory'] = 'IMAGE';
                $payload['specificContent']['com.linkedin.ugc.ShareContent']['media'] = $mediaIds;
            }

            // Realiza la publicación
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

    /**
     * Obtiene el ID del usuario de LinkedIn
     */
    protected function getPersonId(): string
    {
        $response = Http::withToken($this->token)
            ->get("{$this->apiUrl}/me");

        if (!$response->successful()) {
            throw new Exception('Failed to get LinkedIn person ID');
        }

        return $response['id'];
    }

    /**
     * Sube archivos multimedia a LinkedIn
     * @param array $media Array de archivos a subir
     * @return array IDs de los archivos subidos
     */
    protected function uploadMedia(array $media): array
    {
        $mediaAssets = [];
        foreach ($media as $mediaItem) {
            // Registra el asset en LinkedIn
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

                // Sube el archivo
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

    /**
     * Elimina un post de LinkedIn
     */
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

    /**
     * Actualiza el token de acceso
     */
    public function refreshToken(): bool
    {
        // Pendiente de implementar
        return true;
    }

    /**
     * Obtiene información de la cuenta
     */
    public function getAccountInfo(): array
    {
        $response = Http::withToken($this->token)
            ->get("{$this->apiUrl}/me");

        return $response->json();
    }

    /**
     * Extrae el ID del post del URN completo
     */
    protected function extractPostId(string $urn): string
    {
        return str_replace('urn:li:ugcPost:', '', $urn);
    }
}