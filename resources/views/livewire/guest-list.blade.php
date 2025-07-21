@section('title', ' · Guest List')
<div>
    <div class="text-center">
        <div class="mb-5">
            <flux:separator variant="sublte" />
        </div>
        <flux:heading>{{ __('All wishes for bride & groom') }}</flux:heading>
        <div class="mb-5 mt-5">
            <flux:separator variant="sublte" />
        </div>
    </div>
    <div class="flex flex-wrap justify-center gap-4 overflow-y-auto max-h-[400px]">
        @forelse ($rsvps as $item)
            <div class="flex flex-col justify-between w-full sm:w-[47%] md:w-[30%]">
                <flux:callout :color="$item->attendence ? 'indigo' : 'amber'"
                    class="text-indigo-600 dark:text-indigo-100 text-sm leading-relaxed">
                    <flux:callout.text>
                        <div class="flex flex-col space-y-3">
                            <div>
                                <svg class="w-4 h-4 mb-2 text-blue-400" fill="currentColor" viewBox="0 0 24 24">
                                    <path
                                        d="M7.17 3.1C4.57 5.08 3 8.03 3 11v7a3 3 0 0 0 3 3h3v-7H7v-2c0-1.61.79-3.09 2.11-4.03l-1.94-1.87zm9 0C13.57 5.08 12 8.03 12 11v7a3 3 0 0 0 3 3h3v-7h-2v-2c0-1.61.79-3.09 2.11-4.03l-1.94-1.87z" />
                                </svg>
                                <p class="italic">"{{ $item->notes }}"</p>
                            </div>

                            <div class="flex items-center mt-2">
                                <div class="text-xs space-y-1">
                                    <div class="flex items-center">
                                        <p
                                            class="font-semibold mr-1 text-indigo-900 dark:text-indigo-100 text-sm hover:text-indigo-400">
                                            {{ $item->name }}
                                        </p>
                                        <flux:icon variant="solid" :icon="$item->attendence ? 'check-badge' : 'moon'"
                                            :class="$item->attendence ? 'text-green-500 size-3' : 'text-zinc-500 size-3'" />
                                    </div>
                                    <p class="text-zinc-500 dark:text-zinc-100">
                                        {{ $item->created_at->diffForHumans() }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </flux:callout.text>
                </flux:callout>
            </div>
        @empty
            <div class="flex flex-col justify-between w-full">
                <flux:callout icon="face-frown" icon-variant="outline" color="zinc" inline>
                    <flux:callout.heading>{{ __('No wishes yet') }}</flux:callout.heading>
                    <flux:callout.text>{{ __('Waiting for our friends and family to send their wishes') }}
                    </flux:callout.text>
                    <x-slot name="actions" class="@md:h-full m-0!">
                        <flux:button href="{{ route('rsvp') }}" wire:navigate variant="ghost" icon:trailing="arrow-up-right">{{ __('Be the first') }}
                        </flux:button>
                    </x-slot>
                </flux:callout>
            </div>
        @endforelse
    </div>
    <div class="text-right">
        <div class="mt-5 mb-5">
            <flux:separator variant="sublte" />
        </div>
        <flux:text class="text-indigo-900 dark:text-indigo-100">
            {{ __('Wedding Nabilah & Hakim') }}
        </flux:text>
    </div>
</div>
