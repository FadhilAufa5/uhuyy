<?php

use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('components.layouts.app', ['title' => 'Dashboard'])] class extends Component {
}; ?>

<div>
    @unlessrole(\App\Enums\Roles::Vendor->value)
    <livewire:breadcrumbs :items="[
        ['href' => route('dashboard'), 'label' => 'Dashboard']
    ]" />

    <div class="flex flex-col flex-1 w-full h-full gap-4 rounded-xl">

        <div class="grid gap-4 auto-rows-min md:grid-cols-3">
            <!-- Card: User -->
            <div class="relative border aspect-auto rounded-xl border-neutral-200 dark:border-neutral-700">
                <livewire:dashboard.card.user-card />
            </div>

            <!-- Card: Upload -->
            <div class="relative border aspect-auto rounded-xl border-neutral-200 dark:border-neutral-700">
                <livewire:dashboard.card.branch-upload-card>
            </div>
        </div>

    </div>
    @endunlessrole

    @hasrole(\App\Enums\Roles::Vendor->value)
    <div class="w-full mx-auto">
        <flux:heading size="xl" class="mx-auto my-6 text-zinc-800 dark:text-zinc-50 text-center">Procurement News</flux:heading>
        @livewire('news')
    </div>
    @endhasrole
</div>
