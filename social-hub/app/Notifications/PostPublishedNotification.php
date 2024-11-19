<?php

namespace App\Notifications;

use App\Models\Post;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class PostPublishedNotification extends Notification
{
    use Queueable;

    protected $post;

    public function __construct(Post $post)
    {
        $this->post = $post;
    }

    public function via(object $notifiable): array
    {
        // Solo usar el canal de base de datos
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'message' => 'Post published successfully',
            'post_id' => $this->post->id,
            'content' => substr($this->post->content, 0, 100),
            'platforms' => $this->post->platforms,
            'published_at' => $this->post->published_at,
            'type' => 'success',
            'status' => 'published'
        ];
    }
}