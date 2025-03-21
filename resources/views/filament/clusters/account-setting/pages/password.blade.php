<x-filament-panels::page>
    <form wire:submit.prevent="submit" class="space-y-6">

        {{ $this->form }}

        <div class="text-right">
            <x-filament::button type="submit" form="submit" class="align-right">
                {{ __('filament-breezy::default.profile.password.submit.label') }}
            </x-filament::button>
        </div>
    </form>
</x-filament-panels::page>
