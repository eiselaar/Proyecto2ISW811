<?php

namespace App\View\Components\UI;

use Illuminate\View\Component;

class Card extends Component
{
    public $title;
    public $padding;

    public function __construct($title = '', $padding = true)
    {
        $this->title = $title;
        $this->padding = $padding;
    }

    public function render()
    {
        return view('components.ui.card');
    }
}
