<x-layouts.main>
    <flux:header container class="sticky top-0 bg-zinc-100 dark:bg-zinc-900 drop-shadow-2xl mb-4 z-50">
        <flux:container>
            <div class="z-30 flex items-center justify-between h-24 md:space-x-8">
                <div class="z-20 flex items-center justify-between w-full md:w-auto">
                    <div class="relative z-20 inline-flex">
                        <a href="{{ route('home') }}" wire:navigate
                           class="flex items-center justify-center space-x-3 font-bold text-zinc-900">
                            <x-app-logo-icon class="w-auto h-8 md:h-12"></x-app-logo-icon>
                        </a>
                    </div>
                 
                </div>

                <nav
                        :class="{ 'hidden' : !mobileMenuOpen, 'block md:relative absolute top-0 left-0 md:w-auto w-screen md:h-auto h-screen pointer-events-none md:z-10 z-10' : mobileMenuOpen }"
                        class="h-full md:flex">
                    <ul :class="{ 'hidden md:flex' : !mobileMenuOpen, 'flex flex-col absolute md:relative md:w-auto w-screen h-full md:h-full md:overflow-auto overflow-scroll md:pt-0 mt-24 md:pb-0 pb-48 bg-white dark:bg-zinc-800 md:bg-transparent' : mobileMenuOpen }"
                        id="menu"
                        class="flex items-stretch justify-start flex-1 w-full h-full ml-0 border-t border-zinc-100 dark:border-zinc-700 pointer-events-auto md:items-center md:justify-center gap-x-8 md:w-auto md:border-t-0 md:flex-row">
                        @guest
                         
                        @endguest
                        <flux:button x-data x-on:click="$flux.dark = ! $flux.dark" icon="moon" variant="subtle"
                                     aria-label="Toggle dark mode" class="hidden mx-auto"/>
                        @guest
                            <li class="relative z-30 flex flex-col items-center justify-center flex-shrink-0 w-full h-auto pt-3 space-y-3 text-sm md:hidden px-7">
                                <flux:button href="{{ route('login') }}" type="a" class="w-full text-sm"
                                             color="secondary">Login
                                </flux:button>
                            </li>
                        @else
                            <li class="flex items-center justify-center w-full pt-3 md:hidden px-7">
                                <flux:button href="{{ route('login') }}" type="a" class="w-full text-sm">View
                                    Dashboard
                                </flux:button>
                            </li>
                        @endguest

                    </ul>
                </nav>
                @guest
                    <div class="relative z-30 items-center justify-center flex-shrink-0 hidden h-full space-x-3 text-sm md:flex">
                        <flux:button href="{{ route('login') }}" type="a" class="text-sm">Login</flux:button>
                    </div>
                @else
                    <flux:button href="{{ route('login') }}"
                                 class="text-sm relative flex-shrink-0 z-20 !hidden ml-2 md:!flex">View Dashboard
                    </flux:button>
                @endguest

            </div>
        </flux:container>

    </flux:header>

    {{ $slot }}

    @include('partials.footer')
</x-layouts.main>
