<?php
namespace App\View\Components\UI;

use Illuminate\View\Component;

class Badge extends Component
{
    public $type;
    
    public function __construct($type = 'default')
    {
        $this->type = $type;
    }

    public function render()
    {
        return view('components.ui.badge');
    }
}