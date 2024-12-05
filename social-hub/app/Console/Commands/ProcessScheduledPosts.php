<?php

namespace App\Console\Commands;

use App\Jobs\PublishPost;
use App\Models\QueuedPost;
use Illuminate\Console\Command;

class ProcessScheduledPosts extends Command
{
    protected $signature = 'posts:process-scheduled';
    protected $description = 'Process scheduled posts';

    public function handle()
    {
        // Procesar posts programados para hora específica
        $scheduledPosts = QueuedPost::with('post')
            ->where('is_scheduled', true)
            ->where('scheduled_for', '<=', now())
            ->get();

        foreach ($scheduledPosts as $queuedPost) {
            Log::info('Processing scheduled post', [
                'post_id' => $queuedPost->post_id,
                'scheduled_for' => $queuedPost->scheduled_for
            ]);

            PublishPost::dispatch($queuedPost->post);
            $queuedPost->delete();
        }

        // Procesar posts en cola general
        $queuedPosts = Post::where('status', 'queued')
            ->whereHas('queuedPost', function($query) {
                $query->where('is_scheduled', false);
            })
            ->get();

        foreach ($queuedPosts as $post) {
            Log::info('Processing queued post', [
                'post_id' => $post->id
            ]);

            PublishPost::dispatch($post);
            $post->queuedPost->delete();
        }
    
    }
}