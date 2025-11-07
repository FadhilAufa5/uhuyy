<?php

use App\Models\Branch;
use Livewire\Volt\Component;
use Livewire\WithPagination;

new class extends Component {
    use WithPagination;

    public ?string $search = '';
    public int $perPage = 10;

    protected array $queryString = ['search'];

    protected $listeners = [
        'refresh-branch-table' => '$refresh',
        'record-deleted' => '$refresh',
        'record-updated' => '$refresh',
    ];

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function sortBy($field): void
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection == 'desc' ? 'asc' : 'desc';
        } else {
            $this->sortDirection = 'desc';
        }

        $this->sortField = $field;
    }

    public function with(): array
    {
        return [
            'branches' => Branch::paginate(10),
        ];
    }

}; ?>
<div
    class="relative flex flex-col w-full h-full px-4 text-zinc-700 bg-white shadow-md rounded-xl bg-clip-border dark:bg-zinc-700 dark:text-zinc-300">
    <div class="flex items-center justify-between pt-6 pb-4 gap-2 md:flex-row md:space-y-0">

        <flux:modal.trigger name="add-branch" wire:click="$dispatch('loadBranch', { id: null })">
            <flux:button variant="primary">
                {{  __('Add New BM') }}
            </flux:button>
        </flux:modal.trigger>

        <flux:button variant="filled" icon="download" href="{{ route('branches.export') }}">
            Export
        </flux:button>
        <div class="ml-auto">
            <flux:input class=" w-64" icon="search" placeholder="Search..." wire:model.live.debounce="search"
                        clearable="true"/>
        </div>
    </div>

    <div class="grid relative w-auto p-4 gap-y-2 items-center">
        <div x-data="{ activeAccordion: null }">
            @forelse($branches as $branch)
                <div
                    class="rounded-xl border bg-zinc-100 dark:bg-zinc-950 dark:border-zinc-800 text-zinc-800 shadow-xs mb-4 mx-auto">
                    <x-accordion
                        
                        x-model="activeAccordion"
                    >
                        <x-slot name="details">
                            <div class="flex gap-2">
                                <flux:icon.map-pin class="text-emerald-500"/>
                                <!-- <span>{{ $branch->address }}</span>  -->
                            </div>
                        </x-slot>
                        <div
                            class="w-full py-4 px-6 xs:px-[24rem] sm:px-40 2xl:px-[24rem] text-left dark:bg-zinc-700 rounded-lg">
                            @foreach([
                                 'file' => $branch->file_path
                                 ] as $column => $value)
                                <x-accordion-details :column="$column" :value="$value"/>
                            @endforeach
                        </div>
                        <div class="flex items-center justify-center gap-4 mx-auto mt-4">
                            <flux:modal.trigger name="add-branch"
                                                wire:click="$dispatch('loadBranch', { id: {{ $branch->id }} })">
                                <flux:button variant="primary">
                                    {{  __('Edit') }}
                                </flux:button>
                            </flux:modal.trigger>
                            <flux:modal.trigger name="switch-modal"
                                                wire:click="$dispatch('switchStatus', {id: {{ $branch->id }}, model: 'Branch', attribute: 'status' })"
                            >
                                @if($branch->status)
                                    <flux:button icon="lock-closed" variant="danger">Block</flux:button>
                                @else
                                    <flux:button icon="lock-open" class="hover:!bg-emerald-500/30 hover:!text-emerald-500">Activate</flux:button>
                                @endif
                            </flux:modal.trigger>
                            @hasrole(\App\Enums\Roles::SuperAdmin->value)
                            <flux:modal.trigger variant="danger" name="delete-modal"
    x-data="{ recordId: {{  json_encode([$branch->id]) }} }"
    wire:click="$dispatch('bulkDelete', {recordIds: recordId, model: 'Branch' })"
>
    <flux:button size="sm" variant="danger"
    @click="$dispatch('bulkDelete', {model: 'Branch', recordIds: [{{ $branch->id }}]}); $dispatch('open-modal', { name: 'delete-modal' })">
    <flux:icon.trash class="mr-1"/> Delete
    </flux:button>
    

        </flux:modal.trigger>
                            @endhasrole
                        </div>
                    </x-accordion>
                </div>
            @empty
                <p>No branches available.</p>
            @endforelse
        </div>
    </div>
    <div>
        {{ $branches->links('simple-pagination', data: ['scrollTo' => false]) }}
    </div>

    {{-- Modals --}}
    
    <livewire:branches.create/>
    <livewire:branches.delete-modal />
    <livewire:switch-modal/>
</div>
