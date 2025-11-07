<x-layouts.main>
    <flux:header container class="sticky top-0 bg-zinc-50 dark:bg-zinc-800 drop-shadow-2xl">
        @hasrole(\App\Enums\Roles::SuperAdmin->value . '|' . \App\Enums\Roles::Manager->value)
        <flux:sidebar.toggle class="lg:hidden" icon="bars-2" inset="left"/>
        @endhasrole

        <flux:navbar class="-mb-px max-sm:hidden">
            <a href="{{ route('home') }}" class="mr-5 flex items-center space-x-2">
                <x-app-logo class="size-24 accent-transparent" href="#"/>
            </a>

            <flux:navbar.item icon="home" href="{{ route('dashboard') }}" :current="Request::is('dashboard')"
                              wire:navigate>Dashboard
            </flux:navbar.item>

        </flux:navbar>

        <flux:spacer/>

        <flux:navbar class="mr-4">
            <flux:button id="dark-mode-toggle" variant="subtle" aria-label="Toggle dark mode">
                <svg class="w-5 h-5 dark:hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
                </svg>
                <svg class="w-5 h-5 hidden dark:block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>
                </svg>
            </flux:button>
        </flux:navbar>

        <flux:dropdown position="top" align ="start">
            <flux:profile
                :name="auth()->user()->name"
                :avatar="null"
                :initials="auth()->user()->initials()"
                icon-trailing="chevrons-up-down"
            />

            <flux:menu class="w-[220px]">
                <flux:menu.radio.group>
                    <div class="p-0 text-sm font-normal">
                        <div class="flex items-center gap-2 px-1 py-1.5 text-left text-sm">
                                <span class="relative flex h-8 w-8 shrink-0 overflow-hidden rounded-full">
                                    <span
                                        class="flex h-full w-full items-center justify-center rounded-lg bg-neutral-200 text-black dark:bg-neutral-700 dark:text-white"
                                    >
                                        <img src="{{ '' }}" alt="{{ auth()->user()->initials() }}">
                                    </span>
                                </span>

                            <div class="grid flex-1 text-left text-sm leading-tight">
                                <span class="truncate font-semibold">{{ auth()->user()->name }}</span>
                                <span class="truncate text-xs">{{ auth()->user()->email }}</span>
                            </div>
                        </div>
                    </div>
                </flux:menu.radio.group>

                <flux:menu.separator/>

                <flux:menu.radio.group>
                    <flux:menu.item href="{{ route('settings.profile') }}" icon="cog" wire:navigate>Settings
                    </flux:menu.item>
                </flux:menu.radio.group>

                <flux:menu.separator/>

                <form method="POST" action="{{ route('logout') }}" class="w-full">
                    @csrf
                    <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle" class="w-full">
                        {{ __('Log Out') }}
                    </flux:menu.item>
                </form>
            </flux:menu>
        </flux:dropdown>
    </flux:header>
    <div class="max-w-7xl mx-auto">
        {{ $slot }}
    </div>
</x-layouts.main>
