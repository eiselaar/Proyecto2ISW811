<?php

namespace App\View\Components\Forms;

use Illuminate\View\Component;

class Button extends Component
{
    public $type;
    public $variant;

    public function __construct($type = 'button', $variant = 'primary')
    {
        $this->type = $type;
        $this->variant = $variant;
    }

    public function render()
    {
        return view('components.forms.button');
    }
}