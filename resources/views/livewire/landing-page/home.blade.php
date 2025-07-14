<div class="width-full dark:bg-transparent dark:bg-opacity-50 dark:backdrop-blur dark:backdrop-filter">
    <div class="relative isolate px-6 pt-14 lg:px-8">
        <flux:heading size="xl" level="1">
            @php
                $hour = date('G');
            @endphp
            @if ($hour >= 6 && $hour < 12)
                Good morning,
            @elseif ($hour >= 12 && $hour < 18)
                Good afternoon,
            @else
                Good evening,
            @endif
            {{ auth()->user()->name ?? 'Hello There!' }}
        </flux:heading>
        <flux:text class="mt-2 mb-6">Here's what's new today</flux:text>
        <flux:separator variant="subtle" />
        <div class="absolute inset-x-0 -top-40 -z-10 transform-gpu overflow-hidden blur-3xl sm:-top-80"
            aria-hidden="true">
            <div class="relative left-[calc(50%-11rem)] aspect-1155/678 w-[36.125rem] -translate-x-1/2 rotate-[30deg] bg-linear-to-tr from-[#ff80b5] to-[#9089fc] opacity-30 sm:left-[calc(50%-30rem)] sm:w-[72.1875rem]"
                style="clip-path: polygon(74.1% 44.1%, 100% 61.6%, 97.5% 26.9%, 85.5% 0.1%, 80.7% 2%, 72.5% 32.5%, 60.2% 62.4%, 52.4% 68.1%, 47.5% 58.3%, 45.2% 34.5%, 27.5% 76.7%, 0.1% 64.9%, 17.9% 100%, 27.6% 76.8%, 76.1% 97.7%, 74.1% 44.1%)">
            </div>
        </div>
        <div class="mx-auto max-w-2xl py-32 sm:py-48 lg:py-56">
            <div class="hidden sm:mb-8 sm:flex sm:justify-center">
                <div
                    class="relative rounded-full px-3 py-1 text-sm/6 text-gray-600 ring-1 ring-gray-900/10 hover:ring-gray-900/20 dark:text-gray-400 dark:ring-white/10">
                    Announcing our next round of funding. <a href="#" class="font-semibold text-indigo-600"><span
                            class="absolute inset-0" aria-hidden="true"></span>Read more <span
                            aria-hidden="true">&rarr;</span></a>
                </div>
            </div>
            <div class="text-center">
                <h1
                    class="text-5xl font-semibold tracking-tight text-balance text-gray-900 sm:text-7xl dark:text-white">
                    Data to
                    enrich your online business</h1>
                <p class="mt-8 text-lg font-medium text-pretty text-gray-500 sm:text-xl/8">Anim aute id magna aliqua
                    ad ad non deserunt sunt. Qui irure qui lorem cupidatat commodo. Elit sunt amet fugiat veniam
                    occaecat.</p>
                <div class="mt-10 flex items-center justify-center gap-x-6">
                    <flux:button href="/admin/register" variant="primary">Get started</flux:button>
                    <flux:link href="#" variant="subtle" class="text-sm/6">Learn more <span
                            aria-hidden="true">→</span></flux:link>
                </div>
            </div>
        </div>
        <div class="absolute inset-x-0 top-[calc(100%-13rem)] -z-10 transform-gpu overflow-hidden blur-3xl sm:top-[calc(100%-30rem)]"
            aria-hidden="true">
            <div class="relative left-[calc(50%+3rem)] aspect-1155/678 w-[36.125rem] -translate-x-1/2 bg-linear-to-tr from-[#ff80b5] to-[#9089fc] opacity-30 sm:left-[calc(50%+36rem)] sm:w-[72.1875rem]"
                style="clip-path: polygon(74.1% 44.1%, 100% 61.6%, 97.5% 26.9%, 85.5% 0.1%, 80.7% 2%, 72.5% 32.5%, 60.2% 62.4%, 52.4% 68.1%, 47.5% 58.3%, 45.2% 34.5%, 27.5% 76.7%, 0.1% 64.9%, 17.9% 100%, 27.6% 76.8%, 76.1% 97.7%, 74.1% 44.1%)">
            </div>
        </div>
    </div>

    <div class="mx-auto max-w-2xl px-6 lg:max-w-7xl lg:px-8">
        <h2 class="text-center text-base/7 font-semibold text-indigo-600">Deploy faster</h2>
        <p
            class="mx-auto mt-2 max-w-lg text-center text-4xl font-semibold tracking-tight text-balance text-gray-950 sm:text-5xl dark:text-white">
            Everything you need to deploy your app</p>
        <div class="mt-10 grid gap-4 sm:mt-16 lg:grid-cols-3 lg:grid-rows-2">
            <div class="relative lg:row-span-2">
                <div
                    class="absolute inset-px rounded-lg bg-white lg:rounded-l-[2rem] dark:bg-zinc-800 dark:backdrop-blur dark:backdrop-filter dark:ring-1 dark:ring-inset dark:ring-gray-100/5">
                </div>
                <div
                    class="relative flex h-full flex-col overflow-hidden rounded-[calc(var(--radius-lg)+1px)] lg:rounded-l-[calc(2rem+1px)]">
                    <div class="px-8 pt-8 pb-3 sm:px-10 sm:pt-10 sm:pb-0">
                        <p
                            class="mt-2 text-lg font-medium tracking-tight text-gray-950 max-lg:text-center dark:text-white">
                            Mobile
                            friendly</p>
                        <p class="mt-2 max-w-lg text-sm/6 text-gray-600 max-lg:text-center dark:text-gray-400">Anim aute
                            id magna
                            aliqua ad ad non deserunt sunt. Qui irure qui lorem cupidatat commodo.</p>
                    </div>
                    <div class="@container relative min-h-[30rem] w-full grow max-lg:mx-auto max-lg:max-w-sm">
                        <div
                            class="absolute inset-x-10 top-10 bottom-0 overflow-hidden rounded-t-[12cqw] border-x-[3cqw] border-t-[3cqw] border-gray-700 bg-gray-900 shadow-2xl">
                            <img class="size-full object-cover object-top"
                                src="https://tailwindcss.com/plus-assets/img/component-images/bento-03-mobile-friendly.png"
                                alt="">
                        </div>
                    </div>
                </div>
                <div
                    class="pointer-events-none absolute inset-px rounded-lg shadow-sm ring-1 ring-black/5 lg:rounded-l-[2rem]">
                </div>
            </div>
            <div class="relative max-lg:row-start-1">
                <div class="absolute inset-px rounded-lg bg-white max-lg:rounded-t-[2rem] dark:bg-zinc-800"></div>
                <div
                    class="relative flex h-full flex-col overflow-hidden rounded-[calc(var(--radius-lg)+1px)] max-lg:rounded-t-[calc(2rem+1px)] dark:bg-zinc-800 dark:backdrop-blur dark:backdrop-filter dark:ring-1 dark:ring-inset dark:ring-gray-100/5">
                    <div class="px-8 pt-8 sm:px-10 sm:pt-10">
                        <p
                            class="mt-2 text-lg font-medium tracking-tight text-gray-950 max-lg:text-center dark:text-white">
                            Performance</p>
                        <p class="mt-2 max-w-lg text-sm/6 text-gray-600 max-lg:text-center dark:text-gray-400">Lorem
                            ipsum, dolor sit
                            amet consectetur adipisicing elit maiores impedit.</p>
                    </div>
                    <div
                        class="flex flex-1 items-center justify-center px-8 max-lg:pt-10 max-lg:pb-12 sm:px-10 lg:pb-2">
                        <img class="w-full max-lg:max-w-xs"
                            src="https://tailwindcss.com/plus-assets/img/component-images/bento-03-performance.png"
                            alt="">
                    </div>
                </div>
                <div
                    class="pointer-events-none absolute inset-px rounded-lg shadow-sm ring-1 ring-black/5 max-lg:rounded-t-[2rem]">
                </div>
            </div>
            <div class="relative max-lg:row-start-3 lg:col-start-2 lg:row-start-2">
                <div
                    class="absolute inset-px rounded-lg bg-white dark:bg-zinc-800 dark:backdrop-blur dark:backdrop-filter dark:ring-1 dark:ring-inset dark:ring-gray-100/5">
                </div>
                <div class="relative flex h-full flex-col overflow-hidden rounded-[calc(var(--radius-lg)+1px)]">
                    <div class="px-8 pt-8 sm:px-10 sm:pt-10">
                        <p
                            class="mt-2 text-lg font-medium tracking-tight text-gray-950 max-lg:text-center dark:text-white">
                            Security
                        </p>
                        <p class="mt-2 max-w-lg text-sm/6 text-gray-600 max-lg:text-center dark:text-gray-400">Morbi
                            viverra dui mi
                            arcu sed. Tellus semper adipiscing suspendisse semper morbi.</p>
                    </div>
                    <div class="@container flex flex-1 items-center max-lg:py-6 lg:pb-2">
                        <img class="h-[min(152px,40cqw)] object-cover"
                            src="https://tailwindcss.com/plus-assets/img/component-images/bento-03-security.png"
                            alt="">
                    </div>
                </div>
                <div class="pointer-events-none absolute inset-px rounded-lg shadow-sm ring-1 ring-black/5"></div>
            </div>
            <div class="relative lg:row-span-2">
                <div
                    class="absolute inset-px rounded-lg bg-white max-lg:rounded-b-[2rem] lg:rounded-r-[2rem] dark:bg-zinc-800 dark:backdrop-blur dark:backdrop-filter dark:ring-1 dark:ring-inset dark:ring-gray-100/5">
                </div>
                <div
                    class="relative flex h-full flex-col overflow-hidden rounded-[calc(var(--radius-lg)+1px)] max-lg:rounded-b-[calc(2rem+1px)] lg:rounded-r-[calc(2rem+1px)]">
                    <div class="px-8 pt-8 pb-3 sm:px-10 sm:pt-10 sm:pb-0">
                        <p
                            class="mt-2 text-lg font-medium tracking-tight text-gray-950 max-lg:text-center dark:text-white">
                            Powerful
                            APIs</p>
                        <p class="mt-2 max-w-lg text-sm/6 text-gray-600 max-lg:text-center dark:text-gray-400">Sit quis
                            amet rutrum
                            tellus ullamcorper ultricies libero dolor eget sem sodales gravida.</p>
                    </div>
                    <div class="relative min-h-[30rem] w-full grow">
                        <div
                            class="absolute top-10 right-0 bottom-0 left-10 overflow-hidden rounded-tl-xl bg-gray-900 shadow-2xl">
                            <div class="flex bg-gray-800/40 ring-1 ring-white/5">
                                <div class="-mb-px flex text-sm/6 font-medium text-gray-400">
                                    <div
                                        class="border-r border-b border-r-white/10 border-b-white/20 bg-white/5 px-4 py-2 text-white">
                                        NotificationSetting.jsx</div>
                                    <div class="border-r border-gray-600/10 px-4 py-2">App.jsx</div>
                                </div>
                            </div>
                            <div class="px-6 pt-6 pb-14">
                                <!-- Your code example -->
                            </div>
                        </div>
                    </div>
                </div>
                <div
                    class="pointer-events-none absolute inset-px rounded-lg shadow-sm ring-1 ring-black/5 max-lg:rounded-b-[2rem] lg:rounded-r-[2rem]">
                </div>
            </div>
        </div>
    </div>

    <div class="py-24 sm:py-32">
        <div class="mx-auto max-w-7xl px-6 lg:px-8">
            <div class="mx-auto max-w-2xl lg:mx-0">
                <h2 class="text-4xl font-semibold tracking-tight text-pretty text-gray-900 sm:text-5xl dark:text-white">
                    From the blog
                </h2>
                <p class="mt-2 text-lg/8 text-gray-600 dark:text-gray-400">Learn how to grow your business with our
                    expert advice.</p>
            </div>
            <div
                class="mx-auto mt-10 grid max-w-2xl grid-cols-1 gap-x-8 gap-y-16 border-t border-gray-200 pt-10 sm:mt-16 sm:pt-16 lg:mx-0 lg:max-w-none lg:grid-cols-3">
                <article class="flex max-w-xl flex-col items-start justify-between">
                    <div class="flex items-center gap-x-4 text-xs">
                        <time datetime="2020-03-16" class="text-gray-500 dark:text-gray-400">Mar 16, 2020</time>
                        <flux:badge as="button" variant="pill" size="md">Marketing</flux:badge>
                    </div>
                    <div class="group relative">
                        <h3
                            class="mt-3 text-lg/6 font-semibold text-gray-900 group-hover:text-gray-600 dark:text-white">
                            <a href="#">
                                <span class="absolute inset-0"></span>
                                Boost your conversion rate
                            </a>
                        </h3>
                        <p class="mt-5 line-clamp-3 text-sm/6 text-gray-600 dark:text-gray-400">Illo sint voluptas.
                            Error voluptates culpa
                            eligendi. Hic vel totam vitae illo. Non aliquid explicabo necessitatibus unde. Sed
                            exercitationem placeat consectetur nulla deserunt vel. Iusto corrupti dicta.</p>
                    </div>
                    <div class="relative mt-8 flex items-center gap-x-4">
                        <img src="https://images.unsplash.com/photo-1519244703995-f4e0f30006d5?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80"
                            alt="" class="size-10 rounded-full bg-gray-50">
                        <div class="text-sm/6">
                            <p class="font-semibold text-gray-900 dark:text-white">
                                <a href="#">
                                    <span class="absolute inset-0"></span>
                                    Michael Foster
                                </a>
                            </p>
                            <p class="text-gray-600 dark:text-gray-400">Co-Founder / CTO</p>
                        </div>
                    </div>
                </article>
                <article class="flex max-w-xl flex-col items-start justify-between">
                    <div class="flex items-center gap-x-4 text-xs">
                        <time datetime="2020-03-16" class="text-gray-500 dark:text-gray-400">Mar 16, 2020</time>
                        <flux:badge as="button" variant="pill" size="md">Marketing</flux:badge>
                    </div>
                    <div class="group relative">
                        <h3
                            class="mt-3 text-lg/6 font-semibold text-gray-900 group-hover:text-gray-600 dark:text-white">
                            <a href="#">
                                <span class="absolute inset-0"></span>
                                Boost your conversion rate
                            </a>
                        </h3>
                        <p class="mt-5 line-clamp-3 text-sm/6 text-gray-600 dark:text-gray-400">Illo sint voluptas.
                            Error voluptates culpa
                            eligendi. Hic vel totam vitae illo. Non aliquid explicabo necessitatibus unde. Sed
                            exercitationem placeat consectetur nulla deserunt vel. Iusto corrupti dicta.</p>
                    </div>
                    <div class="relative mt-8 flex items-center gap-x-4">
                        <img src="https://images.unsplash.com/photo-1519244703995-f4e0f30006d5?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80"
                            alt="" class="size-10 rounded-full bg-gray-50">
                        <div class="text-sm/6">
                            <p class="font-semibold text-gray-900 dark:text-white">
                                <a href="#">
                                    <span class="absolute inset-0"></span>
                                    Michael Foster
                                </a>
                            </p>
                            <p class="text-gray-600 dark:text-gray-400">Co-Founder / CTO</p>
                        </div>
                    </div>
                </article>
                <article class="flex max-w-xl flex-col items-start justify-between">
                    <div class="flex items-center gap-x-4 text-xs">
                        <time datetime="2020-03-16" class="text-gray-500 dark:text-gray-400">Mar 16, 2020</time>
                        <flux:badge as="button" variant="pill" size="md">Marketing</flux:badge>
                    </div>
                    <div class="group relative">
                        <h3
                            class="mt-3 text-lg/6 font-semibold text-gray-900 group-hover:text-gray-600 dark:text-white">
                            <a href="#">
                                <span class="absolute inset-0"></span>
                                Boost your conversion rate
                            </a>
                        </h3>
                        <p class="mt-5 line-clamp-3 text-sm/6 text-gray-600 dark:text-gray-400">Illo sint voluptas.
                            Error voluptates culpa
                            eligendi. Hic vel totam vitae illo. Non aliquid explicabo necessitatibus unde. Sed
                            exercitationem placeat consectetur nulla deserunt vel. Iusto corrupti dicta.</p>
                    </div>
                    <div class="relative mt-8 flex items-center gap-x-4">
                        <img src="https://images.unsplash.com/photo-1519244703995-f4e0f30006d5?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80"
                            alt="" class="size-10 rounded-full bg-gray-50">
                        <div class="text-sm/6">
                            <p class="font-semibold text-gray-900 dark:text-white">
                                <a href="#">
                                    <span class="absolute inset-0"></span>
                                    Michael Foster
                                </a>
                            </p>
                            <p class="text-gray-600 dark:text-gray-400">Co-Founder / CTO</p>
                        </div>
                    </div>
                </article>

                <!-- More posts... -->
            </div>
        </div>
    </div>
</div>
