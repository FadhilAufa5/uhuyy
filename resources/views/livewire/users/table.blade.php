<div class="p-6 bg-white dark:bg-zinc-800 rounded-xl shadow-lg border border-gray-200 dark:border-zinc-700 transition-colors duration-200">
    <!-- Header Section -->
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-900 dark:text-white">User Management</h2>
        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Manage system users and their permissions roles</p>
    </div>

    <!-- Action Bar -->
    <div class="flex items-center justify-between pb-4 gap-3 flex-wrap">
        @if(! $checked || count($checked) < 2)
            <div class="flex gap-2">
                <flux:modal.trigger name="add-user" wire:click="$dispatch('loadUser', {id: 'null'})">
                    <flux:button variant="primary" class="shadow-md hover:shadow-lg transition-shadow">
                        <svg class="w-5 h-5 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                        </svg>
                        Add New User
                    </flux:button>
                </flux:modal.trigger>

                
            </div>
        @endif
        @if(count($checked)>1)
            <div class="flex gap-4">
                <flux:dropdown>
                    <flux:button icon-trailing="chevron-down">With Selected (<strong>{{ count($checked) }}</strong>)
                    </flux:button>

                    <flux:menu>
                        <flux:menu.item icon="download"
                                        wire:click="exportChecked">Export
                        </flux:menu.item>
                        <flux:menu.separator/>
                        <flux:modal.trigger
                            variant="danger" icon="trash" name="delete-modal"
                            x-data="{ recordIds: {{ json_encode($checked) }} }"
                            wire:click="$dispatch('bulkDelete', {recordIds: recordIds, model: 'User' })"
                        >
                            <flux:menu.item variant="danger" class="w-full" icon="user-minus">
                                {{  __('Delete') }}
                            </flux:menu.item>
                        </flux:modal.trigger>
                    </flux:menu>
                </flux:dropdown>
            </div>
        @endif
        <div class="ml-auto w-full sm:w-auto">
            <flux:input class="w-full sm:w-72" icon="search" placeholder="Search users..." wire:model.live.debounce="search" clearable="true"/>
        </div>
    </div>

    <!-- Selected Info Bar -->

    @if($checkPage)
        <div class="mb-4 p-3 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg">
            <div class="text-sm text-blue-800 dark:text-blue-300">
                @if($checkAll)
                    <svg class="w-4 h-4 inline mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    You have selected all <strong>{{ count($checked) }}</strong> records.
                @else
                    <svg class="w-4 h-4 inline mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                    </svg>
                    You have selected <strong>{{ count($checked) }}</strong> records. Do you want to select all <strong>{{ $users->total() }}</strong> records?
                    <a href="#" wire:click="selectAll" class="ml-2 font-semibold text-blue-600 dark:text-blue-400 hover:underline">Select All</a>
                </div> 
            @endif
        </div>
    @endif

    <!-- Table Container -->
    <div class="overflow-x-auto rounded-lg border border-gray-200 dark:border-zinc-700">
        <x-table.index>
            <x-slot name="head">
                <x-table.heading class="text-left">
                    <flux:checkbox wire:model.live="checkPage"/>
                </x-table.heading>
                <x-table.heading>#</x-table.heading>
                <x-table.heading sortable wire:click="sortBy('users.name')"
                                 :direction="$sortField === 'users.name' ? $sortDirection : null">Nama
                </x-table.heading>
              
                <x-table.heading>Role</x-table.heading>
                <x-table.heading>Status</x-table.heading>
                <x-table.heading sortable wire:click="sortBy('users.updated_at')"
                                 :direction="$sortField === 'users.updated_at' ? $sortDirection : null">Last Update
                </x-table.heading>
                <x-table.heading class="w-fit"/>
            </x-slot>
            <x-slot name="body">
                @forelse($users as $user)
                    <x-table.row :even="$loop->even">
                        <x-table.cell>
                            <flux:checkbox value="{{ $user->id }}" wire:key="{{ $user->id }}"
                                           wire:model.live="checked"/>
                        </x-table.cell>
                        <x-table.cell index class="w-1">{{ $users->firstItem() + $loop->index }}</x-table.cell>
                        <x-table.cell>
                            <div class="flex items-center gap-2">
                                <img
                                    class="size-10 rounded-full bg-zinc-100 dark:bg-zinc-500 text-center content-center"
                                    src=""
                                    alt="{{ $user->initials() }}"/>
                                <div>
                                    <div
                                        class="text-sm font-semibold">{{ $user->name }}</div>
                                    <div class="font-normal text-zinc-500 dark:text-zinc-400">
                                        {{ $user->email }}
                                    </div>
                                </div>
                            </div>
                        </x-table.cell>
                       
                        <x-table.cell>{{ \App\Enums\Roles::labels()[$user->getRoleNames()->first()] ?? null }}</x-table.cell>
                        <x-table.cell>
                            <div class="flex-col items-center">
                                @php
                                    $session = $this->sessions->firstWhere('user_id', $user->id);
                                    $lastActive = $session ? $session->last_active : 'offline';
                                @endphp
                                <div class="flex items-center">
                                    @if( $lastActive === 'offline')
                                        <span class="h-2.5 w-2.5 rounded-full bg-orange-500 mr-2 "></span>
                                    @else
                                        <span class="h-2.5 w-2.5 rounded-full animate-ping bg-green-500 mr-2"></span>
                                    @endif
                                    <flux:badge size="sm"
                                                color="{{$user->is_active ? 'green' : 'rose'}}">{{ $user->is_active ? 'active' : 'blocked' }}</flux:badge>
                                </div>
                            </div>
                        </x-table.cell>
                        <x-table.cell>
                            {{ $user->updated_at ? $user->updated_at->diffForHumans() : null }}
                        </x-table.cell>
                        <x-table.cell>
                            <flux:dropdown>
                                <flux:button icon-trailing="chevron-down">Action</flux:button>
                                <flux:menu>
                                    <flux:menu.item wire:click="editUser({{ $user->id }})" icon="pencil" class="cursor-pointer">Edit</flux:button>
                                    @can(\App\Enums\Permissions::ManageRoles->value)
                                        <flux:modal.trigger name="edit-roles"
                                                            wire:click="$dispatch('loadUser', { id: {{ $user->id }} })">
                                            <flux:menu.item icon="academic-cap" class="cursor-pointer">Update Role
                                            </flux:menu.item>
                                        </flux:modal.trigger>
                                    @endcan

                                    <flux:separator/>

                                    <flux:modal.trigger name="switch-modal"
                                                        wire:click="$dispatch('switchStatus', {id: {{ $user->id }}, model: 'User', attribute: 'is_active' })"
                                    >
                                        @if($user->is_active)
                                            <flux:menu.item icon="lock-closed" variant="danger">Block</flux:menu.item>
                                        @else
                                            <flux:menu.item icon="lock-open"
                                                            class="hover:!bg-emerald-500/30 hover:!text-emerald-500">
                                                Activate
                                            </flux:menu.item>
                                        @endif
                                    </flux:modal.trigger>
                                    @hasrole(\App\Enums\Roles::SuperAdmin->value)
                                    <flux:modal.trigger variant="danger" name="delete-modal"
                                                        x-data="{ userId: {{  json_encode([$user->id]) }} }"
                                                        wire:click="$dispatch('bulkDelete', {recordIds: userId, model: 'User' })"
                                    >
                                        <flux:menu.item variant="danger" class="cursor-pointer" icon="user-minus">
                                            {{  __('Delete') }}
                                        </flux:menu.item>
                                    </flux:modal.trigger>
                                    @endhasrole
                                </flux:menu>
                            </flux:dropdown>

                        </x-table.cell>
                    </x-table.row>
                @empty
                    <tr>
                        <td colspan="10" class="text-center py-4 text-zinc-500 bg-zinc-50 dark:bg-zinc-700">No records
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
            {{ $users->links('simple-pagination', data: ['scrollTo' => false]) }}
        </div>
    </div>

    {{-- modals --}}
    <livewire:users.create/>
    <livewire:users.edit-roles/>
    <livewire:delete-modal/>
    <livewire:switch-modal/>
</div>
