<?php

namespace App\View\Components\Schedule;

use Illuminate\View\Component;

class WeekView extends Component
{
    public $schedules;
    public $currentWeek;

    public function __construct($schedules, $currentWeek = null)
    {
        $this->schedules = $schedules;
        $this->currentWeek = $currentWeek ?? now();
    }

    public function render()
    {
        return view('components.schedule.week-view');
    }
}