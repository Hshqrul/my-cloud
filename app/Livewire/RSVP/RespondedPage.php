<?php

namespace App\Livewire\RSVP;

use App\Models\Rsvp;
use Flux\Flux;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Locked;

#[Layout('components.layouts.guest')]
class RespondedPage extends Component
{
    #[Locked]
    public $guestName;
    public $rsvp;

    public function mount()
    {
        $this->rsvp = Rsvp::where('name', $this->guestName)->first();
    }

    public function render()
    {
        $rsvps = Rsvp::where('notes', '!=', '')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return view('livewire.rsvp.responded-page', compact('rsvps'));
    }
}
