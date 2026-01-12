<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Dashboard extends Component
{
    public $heading;

    public function __construct($heading = 'Dashboard')
    {
        $this->heading = $heading;
    }

    public function render()
    {
        return view('components.dashboard');
    }
}
