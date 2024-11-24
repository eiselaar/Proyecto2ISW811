<?php

namespace App\View\Components\UI;

use Illuminate\View\Component;

class Tab extends Component
{
    public $active;

    public function __construct($active = '')
    {
        $this->active = $active;
    }

    public function render()
    {
        return view('components.ui.tab');
    }
}