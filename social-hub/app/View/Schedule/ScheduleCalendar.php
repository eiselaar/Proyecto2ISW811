<?php

namespace App\View\Components\Schedule;

use Illuminate\View\Component;
use Illuminate\Support\Collection;

class ScheduleCalendar extends Component
{
    public $schedules;

    public function __construct(Collection $schedules)
    {
        $this->schedules = $schedules;
    }

    public function render()
    {
        return view('components.schedule.calendar');
    }
}