<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class QueuedPost extends Model
{
    protected $fillable = [
        'post_id',
        'scheduled_for',
        'is_scheduled',
        'attempts',
        'last_error',
    ];

    protected $casts = [
        'scheduled_for' => 'datetime',
        'is_scheduled' => 'boolean',
    ];

    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }

    public function shouldRetry(): bool
    {
        return $this->attempts < 3;
    }

    public function incrementAttempts(): void
    {
        $this->increment('attempts');
    }

    public function isReadyToPublish(): bool
    {
        if ($this->is_scheduled) {
            return $this->scheduled_for <= now();
        }

        return true;
    }
}