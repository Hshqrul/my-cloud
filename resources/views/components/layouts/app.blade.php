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

<body class="min-h-screen bg-zinc-50 dark:bg-zinc-900">
    <flux:header id="mainHeader" class="transition-all duration-10 sticky top-0 z-50 bg-transparent">
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const header = document.getElementById('mainHeader');
        
                function handleScroll() {
                    if (window.scrollY > 10) {
                        header.classList.remove('bg-transparent');
                        header.classList.add('bg-zinc-50', 'dark:bg-zinc-900', 'border-b', 'border-zinc-100', 'dark:border-zinc-700');
                    } else {
                        header.classList.add('bg-transparent');
                        header.classList.remove('bg-zinc-50', 'dark:bg-zinc-900', 'border-b', 'border-zinc-100', 'dark:border-zinc-700');
                    }
                }
        
                window.addEventListener('scroll', handleScroll);
                handleScroll(); // Run on load
            });
        </script>
        
        <flux:sidebar.toggle class="lg:hidden" icon="bars-3" inset="left" />
        <a href="{{ route('home') }}" class="ml-2 mr-5 flex items-center space-x-2 lg:ml-0 max-lg:hidden" wire:navigate>
            <div
                class="flex aspect-square items-center justify-center">
                <svg width="50" height="50" viewBox="0 0 350 180" fill="currentColor"
                    xmlns="http://www.w3.org/2000/svg">
                    <path d="M1623 1380 c-73 -126 -133 -235 -133 -242 0 -7 40 -82 89 -168 263
                        -458 272 -471 381 -561 109 -91 226 -140 371 -158 110 -14 209 -14 209 0 0 12
                        -23 52 -355 629 -125 217 -270 470 -323 563 -52 92 -98 167 -101 167 -3 0 -66
                        -104 -138 -230z" transform="translate(0, 180) scale(0.1, -0.1)" />

                    <path d="M1305 828 c-71 -123 -175 -304 -232 -403 l-104 -180 138 -3 c77 -2
                        165 2 198 8 128 25 223 99 281 217 36 72 44 188 20 264 -15 44 -86 178 -153
                        287 l-20 32 -128 -222z" transform="translate(0, 180) scale(0.1, -0.1)" />
                </svg>
            </div>
            <div class="grid flex-1 text-left text-md mr-2">
                <span class="truncate leading-none font-bold uppercase">{{ config('app.name') }}</span>
            </div>
        </a>
        {{-- Navbar --}}
        <flux:navbar class="-mb-px max-lg:hidden">
            <flux:navbar.item icon="home" href="{{ route('home') }}" :current="request()->is('/')">Home</flux:navbar.item>
            <flux:navbar.item icon="book-open" href="{{ route('about') }}" :current="request()->is('about')">About</flux:navbar.item>
            {{-- <flux:navbar.item icon="document-text" href="#">Documents</flux:navbar.item>
            <flux:navbar.item icon="calendar" href="#">Calendar</flux:navbar.item> --}}
            {{-- <flux:separator vertical variant="subtle" class="my-2" />
            <flux:dropdown class="max-lg:hidden">
                <flux:navbar.item icon:trailing="chevron-down">Favorites</flux:navbar.item>
                <flux:navmenu>
                    <flux:navmenu.item href="#">Marketing site</flux:navmenu.item>
                    <flux:navmenu.item href="#">Android app</flux:navmenu.item>
                    <flux:navmenu.item href="#">Brand guidelines</flux:navmenu.item>
                </flux:navmenu>
            </flux:dropdown> --}}
        </flux:navbar>
        <flux:spacer />
        <flux:navbar class="me-4">
            <flux:navbar.item class="max-sm:hidden mr-2" icon="magnifying-glass" href="#" label="Search" />
            @guest
            <flux:button x-data x-on:keydown.d.window="if (document.activeElement.localName === 'body') { $flux.dark = ! $flux.dark }" class="max-sm:hidden mr-2" x-on:click="$flux.dark = ! $flux.dark" icon="moon" tooltip="Toggle dark mode" tooltip:kbd="D" variant="subtle" aria-label="Toggle dark mode" />
            @endguest
            {{-- <flux:navbar.item class="max-lg:hidden" icon="cog-6-tooth" href="#" label="Settings" />
            <flux:navbar.item class="max-lg:hidden" icon="information-circle" href="#" label="Help" /> --}}
            <flux:separator vertical class="my-2" />
        </flux:navbar>
        {{-- User Menu --}}
        <flux:dropdown position="top" align="end">
            @auth
                @php
                    $user = auth()->user();
                    $userName = $user->hasRole('super_admin') ? 'Super Admin' : '';
                    $avatarUrl = $user->getFilamentAvatarUrl();
                @endphp

                <flux:profile name="{{ $userName }}" avatar="{{ $avatarUrl }}"
                    avatar:color="auto" />
                <flux:menu>
                    <flux:menu.radio.group>
                        <div class="px-2 py-1.5">
                            <flux:text size="sm">Signed in as</flux:text>
                            <flux:heading class="mt-1! truncate">{{ auth()->user()->email }}</flux:heading>
                        </div>
                    </flux:menu.radio.group>
                    <flux:menu.separator />
                    <flux:radio.group x-data variant="segmented" x-model="$flux.appearance">
                        <flux:radio value="light" icon="sun" />
                        <flux:radio value="dark" icon="moon" />
                        <flux:radio value="system" icon="computer-desktop" />
                    </flux:radio.group>
                    <flux:menu.separator />
                    <flux:menu.item href="/admin" icon="user" class="text-zinc-800 dark:text-white">Admin Panel
                        </flux:navmenu.item>
                        {{-- <flux:menu.separator /> --}}
                        <form method="POST" action="{{ route('logout') }}" class="w-full">
                            @csrf
                            <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle" variant="danger"
                                class="w-full cursor-pointer">
                                {{ __('Log Out') }}
                            </flux:menu.item>
                        </form>
                </flux:menu>
            @else
            <flux:button
                href="/admin/login"
                variant="subtle"
                size="sm"
            >
                Log in
            </flux:button>
            
            <flux:button
                href="/admin/register"
                variant="filled"
                size="sm"
            >
                Register
            </flux:button>
            @endauth
        </flux:dropdown>
    </flux:header>
    {{-- Sidebar on mobile --}}
    <flux:sidebar stashable sticky
        class="lg:hidden bg-zinc-50 dark:bg-zinc-900 border rtl:border-r-0 rtl:border-l border-zinc-200 dark:border-zinc-700">
        <flux:sidebar.toggle class="lg:hidden" icon="x-mark" />
        {{-- <flux:brand href="#" logo="https://fluxui.dev/img/demo/logo.png" name="{{ config('app.name') }}"
            class="px-2 dark:hidden" /> --}}
            <flux:brand href="#" name="{{ config('app.name') }}">
                <x-slot name="logo" class="flex aspect-square size-6 items-center justify-center rounded-md bg-accent-content text-accent-foreground">
                    <svg width="50" height="50" viewBox="0 0 350 180" fill="currentColor"
                    xmlns="http://www.w3.org/2000/svg">
                    <path d="M1623 1380 c-73 -126 -133 -235 -133 -242 0 -7 40 -82 89 -168 263
                        -458 272 -471 381 -561 109 -91 226 -140 371 -158 110 -14 209 -14 209 0 0 12
                        -23 52 -355 629 -125 217 -270 470 -323 563 -52 92 -98 167 -101 167 -3 0 -66
                        -104 -138 -230z" transform="translate(0, 180) scale(0.1, -0.1)" />

                    <path d="M1305 828 c-71 -123 -175 -304 -232 -403 l-104 -180 138 -3 c77 -2
                        165 2 198 8 128 25 223 99 281 217 36 72 44 188 20 264 -15 44 -86 178 -153
                        287 l-20 32 -128 -222z" transform="translate(0, 180) scale(0.1, -0.1)" />
                </svg>
                </x-slot>
            </flux:brand>
        {{-- <flux:brand href="#" logo="https://fluxui.dev/img/demo/dark-mode-logo.png" name="{{ config('app.name') }}"
            class="px-2 hidden dark:flex" /> --}}
        <flux:navlist variant="outline">
            <flux:navlist.item icon="home" href="{{ route('home') }}" :current="request()->is('/')">Home</flux:navlist.item>
            <flux:navlist.item icon="book-open" href="{{ route('about') }}" :current="request()->is('about')">Home</flux:navlist.item>
            {{-- <flux:navlist.item icon="inbox" badge="12" href="#">Inbox</flux:navlist.item>
            <flux:navlist.item icon="document-text" href="#">Documents</flux:navlist.item>
            <flux:navlist.item icon="calendar" href="#">Calendar</flux:navlist.item>
            <flux:navlist.group expandable heading="Favorites" class="max-lg:hidden">
                <flux:navlist.item href="#">Marketing site</flux:navlist.item>
                <flux:navlist.item href="#">Android app</flux:navlist.item>
                <flux:navlist.item href="#">Brand guidelines</flux:navlist.item>
            </flux:navlist.group> --}}
        </flux:navlist>
        <flux:spacer />
        <flux:navlist variant="outline">
            @guest
            {{-- <flux:text class="text-sm">Appearance</flux:text> --}}
            {{-- <flux:button x-data class="mr-2" x-on:click="$flux.dark = ! $flux.dark" icon="moon" tooltip="Toggle dark mode" tooltip:kbd="D" variant="subtle" aria-label="Toggle dark mode" /> --}}

            <flux:navlist.item icon="moon">
                <flux:switch x-data x-model="$flux.dark" label="Dark mode"  />
            </flux:navlist.item>
            @endguest
            {{-- <flux:navlist.item icon="cog-6-tooth" href="#">Settings</flux:navlist.item>
            <flux:navlist.item icon="information-circle" href="#">Help</flux:navlist.item> --}}
        </flux:navlist>
    </flux:sidebar>
    <flux:main >
        {{ $slot }}
    </flux:main>

    <flux:footer container class="bg-zinc-50 dark:bg-zinc-900 border-t border-zinc-200 dark:border-zinc-700">
        {{-- <div class="bg-zinc-50 dark:bg-zinc-900"> --}}
            <flux:text class="text-sm text-right">Copyright © {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</flux:text>
        {{-- </div> --}}
    </flux:footer>
    @livewireScripts
    @fluxScripts
</body>

</html>
