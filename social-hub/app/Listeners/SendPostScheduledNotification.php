<?php

namespace App\Listeners;

use App\Events\PostScheduled;
use App\Notifications\PostScheduledNotification;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendPostScheduledNotification implements ShouldQueue
{
    public function handle(PostScheduled $event): void
    {
        $event->post->user->notify(new PostScheduledNotification($event->post));
    }
}