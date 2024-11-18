<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\SocialAccount;
use App\Models\QueuedPost;
class Post extends Model
{
    protected $fillable = [
        'user_id',
        'content',
        'media_urls',
        'platforms',
        'published_at',
        'status',
        'platform_post_ids',
    ];

    protected $casts = [
        'media_urls' => 'array',
        'platforms' => 'array',
        'platform_post_ids' => 'array',
        'published_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function queuedPost()
    {
        return $this->hasOne(QueuedPost::class);
    }

    public function isQueued()
    {
        return $this->status === 'queued';
    }

    public function isPublished()
    {
        return $this->status === 'published';
    }

    public function isDraft()
    {
        return $this->status === 'draft';
    }
    
}