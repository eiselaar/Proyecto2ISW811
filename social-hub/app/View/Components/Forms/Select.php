<?php

namespace App\View\Components\Forms;

use Illuminate\View\Component;

class Select extends Component
{
    public $name;
    public $selected;
    
    public function __construct($name = '', $selected = null)
    {
        $this->name = $name;
        $this->selected = $selected;
    }

    public function render()
    {
        return view('components.forms.select');
    }
}