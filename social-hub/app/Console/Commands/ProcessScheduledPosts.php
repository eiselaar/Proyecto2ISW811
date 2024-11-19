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
        // Procesar posts programados para una hora específica
        $scheduledPosts = QueuedPost::with('post')
            ->where('is_scheduled', true)
            ->where('scheduled_for', '<=', now())
            ->get();

        foreach ($scheduledPosts as $queuedPost) {
            if ($queuedPost->post->status === 'queued') {
                PublishPost::dispatch($queuedPost->post);
                $queuedPost->delete();
            }
        }

        // Procesar posts en cola según horarios
        $now = now();
        $dayOfWeek = $now->dayOfWeek;
        $currentTime = $now->format('H:i:00');

        $queuedPosts = QueuedPost::with(['post.user.schedules'])
            ->where('is_scheduled', false)
            ->whereHas('post', function ($query) {
                $query->where('status', 'queued');
            })
            ->get();

        foreach ($queuedPosts as $queuedPost) {
            $hasSchedule = $queuedPost->post->user->schedules()
                ->where('day_of_week', $dayOfWeek)
                ->where('time', $currentTime)
                ->where('is_active', true)
                ->exists();

            if ($hasSchedule) {
                PublishPost::dispatch($queuedPost->post);
                $queuedPost->delete();
            }
        }
    }
}