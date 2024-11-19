<?php

namespace App\View\Components;

use App\Models\Post;
use Illuminate\View\Component;
use Illuminate\View\View;

class PostCard extends Component
{
    public Post $post;
    public bool $showActions;

    public function __construct(Post $post, bool $showActions = true)
    {
        $this->post = $post;
        $this->showActions = $showActions;
    }

    public function render(): View
    {
        return view('components.post-card');
    }

    public function statusColor(): string
    {
        return match($this->post->status) {
            'published' => 'success',
            'queued' => 'info',
            'pending' => 'warning',
            default => 'secondary'
        };
    }

    public function formattedDate(): string
    {
        if ($this->post->queuedPost && $this->post->queuedPost->scheduled_for) {
            return $this->post->queuedPost->scheduled_for->format('M d, Y H:i');
        }

        return $this->post->created_at->format('M d, Y H:i');
    }
}