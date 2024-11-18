<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class QueuedPost extends Pivot
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

    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    public function shouldRetry()
    {
        return $this->attempts < 3;
    }

    public function incrementAttempts()
    {
        $this->attempts++;
        $this->save();
    }
}