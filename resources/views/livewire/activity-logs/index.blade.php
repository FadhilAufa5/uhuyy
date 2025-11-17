<?php

use App\Models\ActivityLog;
use Livewire\Volt\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

new #[Layout('components.layouts.app'), Title('Activity Logs')] class extends Component {
    use WithPagination;

    public $search = '';
    public $eventFilter = '';
    public $userFilter = '';
    public $perPage = 25;

    public function with(): array
    {
        $query = ActivityLog::with('user')
            ->orderBy('created_at', 'desc');

        // Search
        if ($this->search) {
            $query->where(function($q) {
                $q->where('description', 'like', '%' . $this->search . '%')
                  ->orWhere('user_name', 'like', '%' . $this->search . '%')
                  ->orWhere('model_type', 'like', '%' . $this->search . '%');
            });
        }

        // Filter by event
        if ($this->eventFilter) {
            $query->where('event', $this->eventFilter);
        }

        // Filter by user
        if ($this->userFilter) {
            $query->where('user_id', $this->userFilter);
        }

        return [
            'logs' => $query->paginate($this->perPage),
            'users' => \App\Models\User::select('id', 'name')->get(),
            'events' => ActivityLog::distinct()->pluck('event'),
        ];
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingEventFilter(): void
    {
        $this->resetPage();
    }

    public function updatingUserFilter(): void
    {
        $this->resetPage();
    }

    public function clearFilters(): void
    {
        $this->search = '';
        $this->eventFilter = '';
        $this->userFilter = '';
        $this->resetPage();
    }
}; ?>

