@hasrole(\App\Enums\Roles::Vendor->value)
<x-layouts.app.header :title="$title ?? null">
    <flux:main class="bg-gray-50 dark:bg-zinc-900 min-h-screen transition-colors duration-200">
        {{ $slot }}
    </flux:main>
</x-layouts.app.header>
@endhasrole

@unlesshasrole(\App\Enums\Roles::Vendor->value)
<x-layouts.app.sidebar :title="$title ?? null">
    <flux:main class="bg-gray-50 dark:bg-zinc-900 min-h-screen transition-colors duration-200">
        {{ $slot }}
    </flux:main>
</x-layouts.app.sidebar>
@endunlessrole

<script>
    // Modern toast notification handler
    Livewire.on('notify', ({ title, message, timeout = 3000 }) => {
        const type = title.toLowerCase();
        if (window.toast && typeof window.toast[type] === 'function') {
            window.toast[type](message, timeout);
        } else {
            window.toast.info(message, timeout);
        }
    });
</script>

