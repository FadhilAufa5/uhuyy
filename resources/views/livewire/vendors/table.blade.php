<div
    class="relative flex flex-col w-full h-full px-4 text-zinc-700 bg-white shadow-md rounded-xl bg-clip-border dark:bg-zinc-700 dark:text-zinc-300">
    <div class="flex items-center justify-between pt-6 pb-4 gap-2 md:flex-row md:space-y-0">
        @if(!$checked)
            <flux:button variant="filled" icon="download" href="{{ route('vendors.export') }}">
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
                    <strong>{{ $vendors->total() }}</strong> records?
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
                <x-table.heading sortable>#</x-table.heading>
                <x-table.heading sortable wire:click="sortBy('vendors.user.name')"
                                 :direction="$sortField === 'vendors.user.name' ? $sortDirection : null">User
                </x-table.heading>
                <x-table.heading sortable wire:click="sortBy('vendors.name')"
                                 :direction="$sortField === 'vendors.name' ? $sortDirection : null">Name
                </x-table.heading>
                <x-table.heading sortable wire:click="sortBy('vendors.business_entity')"
                                 :direction="$sortField === 'vendors.business_entity' ? $sortDirection : null">Entitas
                </x-table.heading>
                <x-table.heading sortable wire:click="sortBy('vendors.alias')"
                                 :direction="$sortField === 'vendors.alias' ? $sortDirection : null">Alias
                </x-table.heading>
                <x-table.heading sortable wire:click="sortBy('vendors.phone')"
                                 :direction="$sortField === 'vendors.phone' ? $sortDirection : null">Phone
                </x-table.heading>
                <x-table.heading sortable wire:click="sortBy('vendors.email')"
                                 :direction="$sortField === 'vendors.email' ? $sortDirection : null">Email
                </x-table.heading>
                <x-table.heading sortable wire:click="sortBy('vendors.website')"
                                 :direction="$sortField === 'vendors.website' ? $sortDirection : null">Website
                </x-table.heading>
                <x-table.heading sortable>PKP</x-table.heading>
                <x-table.heading sortable>Status</x-table.heading>
                <x-table.heading sortable wire:click="sortBy('vendors.updated_at')"
                                 :direction="$sortField === 'vendors.updated_at' ? $sortDirection : null">Last Update
                </x-table.heading>
                <x-table.heading sortable class="w-fit">Action</x-table.heading>
            </x-slot>
            <x-slot name="body">
                @forelse($vendors as $vendor)
                    <x-table.row :even="$loop->even">
                        <x-table.cell>
                            <flux:checkbox value="{{ $vendor->id }}" wire:key="{{ $vendor->id }}"
                                           wire:model.live="checked"/>
                        </x-table.cell>
                        <x-table.cell>{{ $vendors->firstItem() + $loop->index }}</x-table.cell>
                        <x-table.cell>{{ $vendor->user->username ?? null }}</x-table.cell>
                        <x-table.cell>{{ $vendor->name ?? null }}</x-table.cell>
                        <x-table.cell>{{ $vendor->business_entity ?? null }}</x-table.cell>
                        <x-table.cell>{{ $vendor->alias ?? null }}</x-table.cell>
                        <x-table.cell>{{ $vendor->phone ?? null }}</x-table.cell>
                        <x-table.cell>{{ $vendor->email ?? null }}</x-table.cell>
                        <x-table.cell>{{ $vendor->website ?? null }}</x-table.cell>
                        <x-table.cell>{{ $vendor->pkp_id ?? null }}</x-table.cell>
                        <x-table.cell>{{ $vendor->status ?? null }}</x-table.cell>
                        <x-table.cell>
                            {{ $vendor->updated_at ? $vendor->updated_at->diffForHumans() : null }}
                        </x-table.cell>
                        <x-table.cell>
                            <flux:dropdown>
                                <flux:button icon-trailing="chevron-down">Action</flux:button>
                                <flux:menu>
                                    @can(\App\Enums\Permissions::EditVendors->value)
                                        <flux:modal.trigger name="add-vendor"
                                                            wire:click="$dispatch('loadVendor', { id: {{ $vendor->id }} })">
                                            <flux:menu.item icon="pencil" class="cursor-pointer">Edit</flux:button>
                                        </flux:modal.trigger>
                                    @endcan

                                    <flux:separator/>

                                    {{--                                    <flux:modal.trigger name="switch-modal"--}}
                                    {{--                                                        wire:click="$dispatch('switchStatus', {id: {{ $vendor->id }}, model: 'Vendor', attribute: 'is_active' })"--}}
                                    {{--                                    >--}}
                                    {{--                                        @if($vendor->status === 'pending')--}}
                                    {{--                                            <flux:menu.item icon="lock-open"--}}
                                    {{--                                                            class="hover:!bg-emerald-500/30 hover:!text-emerald-500">--}}
                                    {{--                                                Approve--}}
                                    {{--                                            </flux:menu.item>--}}
                                    {{--                                        @elseif($vendor->status === 'active')--}}
                                    {{--                                            <flux:menu.item icon="lock-closed" variant="danger">Deactivate</flux:menu.item>--}}
                                    {{--                                        @elseif($vendor->status === 'inactive')--}}
                                    {{--                                            <flux:menu.item icon="lock-open"--}}
                                    {{--                                                            class="hover:!bg-emerald-500/30 hover:!text-emerald-500">--}}
                                    {{--                                                Activate--}}
                                    {{--                                            </flux:menu.item>--}}
                                    {{--                                        @endif--}}
                                    {{--                                    </flux:modal.trigger>--}}
                                    @can(\App\Enums\Permissions::DeleteVendors->value)
                                        <flux:modal.trigger variant="danger" name="delete-modal"
                                                            x-data="{ vendorId: {{  json_encode([$vendor->id]) }} }"
                                                            wire:click="$dispatch('bulkDelete', {recordIds: vendorId, model: 'Vendor' })"
                                        >
                                            <flux:menu.item variant="danger" class="cursor-pointer" icon="user-minus">
                                                {{  __('Delete') }}
                                            </flux:menu.item>
                                        </flux:modal.trigger>
                                    @endcan
                                </flux:menu>
                            </flux:dropdown>

                        </x-table.cell>
                    </x-table.row>
                @empty
                    <tr>
                        <td colspan="15" class="text-center py-4 text-zinc-500 bg-zinc-50 dark:bg-zinc-700">No records found</td>
                    </tr>
                @endforelse
            </x-slot>
        </x-table.index>
    </div>

    <div class="flex flex-wrap items-center justify-between py-4 space-y-4 flex-column md:flex-row md:space-y-0">
        @if(count($vendors) >= $perPage)
        <div wire:model.live="perPage" class="flex gap-2 items-center">
            <label for="perPage">per Page:</label>
            <select id="perPage" class="border px-2 py-1 border-zinc-300 dark:border-zinc-500 dark:bg-zinc-700 rounded">
                <option value="10">10</option>
                <option value="25">25</option>
                <option value="50">50</option>
            </select>
        </div>
        @endif
        <div>
            {{ $vendors->links('simple-pagination', data: ['scrollTo' => false]) }}
        </div>
    </div>

    {{-- modals --}}
    <livewire:delete-modal/>
    <livewire:switch-modal/>
</div>
