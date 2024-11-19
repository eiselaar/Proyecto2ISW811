<?php

namespace App\Listeners;

use App\Events\PostPublished;
use App\Notifications\PostPublishedNotification;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendPostPublishedNotification implements ShouldQueue
{
    public function handle(PostPublished $event): void
    {
        $event->post->user->notify(new PostPublishedNotification($event->post));
    }
}