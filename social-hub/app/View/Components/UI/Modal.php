<?php

namespace App\View\Components\UI;

use Illuminate\View\Component;

class Modal extends Component
{
    public $show;
    public $maxWidth;

    public function __construct($show = false, $maxWidth = '2xl')
    {
        $this->show = $show;
        $this->maxWidth = $maxWidth;
    }

    public function render()
    {
        return view('components.ui.modal');
    }
}