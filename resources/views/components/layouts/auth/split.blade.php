<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-white antialiased dark:bg-zinc-950 transition-colors duration-200">
        <div class="relative grid h-dvh lg:grid-cols-2">
            <!-- Left Panel - Image -->
            <div class="relative hidden lg:block">
                <img 
                    src="https://images.unsplash.com/photo-1497366216548-37526070297c?q=80&w=2069&auto=format&fit=crop" 
                    alt="Background" 
                    class="absolute inset-0 h-full w-full object-cover"
                />
                <div class="absolute inset-0 bg-black/30 dark:bg-black/50"></div>
                
                <a href="{{ route('home') }}" class="absolute top-10 left-10 flex items-center gap-3 text-white z-10" wire:navigate>
                    <x-app-logo-icon class="h-10 fill-current drop-shadow-lg" />
                </a>
            </div>
            
            <!-- Right Panel - Form -->
            <div class="flex items-center justify-center p-6 bg-white dark:bg-zinc-950 transition-colors duration-200">
                <div class="w-full max-w-[380px]">
                    <!-- Dark Mode Toggle -->
                 

                    <a href="{{ route('home') }}" class="flex items-center justify-center gap-3 mb-10 lg:hidden" wire:navigate>
                        <x-app-logo-icon class="h-10 fill-current text-gray-900 dark:text-white" />
                        <span class="font-semibold text-xl text-gray-900 dark:text-white">{{ config('app.name') }}</span>
                    </a>
                    
                    {{ $slot }}
                </div>
            </div>
        </div>
        @fluxScripts
    </body>
</html>
