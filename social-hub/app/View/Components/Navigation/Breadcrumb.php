<?php

namespace App\View\Components\Navigation;

use Illuminate\View\Component;

class Breadcrumb extends Component
{
    public $items;

    public function __construct(array $items = [])
    {
        $this->items = $items;
    }

    public function render()
    {
        return view('components.navigation.breadcrumb');
    }
}