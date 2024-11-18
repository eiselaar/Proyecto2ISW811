<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getNextScheduledTime()
    {
        $now = now();
        $scheduleTime = today()
            ->setTime(
                (int) $this->time->format('H'),
                (int) $this->time->format('i')
            );
        
        while ($scheduleTime->dayOfWeek !== $this->day_of_week || $scheduleTime->isPast()) {
            $scheduleTime->addDay();
        }

        return $scheduleTime;
    }
}