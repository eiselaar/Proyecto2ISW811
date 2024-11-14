<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PostingSchedule extends Model
{
    protected $fillable = [
        'user_id',
        'day_of_week',
        'posting_time',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}