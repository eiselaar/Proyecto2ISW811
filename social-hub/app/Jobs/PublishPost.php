<?php

namespace App\Jobs;

use App\Models\Post;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;
use Exception;

class PublishPost implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $post;

    public function __construct(Post $post)
    {
        $this->post = $post;
        Log::info("Construct");
        Log::info($post);
    }

    public function handle()
    {
        try {

            foreach ($this->post->platforms as $platform) {
                match ($platform) {
                    'linkedin' => $this->publishToLinkedIn(),
                    default => null
                };
            }

            $this->post->update([
                'status' => 'published',
                'published_at' => now()
            ]);

        } catch (Exception $e) {
            Log::info("catch handel");
            Log::info($e);
            $this->post->update(['status' => 'failed']);
            throw $e;
        }
    }

    protected function publishToLinkedIn()
    {
        try {
            $socialAccount = $this->post->user->socialAccounts()
                ->where('provider', 'linkedin')
                ->first();

            Log::info('LinkedIn account details', [
                'found_account' => !!$socialAccount,
                'has_token' => !empty($socialAccount?->provider_token),
                'token_value' => $socialAccount?->provider_token,
                'token_expires_at' => $socialAccount?->token_expires_at
            ]);

            if (!$socialAccount) {
                throw new Exception('LinkedIn account not found');
            }
            if (empty($socialAccount->provider_token)) {
                throw new Exception('LinkedIn token is empty');
            }

            $client = new Client();
            $authorResponse = $client->get('https://api.linkedin.com/v2/userinfo', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $socialAccount->provider_token
                ]
            ]);

            $authorData = json_decode($authorResponse->getBody(), true);
            $authorId = $authorData['sub'];

            // Log de la petición que se va a hacer
            Log::info('LinkedIn request details', [
                'token' => $socialAccount->provider_token,
                'author_id' => $authorId,
                'headers' => [
                    'Authorization' => 'Bearer ' . $socialAccount->provider_token,
                    'Content-Type' => 'application/json',
                    'X-Restli-Protocol-Version' => '2.0.0'
                ]
            ]);

            $response = $client->post('https://api.linkedin.com/v2/ugcPosts', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $socialAccount->provider_token,
                    'Content-Type' => 'application/json',
                    'X-Restli-Protocol-Version' => '2.0.0'
                ],
                'json' => [
                    'author' => "urn:li:person:{$authorId}",
                    'lifecycleState' => 'PUBLISHED',
                    'specificContent' => [
                        'com.linkedin.ugc.ShareContent' => [
                            'shareCommentary' => [
                                'text' => $this->post->content
                            ],
                            'shareMediaCategory' => 'NONE'
                        ]
                    ],
                    'visibility' => [
                        'com.linkedin.ugc.MemberNetworkVisibility' => 'PUBLIC'
                    ]
                ]
            ]);

            return true;
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            Log::error('LinkedIn API Error', [
                'response_status' => $e->getResponse()->getStatusCode(),
                'response_body' => $e->getResponse()->getBody()->getContents(),
                'token_used' => $socialAccount?->provider_token ?? 'no token'
            ]);
            throw $e;
        }
    }
}