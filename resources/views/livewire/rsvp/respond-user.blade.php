@section('title', ' · Répondez S\'il Vous Plaît')
<div class="isolate md:isolate-auto flex flex-col gap-4">
    <div class="absolute inset-x-0 -top-40 -z-10 transform-gpu overflow-hidden blur-3xl sm:-top-80" aria-hidden="true">
        <div class="relative left-1/2 -z-10 aspect-[1155/678] w-[36.125rem] max-w-none -translate-x-1/2 rotate-[30deg] bg-gradient-to-tr from-[#ff80b5] to-[#9089fc] opacity-30 sm:left-[calc(50%-40rem)] sm:w-[72.1875rem]"
            style="clip-path: polygon(74.1% 44.1%, 100% 61.6%, 97.5% 26.9%, 85.5% 0.1%, 80.7% 2%, 72.5% 32.5%, 60.2% 62.4%, 52.4% 68.1%, 47.5% 58.3%, 45.2% 34.5%, 27.5% 76.7%, 0.1% 64.9%, 17.9% 100%, 27.6% 76.8%, 76.1% 97.7%, 74.1% 44.1%)">
        </div>
    </div>

    <div class="text-center">
        <div class="mb-5">
            <flux:separator variant="sublte" />
        </div>
        <flux:subheading>{{ __('Please enter your name and let us know if you can attend') }}</flux:subheading>
    </div>

    <form wire:submit="saveRsvp" class="flex flex-col gap-5">
        <flux:input wire:model="name" label="Full Name" type="text" required autofocus autocomplete="name"
            placeholder="Your Name" class="w-full" />

        <flux:radio.group wire:model.live="attendence" variant="segmented" label="Will you attend?" class="w-full">
            <flux:radio :value="1" :checked="$attendence == true" label="Yes" icon="check-badge" />
            <flux:radio :value="0" :checked="$attendence == false" label="No" icon="moon" />
        </flux:radio.group>

        <flux:radio.group wire:model="no_of_pax" label="Number of Guests" class="w-full">
            <flux:radio value="1" :disabled="$attendence == false" label="1" />
            <flux:radio value="2" :disabled="$attendence == false" label="2" />
        </flux:radio.group>

        <flux:textarea wire:model="notes" label="Wish for bride & groom" placeholder="Your wish for bride & groom"
            class="w-full" />

        <div class="flex items-center justify-end">
            <flux:button type="submit" variant="primary" class="w-full">
                {{ __('Submit') }}
            </flux:button>
            <x-action-message class="ml-3 text-sm text-gray-600 dark:text-gray-300" on="rsvp-saved">
                {{ __('Saved.') }}
            </x-action-message>
        </div>
    </form>
    <div class="space-x-1 text-center text-sm text-zinc-600 dark:text-zinc-400">
        {{ __('Already responded? View the guest book!') }}
        <flux:link :href="route('guest_list')" wire:navigate>{{ __('here...') }}</flux:link>
    </div>
</div>
