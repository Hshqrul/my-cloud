<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
        rel="stylesheet">

    <!-- Styles / Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    @fluxAppearance
</head>

<body class="min-h-screen bg-white antialiased dark:bg-linear-to-b dark:from-neutral-950 dark:to-neutral-900">
    <div class="bg-muted flex min-h-dvh flex-col items-center justify-center gap-6 p-6 md:p-10">
        <div class="flex w-full max-w-xl flex-col gap-2">
            <div>
                <div class="flex flex-col gap-6">
                    <div
                        class="rounded-xl border bg-white dark:bg-stone-950 dark:border-stone-800 text-stone-300 shadow-xs">
                        <div class="lg:px-12 pb-12 px-6">
                            <a href="#" class="flex flex-col items-center gap-2 mt-4 font-medium" wire:navigate>
                                <span class="flex h-45 w-45 lg:h-45 lg:w-45 items-center justify-center rounded-md">
                                    @php
                                        $user = App\Models\User::where('username', 'superadmin')->first();
                                    @endphp
                                    <img src="{{ $user->getFirstMediaUrl('logo_light') }}" alt="H And N"
                                        class="block dark:hidden">
                                    <img src="{{ $user->getFirstMediaUrl('logo_dark') }}" alt="H And N"
                                        class="hidden dark:block">
                                </span>
                                <span class="sr-only">{{ config('app.name', 'Laravel') }}</span>
                            </a>
                            {{ $slot }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div
        class="bottom-0 w-full text-default mx-auto flex items-center justify-between px-4 py-8 md:flex-row flex flex-col gap-2 md:gap-0">
        <div class="flex items-center gap-2">
            <div class="flex items-center gap-1">
                <span>
                    © {{ date('Y') }} <span class="font-semibold">{{ config('app.name', 'Laravel') }}</span>
                </span>
            </div>
            <span> · </span>
            <div class="flex items-center gap-1">
                <span class="rounded-sm text-base italic">
                    Built with
                    <svg class="inline-block h-4 w-4 mb-1 text-primary hover:text-red-500" fill="currentColor"
                        viewBox="0 0 24 24" aria-hidden="true">
                        <path fill-rule="evenodd"
                            d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"
                            clip-rule="evenodd" />
                    </svg>
                    by
                    <flux:link href="https://github.com/hshqrul">Hashaqirul </flux:link>.
                </span>
            </div>
        </div>
        <flux:switch x-data x-model="$flux.dark" label="Dark mode" />
    </div>
    @livewireScripts
    @fluxScripts
</body>

</html>
