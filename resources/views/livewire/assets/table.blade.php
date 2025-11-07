<div
    class="relative flex flex-col w-full h-full px-4 text-zinc-700 bg-white shadow-md rounded-xl bg-clip-border dark:bg-zinc-700 dark:text-zinc-300">
    <div class="flex items-center justify-between pt-6 pb-4 gap-2 md:flex-row md:space-y-0">
        @if(!$checked)
            <flux:modal.trigger name="add-asset">
                <flux:button variant="primary">
                    {{  __('Tambah Aset') }}
                </flux:button>
            </flux:modal.trigger>

            <flux:button variant="filled" icon="download" href="{{ route('assets.export') }}">
                Export
            </flux:button>
        @endif

        @if(count($checked))
            <div class="flex gap-4">
                <flux:button icon="download" variant="filled"
                             wire:click="exportChecked">Export ({{count($checked)}})
                </flux:button>
            </div>
        @endif

        <div class="ml-auto">
            <flux:input class=" w-64" icon="search" placeholder="Search..." wire:model.live.debounce="search"
                        clearable="true"/>
        </div>
    </div>

    @if($checkPage)
        <div class="mb-2 dark:text-zinc-200">
            @if($checkAll)
                <div>
                    You have selected all <strong>{{ count($checked) }}</strong> records.
                </div>
            @else
                <div>
                    You have selected <strong>{{ count($checked) }}</strong> records. Do you want to select all
                    <strong>{{ $assets->total() }}</strong> records?
                    <a href="#" wire:click="selectAll" class="ml-2 cursor-pointer !text-blue-500 hover:underline">Check
                        All</a>
                </div>
            @endif
        </div>
    @endif

    <div class="overflow-hidden bg-zinc-50 shadow-xl sm:rounded-lg dark:bg-zinc-800/30">
        <x-table.index>
            <x-slot name="head">
                <x-table.heading class="text-left">
                    <flux:checkbox wire:model.live="checkPage"/>
                </x-table.heading>
                <x-table.heading>#</x-table.heading>
                <x-table.heading class="w-fit"/>
                <x-table.heading sortable wire:click="sortBy('assignee_name')"
                                 :direction="$sortField === 'assignee_name' ? $sortDirection : null">Diassign ke
                </x-table.heading>
                <x-table.heading sortable wire:click="sortBy('assets.asset_type')"
                                 :direction="$sortField === 'assets.asset_type' ? $sortDirection : null">Jenis Aset |
                    Kategori
                </x-table.heading>
                <x-table.heading sortable wire:click="sortBy('assets.group')"
                                 :direction="$sortField === 'assets.group' ? $sortDirection : null">Golongan
                </x-table.heading>
                <x-table.heading sortable wire:click="sortBy('assets.brand')"
                                 :direction="$sortField === 'assets.brand' ? $sortDirection : null">Brand | Model
                </x-table.heading>
                <x-table.heading sortable wire:click="sortBy('assets.description')"
                                 :direction="$sortField === 'assets.description' ? $sortDirection : null">Deskripsi
                </x-table.heading>
                <x-table.heading sortable wire:click="sortBy('assets.purchased_on')"
                                 :direction="$sortField === 'assets.purchased_on' ? $sortDirection : null">Tanggal
                    Perolehan
                </x-table.heading>
                <x-table.heading sortable wire:click="sortBy('assets.purchase_value')"
                                 :direction="$sortField === 'assets.purchase_value' ? $sortDirection : null">Nilai
                    Perolehan
                </x-table.heading>
                <x-table.heading sortable wire:click="sortBy('assets.book_value')"
                                 :direction="$sortField === 'assets.book_value' ? $sortDirection : null">Masa Buku
                </x-table.heading>
                <x-table.heading sortable wire:click="sortBy('assets.updated_at')"
                                 :direction="$sortField === 'assets.updated_at' ? $sortDirection : null">Last Update
                </x-table.heading>
            </x-slot>
            <x-slot name="body">
                @forelse($assets as $asset)
                    <x-table.row :even="$loop->even">
                        <x-table.cell>
                            <flux:checkbox value="{{ $asset->id }}" wire:key="{{ $asset->id }}"
                                           wire:model.live="checked"/>
                        </x-table.cell>
                        <x-table.cell index>{{ $assets->firstItem() + $loop->index }}
                        </x-table.cell>

                        <x-table.cell>
                            <flux:dropdown>
                                <flux:button icon-trailing="chevron-down">Action</flux:button>
                                <flux:menu>
                                    @can(\App\Enums\Permissions::EditAssets->value)
                                        <flux:menu.item icon="pencil" class="cursor-pointer"
                                                        wire:click="editAsset('{{ $asset->id }}')">Edit
                                        </flux:menu.item>
                                        <flux:menu.item icon="user-plus" class="cursor-pointer"
                                        wire:click="assignAsset('{{ $asset->id }}')">Assign</flux:menu.item>
                                    @endcan

                                    <flux:separator/>

                                    @can(\App\Enums\Permissions::DeleteAssets->value)
                                        <flux:modal.trigger variant="danger" name="delete-modal"
                                                            x-data="{ assetId: {{  json_encode([$asset->id]) }} }"
                                                            wire:click="$dispatch('bulkDelete', {recordIds: assetId, model: 'Asset' })"
                                        >
                                            <flux:menu.item variant="danger" class="cursor-pointer"
                                                            icon="document-minus">
                                                {{  __('Delete') }}
                                            </flux:menu.item>
                                        </flux:modal.trigger>
                                    @endcan
                                </flux:menu>
                            </flux:dropdown>
                        </x-table.cell>
                        <x-table.cell>{{ $asset->assignee->name ?? null }}</x-table.cell>
                        <x-table.cell>
                            <div class="flex items-center gap-4">
                                <flux:badge class="min-w-1/3" inset="left right" color="lime">
                                    <span class="mx-auto">{{ $asset->asset_type ?? null }}</span>
                                </flux:badge>
                                <div class="min-w-36">
                                    <flux:subheading size="lg">{{ ucwords($asset->category?->name) }}</flux:subheading>
                                    <flux:text size="sm">{{ $asset->subcategory?->name }}</flux:text>
                                </div>
                            </div>
                        </x-table.cell>
                        <x-table.cell>{{ $asset->group ?? null }}</x-table.cell>
                        <x-table.cell>
                            <div class="min-w-36">
                                <flux:subheading size="lg">{{ $asset->brand ?? null }}</flux:subheading>
                                <flux:text size="sm">{{ $asset->model ?? null }}</flux:text>
                            </div>
                        </x-table.cell>
                        <x-table.cell>{{ $asset->description ?? null }}</x-table.cell>
                        <x-table.cell>{{ $asset->purchased_on ?? null }}</x-table.cell>
                        <x-table.cell>
                            <div class="flex justify-between">
                                <span>IDR</span>
                                <span>{{ number_format($asset->purchase_value) ?? null }}</span>
                            </div>
                        </x-table.cell>
                        <x-table.cell>{{ $asset->book_value ?? null }} tahun</x-table.cell>
                        <x-table.cell>
                            {{ $asset->updated_at ? $asset->updated_at->diffForHumans() : null }}
                        </x-table.cell>
                    </x-table.row>
                @empty
                    <tr>
                        <td colspan="15" class="text-center py-4 text-zinc-500 bg-zinc-50 dark:bg-zinc-700">No records
                            found
                        </td>
                    </tr>
                @endforelse
            </x-slot>
        </x-table.index>
    </div>

    <div class="flex flex-wrap items-center justify-between py-4 space-y-4 flex-column md:flex-row md:space-y-0">
        <div wire:model.live="perPage" class="flex gap-2 items-center">
            <label for="perPage">per Page:</label>
            <select id="perPage"
                    class="border px-2 py-1 border-zinc-300 dark:border-zinc-500 dark:bg-zinc-700 rounded">
                <option value="10">10</option>
                <option value="25">25</option>
                <option value="50">50</option>
            </select>
        </div>
        <div>
            {{ $assets->links('simple-pagination', data: ['scrollTo' => false]) }}
        </div>
    </div>

    {{-- modals --}}
    <livewire:assets.create/>
    <livewire:delete-modal/>
    <livewire:switch-modal/>
    <livewire:assets.assign/>
</div>
