<?php

use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new class extends Component {
}; ?>
<div class="flex flex-col">
    <div class="overflow-x-auto">
        <div class="inline-block min-w-full">
            <div class="overflow-hidden">
                <x-table.index class="min-w-full divide-y divide-zinc-200/70">
                    <x-slot:head>
                        <x-table.heading class="px-5 py-3">Announcement Date</x-table.heading>
                        <x-table.heading class="px-5 py-3">Procurement Name</x-table.heading>
                        <x-table.heading class="px-5 py-3">Winner</x-table.heading>
                    </x-slot:head>
                    <x-slot:body>
                        <x-table.row :even="true">
                            <x-table.cell>2025-01-05</x-table.cell>
                            <x-table.cell>Lorem ipsum dolor sit amet, consectetur adipisicing elit.
                                Beatae eos est ipsum magnam officiis! Amet dolor hic officia officiis sunt, tempora voluptates.
                            </x-table.cell>
                            <x-table.cell>UD Warung Coklat</x-table.cell>
                        </x-table.row>
                        <x-table.row :even="false">
                            <x-table.cell>2025-01-02</x-table.cell>
                            <x-table.cell>Lorem ipsum dolor sit amet, consectetur adipisicing elit.
                                Consequatur dolores magni perferendis repellendus voluptas.
                            </x-table.cell>
                            <x-table.cell>PT Anak Kandung</x-table.cell>
                        </x-table.row>
                        <x-table.row :even="true">
                            <x-table.cell>2024-12-11</x-table.cell>
                            <x-table.cell>Lorem ipsum dolor sit amet, consectetur adipisicing elit.
                                Accusamus adipisci dolores enim est exercitationem repellendus soluta!
                            </x-table.cell>
                            <x-table.cell>CV Kelapa</x-table.cell>
                        </x-table.row>
                    </x-slot:body>
                </x-table.index>
            </div>
        </div>
    </div>
</div>
