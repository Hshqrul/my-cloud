<?php

namespace App\Livewire\RSVP;

use App\Models\Rsvp;
use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('components.layouts.app')]
class RespondUser extends Component
{
    public string $name = '';
    public string $notes = '';
    public $attendence;
    public ?int $no_of_pax = null;

    protected function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255', 'unique:rsvps,name'],
            'attendence' => ['required', 'boolean'],
            'no_of_pax' => ['required_if:attendence,true'],
            'notes' => ['required', 'string', 'max:255'],
        ];
    }

    protected function messages(): array
    {
        return [
            'name.unique' => 'The name has already been taken. Please enter a different name.',
            'no_of_pax.required_if' => 'The number of guests is required.',
            'notes.required' => 'Please enter a wish for bride & groom.',
        ];
    }

    public function updatedAttendence()
    {
        if ($this->attendence == false) {
            $this->no_of_pax = null;
            $this->notes = 'Sorry I can\'t make it, but wishing you both ....';
        } else {
            $this->notes = '';
        }
    }

    public function saveRsvp(): void
    {
        $this->validate();

        $rsvp = Rsvp::create([
            'name' => $this->name,
            'attendence' => $this->attendence,
            'no_of_pax' => $this->no_of_pax,
            'notes' => $this->notes,
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
