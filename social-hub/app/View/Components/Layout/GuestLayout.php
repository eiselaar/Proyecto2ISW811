<?php

namespace App\View\Components\Layout;

use Illuminate\View\Component;

class GuestLayout extends Component
{
    public function render()
    {
        return view('components.layout.guest');
    }
}