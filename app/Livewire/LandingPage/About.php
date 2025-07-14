<?php

namespace App\Livewire\LandingPage;

use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]
class About extends Component
{
    public function render()
    {
        return view('livewire.landing-page.about');
    }
}