<div>
    <!-- Breadcrumbs -->
    <livewire:breadcrumbs :items="[
        ['href' => route('activity-logs.index'), 'label' => 'Activity Logs']
    ]" />

    <div class="p-6 bg-white dark:bg-zinc-800 rounded-xl shadow-lg border border-gray-200 dark:border-zinc-700 transition-colors duration-200">
        <!-- Header -->
        <div class="mb-6">
            <h2 class="text-xl font-bold text-gray-900 dark:text-white flex items-center gap-2">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
                Activity Logs
            </h2>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Monitor all system activities and user actions</p>
        </div>

    <!-- Filters -->
    <div class="mb-6 grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="md:col-span-2">
            <flux:input 
                icon="search" 
                placeholder="Search activities..." 
                wire:model.live.debounce="search" 
                clearable="true"
            />
        </div>
        
        <div>
            <flux:select wire:model.live="eventFilter" placeholder="All Events">
                <option value="">All Events</option>
                @foreach($events as $event)
                    <option value="{{ $event }}">{{ ucfirst($event) }}</option>
                @endforeach
            </flux:select>
        </div>

        <div>
            <flux:select wire:model.live="userFilter" placeholder="All Users">
                <option value="">All Users</option>
                @foreach($users as $user)
                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                @endforeach
            </flux:select>
        </div>
    </div>

    @if($search || $eventFilter || $userFilter)
        <div class="mb-4 flex items-center justify-between p-2 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg">
            <span class="text-xs text-blue-800 dark:text-blue-300">
                Filters active
            </span>
            <flux:button size="xs" variant="ghost" wire:click="clearFilters">
                Clear All
            </flux:button>
        </div>
    @endif

    <!-- Table -->
    <div class="overflow-x-auto rounded-lg border border-gray-200 dark:border-zinc-700">
        <x-table.index>
            <x-slot name="head">
                <x-table.heading>#</x-table.heading>
                <x-table.heading>User</x-table.heading>
                <x-table.heading>Event</x-table.heading>
                <x-table.heading>Model</x-table.heading>
                <x-table.heading>Description</x-table.heading>
                <x-table.heading>IP Address</x-table.heading>
                <x-table.heading>Time</x-table.heading>
                <x-table.heading>Details</x-table.heading>
            </x-slot>
            <x-slot name="body">
                @forelse($logs as $log)
                    <x-table.row :even="$loop->even" wire:key="log-{{ $log->id }}">
                        <x-table.cell index class="w-1">{{ $logs->firstItem() + $loop->index }}</x-table.cell>
                        
                        <x-table.cell>
                            <div class="flex items-center gap-2">
                                @if($log->user)
                                    <img class="size-7 rounded-full bg-zinc-100 dark:bg-zinc-500" 
                                         src="" 
                                         alt="{{ $log->user->initials() }}" />
                                    <div>
                                        <div class="text-xs font-semibold">{{ $log->user->name }}</div>
                                        <div class="text-[10px] text-zinc-500 dark:text-zinc-400">{{ $log->user->email }}</div>
                                    </div>
                                @else
                                    <div class="text-xs text-zinc-500 dark:text-zinc-400">
                                        {{ $log->user_name ?? 'System' }}
                                    </div>
                                @endif
                            </div>
                        </x-table.cell>
                        
                        <x-table.cell>
                            <flux:badge size="sm" :color="$log->event_badge_color" :icon="$log->event_icon">
                                {{ ucfirst($log->event) }}
                            </flux:badge>
                        </x-table.cell>
                        
                        <x-table.cell>
                            <span class="text-xs font-medium">{{ $log->model_name }}</span>
                            @if($log->model_id)
                                <span class="text-[10px] text-zinc-500">#{{ $log->model_id }}</span>
                            @endif
                        </x-table.cell>
                        
                        <x-table.cell>
                            <div class="text-xs">{{ $log->description }}</div>
                        </x-table.cell>
                        
                        <x-table.cell>
                            <span class="text-[10px] font-mono text-zinc-600 dark:text-zinc-400">{{ $log->ip_address }}</span>
                        </x-table.cell>
                        
                        <x-table.cell>
                            <div class="text-xs">{{ $log->created_at->diffForHumans() }}</div>
                            <div class="text-[10px] text-zinc-500">{{ $log->created_at->format('d M Y H:i') }}</div>
                        </x-table.cell>
                        
                        <x-table.cell>
                            @if($log->properties)
                                <flux:modal.trigger name="log-details-{{ $log->id }}">
                                    <flux:button size="xs" variant="ghost" icon="eye">
                                        View
                                    </flux:button>
                                </flux:modal.trigger>

                                <flux:modal name="log-details-{{ $log->id }}" class="max-w-3xl">
                                    <div class="space-y-4">
                                        <div>
                                            <flux:heading size="lg">Activity Details</flux:heading>
                                            <flux:subheading>{{ $log->description }}</flux:subheading>
                                        </div>

                                        <div class="grid grid-cols-2 gap-4 p-4 bg-zinc-50 dark:bg-zinc-900 rounded-lg">
                                            <div>
                                                <span class="text-xs font-semibold text-zinc-500 dark:text-zinc-400">User</span>
                                                <p class="text-sm font-medium">{{ $log->user_name }}</p>
                                            </div>
                                            <div>
                                                <span class="text-xs font-semibold text-zinc-500 dark:text-zinc-400">Event</span>
                                                <p class="text-sm font-medium">{{ ucfirst($log->event) }}</p>
                                            </div>
                                            <div>
                                                <span class="text-xs font-semibold text-zinc-500 dark:text-zinc-400">IP Address</span>
                                                <p class="text-sm font-mono">{{ $log->ip_address }}</p>
                                            </div>
                                            <div>
                                                <span class="text-xs font-semibold text-zinc-500 dark:text-zinc-400">Time</span>
                                                <p class="text-sm">{{ $log->created_at->format('d M Y H:i:s') }}</p>
                                            </div>
                                        </div>

                                        @if($log->properties)
                                            <div>
                                                <flux:heading size="sm" class="mb-2">Properties</flux:heading>
                                                <pre class="p-4 bg-zinc-900 text-zinc-100 rounded-lg overflow-x-auto text-xs">{{ json_encode($log->properties, JSON_PRETTY_PRINT) }}</pre>
                                            </div>
                                        @endif

                                        @if($log->user_agent)
                                            <div>
                                                <flux:heading size="sm" class="mb-2">User Agent</flux:heading>
                                                <p class="text-xs text-zinc-600 dark:text-zinc-400 font-mono p-3 bg-zinc-50 dark:bg-zinc-900 rounded">{{ $log->user_agent }}</p>
                                            </div>
                                        @endif

                                        <div class="flex justify-end">
                                            <flux:modal.close>
                                                <flux:button>Close</flux:button>
                                            </flux:modal.close>
                                        </div>
                                    </div>
                                </flux:modal>
                            @else
                                <span class="text-xs text-zinc-400">-</span>
                            @endif
                        </x-table.cell>
                    </x-table.row>
                @empty
                    <tr>
                        <td colspan="8" class="text-center py-8 text-zinc-500 bg-zinc-50 dark:bg-zinc-700">
                            <svg class="w-12 h-12 mx-auto mb-3 text-zinc-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                            </svg>
                            <p class="text-sm font-medium">No activity logs found</p>
                            <p class="text-xs mt-1">Activities will appear here once users start interacting with the system</p>
                        </td>
                    </tr>
                @endforelse
            </x-slot>
        </x-table.index>
    </div>

        <!-- Pagination -->
        <div class="flex flex-wrap items-center justify-between py-4 space-y-4 flex-column md:flex-row md:space-y-0">
            <div wire:model.live="perPage" class="flex gap-2 items-center">
                <label for="perPage" class="text-xs">per Page:</label>
                <select id="perPage" class="border px-2 py-1 text-xs border-zinc-300 dark:border-zinc-500 dark:bg-zinc-700 rounded">
                    <option value="10">10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                </select>
            </div>
            <div>
                {{ $logs->links('simple-pagination', data: ['scrollTo' => false]) }}
            </div>
        </div>
    </div>
</div>
