<?php

use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('components.layouts.app', ['title' => 'Dashboard'])] class extends Component {
}; ?>

<div>
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
</div>
