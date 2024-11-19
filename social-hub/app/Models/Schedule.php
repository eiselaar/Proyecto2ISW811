<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Schedule extends Model
{
    protected $fillable = [
        'user_id',
        'day_of_week',
        'time',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'time' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getNextOccurrence()
    {
        $now = now();
        $scheduleTime = today()
            ->setTimeFromTimeString($this->time->format('H:i:s'));
        
        while ($scheduleTime->dayOfWeek !== $this->day_of_week || $scheduleTime <= $now) {
            $scheduleTime->addDay();
        }

        return $scheduleTime;
    }
}