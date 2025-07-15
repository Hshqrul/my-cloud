<?php

namespace App\Livewire\RSVP;

use App\Models\Rsvp;
use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('layouts.guest')]
class RespondUser extends Component
{
    public string $name = '';
    public $attendence ;
    public ?int $no_of_pax = null;

    protected function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255', 'unique:rsvps,name'],
            'attendence' => ['required', 'boolean'],
            'no_of_pax' => ['required_if:attendence,true'],
        ];
    }

    protected function messages(): array
    {
        return [
            'name.unique' => 'The name has already been taken. Please enter a different name.',
            'no_of_pax.required_if' => 'The number of guests is required.',
        ]; 
    }

    public function updatedAttendence()
    {
        // Reset pax if not attending
        if ($this->attendence == false) {
            $this->no_of_pax = null;
        }
    }

    public function saveRsvp(): void
    {
        $this->validate();

        $rsvp = Rsvp::create([
            'name' => $this->name,
            'attendence' => $this->attendence,
            'no_of_pax' => $this->no_of_pax,
        ]);

        $this->reset();

        $this->dispatch('rsvp-saved');

        redirect()->route('tq_rsvp', ['guestName' => $rsvp->name]);
    }

    public function render()
    {
        return view('livewire.rsvp.respond-user');
    }
}
