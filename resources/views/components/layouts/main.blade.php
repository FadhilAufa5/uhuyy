<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-zinc-100 dark:bg-zinc-900 transition-colors duration-200">

        {{ $slot }}

        @livewireScripts
        @fluxScripts
        @stack('js')
        @livewire('toast')
    </body>
</html>
