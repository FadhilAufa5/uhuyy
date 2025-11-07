<?php

use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new class extends Component {
}; ?>
<div class="flex flex-col">
    <div class="overflow-x-auto">
        <div class="inline-block min-w-full">
            <div class="overflow-x-clip">
                <x-table.index class="max-w-fit divide-y divide-zinc-200/70 sticky top-0">
                    <x-slot:head>
                        <x-table.heading class="px-5 py-3">Announcement Date</x-table.heading>
                        <x-table.heading class="px-5 py-3">Procurement Name</x-table.heading>
                        <x-table.heading class="px-5 py-3">Description</x-table.heading>
                        <x-table.heading class="px-5 py-3">Action</x-table.heading>
                    </x-slot:head>
                    <x-slot:body>
                        <x-table.row :even="true">
                            <x-table.cell>2025-01-23</x-table.cell>
                            <x-table.cell class="text-pretty">Lorem ipsum dolor sit amet, consectetur adipisicing elit.
                                Accusamus debitis ea ipsum, iusto non placeat sed sit veritatis voluptate voluptatem!
                                Ducimus impedit incidunt sapiente totam!
                            </x-table.cell>
                            <x-table.cell class="flex-wrap">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Ad
                                blanditiis ea hic magni maxime quos vero? Ad adipisci aliquid aperiam at, blanditiis
                                corporis, debitis dicta distinctio earum eos excepturi explicabo fuga ipsum iusto labore
                                laborum laudantium magnam maxime mollitia nostrum officiis pariatur quibusdam quo
                                reprehenderit sequi soluta temporibus ut voluptatem.
                            </x-table.cell>
                            <x-table.cell font-medium text-right whitespace-nowrap>
                                <flux:button icon="paper-airplane" href="#">Apply</flux:button>
                            </x-table.cell>
                        </x-table.row>
                        <x-table.row :even="false">
                            <x-table.cell>2025-01-02</x-table.cell>
                            <x-table.cell>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Commodi
                                facilis magni nihil quae, ratione sapiente.
                            </x-table.cell>
                            <x-table.cell>Lorem ipsum dolor sit amet, consectetur adipisicing elit.
                                Assumenda at commodi est ipsam, iste magni maiores maxime nesciunt nobis nostrum
                                perspiciatis quibusdam quis, veniam. Debitis eaque esse excepturi minus odio, quia quis
                                reprehenderit tempora unde vero. Ad consequatur deserunt distinctio et ipsum iusto maxime
                                molestias pariatur sint soluta, temporibus.
                            </x-table.cell>
                            <x-table.cell font-medium text-right>
                                <flux:button icon="paper-airplane" href="#">Apply</flux:button>
                            </x-table.cell>
                        </x-table.row>
                        <x-table.row :even="true">
                                <x-table.cell>2024-12-31</x-table.cell>
                                <x-table.cell>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Autem
                                    corporis cumque cupiditate deleniti eos et excepturi facere.
                                </x-table.cell>
                                <x-table.cell>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Ad
                                    architecto assumenda eius enim et, facere nesciunt quas quasi soluta tempora! Ad aliquid,
                                    deserunt dolor iure qui vel velit. Consequatur!
                                </x-table.cell>
                                <x-table.cell font-medium text-right whitespace-nowrap>
                                    <flux:button icon="paper-airplane" href="#">Apply</flux:button>
                                </x-table.cell>
                        </x-table.row>
                    </x-slot:body>
                </x-table.index>
            </div>
        </div>
    </div>
</div>
