<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Post extends Model
{
    protected $fillable = [
        'user_id',
        'content',
        'media_urls',
        'platforms',
        'status',
        'platform_post_ids',
        'published_at',
    ];

    protected $casts = [
        'media_urls' => 'array',
        'platforms' => 'array',
        'platform_post_ids' => 'array',
        'published_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function queuedPost(): HasOne
    {
        return $this->hasOne(QueuedPost::class);
    }

    public function isPublished(): bool
    {
        return $this->status === 'published';
    }

    public function isQueued(): bool
    {
        return $this->status === 'queued';
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function markAsPublished(): void
    {
        $this->update([
            'status' => 'published',
            'published_at' => now(),
        ]);
    }
}