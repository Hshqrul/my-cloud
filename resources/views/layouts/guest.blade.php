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
    <div class="bg-background flex min-h-svh flex-col items-center justify-center gap-6 p-6 md:p-10">
        <div class="flex w-full max-w-xl flex-col gap-2">
            <a href="#" class="flex flex-col items-center gap-2 font-medium" wire:navigate>
                <span class="flex h-50 w-50 mb-1 items-center justify-center rounded-md">
                    @php
                        $user = App\Models\User::where('username', 'superadmin')->first();
                    @endphp
                    <img src="{{ $user->getFirstMediaUrl('logo_light') }}" alt="H And N" class="block dark:hidden">
                    <img src="{{ $user->getFirstMediaUrl('logo_dark') }}" alt="H And N" class="hidden dark:block">

                </span>
                <span class="sr-only">{{ config('app.name', 'Laravel') }}</span>
            </a>
            <div class="flex flex-col gap-6">
                {{ $slot }}
            </div>
        </div>
    </div>
    <footer class="fixed bottom-0 w-full p-4 text-xs font-poppins font-light">
        <div class="flex items-center justify-center gap-2">
            <flux:text>&copy; {{ date('Y') }} {{ config('app.name', 'Laravel') }}.</flux:text>
            <flux:text>Built with
                <svg class="inline-block h-4 w-4 mb-1 text-primary" fill="currentColor" viewBox="0 0 24 24"
                    aria-hidden="true">
                    <path fill-rule="evenodd"
                        d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"
                        clip-rule="evenodd" />
                </svg>
                by <flux:link href="#">Hashaqirul </flux:link>.
            </flux:text>
        </div>
    </footer>

    <div class="fixed bottom-0 right-0 p-4 text-xs font-light">
        <flux:button x-data x-on:click="$flux.dark = ! $flux.dark" icon="moon" variant="subtle"
            aria-label="Toggle dark mode" />
    </div>
    @livewireScripts
    @fluxScripts
</body>

</html>
