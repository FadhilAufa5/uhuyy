<?php

use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('components.layouts.guest.index')] class extends Component {

    public function mount()
    {
    }

    /**
     * Handle an incoming registration request.
     */

}; ?>

<div class="flex flex-col gap-6 max-w-2xl mx-auto">
    <x-auth-header title="Download Resources" description="Download Dokumen-dokumen Penting terkait Registrasi Vendor"/>

    <!-- Session Status -->
    <x-auth-session-status class="text-center" :status="session('status')"/>


    <div class="grid relative w-auto p-4 gap-y-2 items-center border-b-zinc-100 dark:border-b-zinc-600">
        <div x-data="{ activeAccordion: null }">
            <div class="rounded-xl border bg-white dark:bg-zinc-700 dark:border-zinc-800 text-zinc-800 shadow-xs mb-4 mx-auto">
                <x-accordion
                        title="Form A"
                        id="form_a"
                        x-model="activeAccordion"
                >
                    <x-slot name="details">
                        <div class="flex gap-2">
                            <span>Details</span>
                        </div>
                    </x-slot>
                    <div
                            class="w-full py-4 px-6 mx-auto text-left dark:bg-zinc-700 rounded-lg flex gap-4 items-center justify-around">
                        Form A
                        <flux:button href="#" icon="document-arrow-down" variant="primary">Download</flux:button>
                    </div>
                </x-accordion>
                <x-accordion
                        title="Form B"
                        id="form_b"
                        x-model="activeAccordion"
                >
                    <x-slot name="details">
                        <div class="flex gap-2">
                            <span>Details</span>
                        </div>
                    </x-slot>
                    <div
                            class="w-full py-4 px-6 mx-auto text-left dark:bg-zinc-700 rounded-lg flex gap-4 items-center justify-around">
                        Form B
                        <flux:button href="#" icon="document-arrow-down" variant="primary">Download</flux:button>
                    </div>

                </x-accordion>
            </div>
        </div>
    </div>
</div>
