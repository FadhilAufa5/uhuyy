<div
    class="relative flex flex-col w-full h-full px-4 text-zinc-700 bg-white shadow-md rounded-xl bg-clip-border dark:bg-zinc-700 dark:text-zinc-300">
    <div class="flex items-center justify-between pt-6 pb-4 gap-2 md:flex-row md:space-y-0">
        @if(count($vendorBanks) < 3)
            <flux:modal.trigger name="add-bank" wire:click="$dispatch('loadBank', {id: 'null'})">
                <flux:button variant="primary">
                    {{  __('Add New Bank Account') }}
                </flux:button>
            </flux:modal.trigger>
        @endif
    </div>

    <div class="p-4 overflow-hidden bg-zinc-50 shadow-xl sm:rounded-lg dark:bg-zinc-800/30">
        <x-table.index>
            <x-slot name="head">
                <x-table.heading sortable>#</x-table.heading>
                <x-table.heading sortable>Name</x-table.heading>
                <x-table.heading sortable>Cabang</x-table.heading>
                <x-table.heading sortable>No Rekening</x-table.heading>
                <x-table.heading sortable>Nama Rekening</x-table.heading>
                <x-table.heading sortable>Last Update</x-table.heading>
                <x-table.heading sortable class="w-fit">Action</x-table.heading>
            </x-slot>
            <x-slot name="body">
                @forelse($vendorBanks as $vendorBank)
                    <x-table.row :even="$loop->even">
                        <x-table.cell>{{ $loop->index + 1 }}</x-table.cell>
                        <x-table.cell>{{ $vendorBank->bank->name ?? null }}</x-table.cell>
                        <x-table.cell>{{ $vendorBank->bank_branch ?? null }}</x-table.cell>
                        <x-table.cell index>{{ $vendorBank->account_number ?? null }}</x-table.cell>
                        <x-table.cell>{{ $vendorBank->account_holder_name ?? null }}</x-table.cell>
                        <x-table.cell>
                            {{ $vendorBank->updated_at ? $vendorBank->updated_at->diffForHumans() : null }}
                        </x-table.cell>
                        <x-table.cell>
                            <flux:dropdown>
                                <flux:button icon-trailing="chevron-down">Action</flux:button>
                                <flux:menu>
                                    <flux:modal.trigger name="add-bank"
                                                        wire:click="$dispatch('loadBank', { id: {{ $vendorBank->id }} })">
                                        <flux:menu.item icon="pencil" class="cursor-pointer">Edit</flux:button>
                                    </flux:modal.trigger>
                                    <flux:modal.trigger variant="danger" name="delete-modal"
                                                        x-data="{ bankId: {{  json_encode([$vendorBank->id]) }} }"
                                                        wire:click="$dispatch('bulkDelete', {recordIds: bankId, model: 'VendorBank' })"
                                    >
                                        <flux:menu.item variant="danger" class="cursor-pointer" icon="user-minus">
                                            {{  __('Delete') }}
                                        </flux:menu.item>
                                    </flux:modal.trigger>
                                </flux:menu>
                            </flux:dropdown>
                        </x-table.cell>
                    </x-table.row>
                @empty
                    <tr>
                        <td colspan="10" class="text-center py-4 text-zinc-500 bg-zinc-50 dark:bg-zinc-700">No records found</td>
                    </tr>
                @endforelse
            </x-slot>
        </x-table.index>
    </div>

    {{-- modals --}}
    <livewire:vendor-settings.banks.create/>
    <livewire:delete-modal/>
</div>
