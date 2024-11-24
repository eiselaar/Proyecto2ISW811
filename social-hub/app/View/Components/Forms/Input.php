<?php

namespace App\View\Components\Forms;

use Illuminate\View\Component;

class Input extends Component
{
    public $type;
    public $name;
    public $value;
    public $label;

    public function __construct($type = 'text', $name = '', $value = '', $label = '')
    {
        $this->type = $type;
        $this->name = $name;
        $this->value = $value;
        $this->label = $label;
    }

    public function render()
    {
        return view('components.forms.input');
    }
}