<?php

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $fillable = [
        'user_id',
        'content',
        'social_networks',
        'status',
        'scheduled_at',
        'published_at',
    ];

    protected $casts = [
        'social_networks' => 'array',
        'scheduled_at' => 'datetime',
        'published_at' => 'datetime',
    ];

    // public function user()
    // {
    //     return $this->belongsTo(User::class);
    // }
}