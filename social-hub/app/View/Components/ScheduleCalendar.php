<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;
use Illuminate\Support\Collection;

class ScheduleCalendar extends Component
{
    public Collection $schedules;
    public array $days;
    public array $hours;

    public function __construct(Collection $schedules)
    {
        $this->schedules = $schedules;
        $this->days = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
        $this->hours = range(0, 23);
    }

    public function render(): View
    {
        return view('components.schedule-calendar');
    }

    public function hasScheduleAt(int $day, int $hour): bool
    {
        return $this->schedules
            ->where('day_of_week', $day)
            ->where('time', sprintf('%02d:00:00', $hour))
            ->where('is_active', true)
            ->isNotEmpty();
    }

    public function getSchedulesForDay(int $day): Collection
    {
        return $this->schedules
            ->where('day_of_week', $day)
            ->sortBy('time');
    }
}