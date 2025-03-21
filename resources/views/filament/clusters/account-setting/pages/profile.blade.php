<x-filament-panels::page>
    <form wire:submit.prevent="update" class="space-y-6">

        {{ $this->form }}

        <div class="text-right">
            <x-filament::button type="submit" form="submit" class="align-right">
                Update
            </x-filament::button>
        </div>
    </form>
</x-filament-panels::page>
