<?php

namespace App\View\Components\Schedule;

use Illuminate\View\Component;

class TimeSlot extends Component
{
    public $time;
    public $selected;

    public function __construct($time, $selected = false)
    {
        $this->time = $time;
        $this->selected = $selected;
    }

    public function render()
    {
        return view('components.schedule.time-slot');
    }
}