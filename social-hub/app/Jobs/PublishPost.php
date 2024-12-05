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
           Log::info('Starting to process queued post', [
               'post_id' => $this->post->id,
               'platforms' => $this->post->platforms,
               'status' => $this->post->status
           ]);
    
           foreach ($this->post->platforms as $platform) {
               Log::info('Processing platform', [
                   'platform' => $platform,
                   'post_id' => $this->post->id
               ]);
    
               match ($platform) {
                   'linkedin' => $this->publishToLinkedIn(),
                   'mastodon' => $this->publishToMastodon(),
                   'reddit' => $this->publishToReddit(),
                   default => null
               };
    
               Log::info('Platform processed successfully', [
                   'platform' => $platform,
                   'post_id' => $this->post->id
               ]);
           }
    
           Log::info('Updating post status to published', [
               'post_id' => $this->post->id
           ]);
    
           $this->post->update([
               'status' => 'published',
               'published_at' => now()
           ]);
    
           Log::info('Post processed and published successfully', [
               'post_id' => $this->post->id,
               'final_status' => 'published'
           ]);
    
       } catch (Exception $e) {
           Log::error('Failed to process post', [
               'post_id' => $this->post->id,
               'error_message' => $e->getMessage(),
               'error_trace' => $e->getTraceAsString()
           ]);
    
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


    protected function publishToMastodon()
{
    try {
        $socialAccount = $this->post->user->socialAccounts()
            ->where('provider', 'mastodon')
            ->first();

        Log::info('Mastodon account details', [
            'found_account' => !!$socialAccount,
            'has_token' => !empty($socialAccount?->provider_token),
            'token_value' => $socialAccount?->provider_token,
            'token_expires_at' => $socialAccount?->token_expires_at
        ]);

        if (!$socialAccount) {
            throw new Exception('Mastodon account not found');
        }

        if (empty($socialAccount->provider_token)) {
            throw new Exception('Mastodon token is empty');
        }

        $client = new Client();
        
        $response = $client->post('https://mastodon.social/api/v1/statuses', [
            'headers' => [
                'Authorization' => 'Bearer ' . $socialAccount->provider_token,
                'Content-Type' => 'application/json'
            ],
            'json' => [
                'status' => $this->post->content,
                'visibility' => 'public'
            ]
        ]);

        return true;
    } catch (\GuzzleHttp\Exception\ClientException $e) {
        Log::error('Mastodon API Error', [
            'response_status' => $e->getResponse()->getStatusCode(),
            'response_body' => $e->getResponse()->getBody()->getContents(),
            'token_used' => $socialAccount?->provider_token ?? 'no token'
        ]);
        throw $e;
    }
}

protected function publishToReddit()
{
    try {
        $socialAccount = $this->post->user->socialAccounts()
            ->where('provider', 'reddit')
            ->first();

        Log::info('Reddit account details', [
            'found_account' => !!$socialAccount,
            'has_token' => !empty($socialAccount?->provider_token),
            'token_value' => $socialAccount?->provider_token,
            'token_expires_at' => $socialAccount?->token_expires_at
        ]);

        if (!$socialAccount) {
            throw new Exception('Reddit account not found');
        }

        if (empty($socialAccount->provider_token)) {
            throw new Exception('Reddit token is empty');
        }

        $client = new Client();

        $response = $client->post('https://oauth.reddit.com/api/submit', [
            'headers' => [
                'Authorization' => 'Bearer ' . $socialAccount->provider_token,
                'Content-Type' => 'application/x-www-form-urlencoded',
                'User-Agent' => 'SocialHub/1.0'
            ],
            'form_params' => [
                'sr' => 'test', // subreddit donde publicar
                'kind' => 'self',
                'title' => substr($this->post->content, 0, 300),
                'text' => $this->post->content
            ]
        ]);

        return true;
    } catch (\GuzzleHttp\Exception\ClientException $e) {
        Log::error('Reddit API Error', [
            'response_status' => $e->getResponse()->getStatusCode(),
            'response_body' => $e->getResponse()->getBody()->getContents(),
            'token_used' => $socialAccount?->provider_token ?? 'no token'
        ]);
        throw $e;
    }
}


}