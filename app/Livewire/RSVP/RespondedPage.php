<?php

namespace App\Livewire\RSVP;

use App\Models\Rsvp;
use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('layouts.guest')]
class RespondedPage extends Component
{
    public $guestName;
    public $rsvp;

    public function mount()
    {
        $this->rsvp = Rsvp::where('name', $this->guestName)->first();
    }

    public function render()
    {
        return view('livewire.rsvp.responded-page');
    }
}
