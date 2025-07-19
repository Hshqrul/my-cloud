<?php

namespace App\Livewire;

use App\Models\Rsvp;
use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('components.layouts.guest')]
class GuestList extends Component
{
    public function render()
    {
        $rsvps = Rsvp::where('notes', '!=', '')->orderBy('created_at', 'desc')->get();

        return view('livewire.guest-list', compact('rsvps'));
    }
}
