<?php

namespace App\Jobs;

use App\Models\Post;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;


class PublishPost implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $post;

    public function __construct(Post $post)
    {
        $this->post = $post;
    }

    public function handle()
    {
        foreach ($this->post->platforms as $platform) {
            try {
                $service = $this->getServiceForPlatform($platform);
                $result = $service->publish($this->post->content);
                
                // Guardar el ID del post en la plataforma
                $platformIds = $this->post->platform_post_ids ?? [];
                $platformIds[$platform] = $result['id'];
                $this->post->platform_post_ids = $platformIds;
                
            } catch (\Exception $e) {
                // Manejar el error
                \Log::error("Error publishing to $platform: " . $e->getMessage());
            }
        }

        $this->post->status = 'published';
        $this->post->published_at = now();
        $this->post->save();
    }

    protected function getServiceForPlatform($platform)
    {
        $serviceClass = "App\\Services\\SocialMedia\\" . ucfirst($platform) . "Service";
        return new $serviceClass($this->post->user->socialAccounts()
            ->where('provider', $platform)
            ->first()
            ->provider_token);
    }
}