<?php

namespace App\Notifications;

use App\Models\Post;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class PostScheduledNotification extends Notification
{
    use Queueable;

    protected $post;

    public function __construct(Post $post)
    {
        $this->post = $post;
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'message' => 'Post scheduled successfully',
            'post_id' => $this->post->id,
            'content' => substr($this->post->content, 0, 100),
            'platforms' => $this->post->platforms,
            'scheduled_for' => $this->post->queuedPost->scheduled_for,
            'type' => 'info',
            'status' => 'scheduled'
        ];
    }
}