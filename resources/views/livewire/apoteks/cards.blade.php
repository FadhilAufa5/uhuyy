<?php

use App\Models\Apotek;
use Livewire\Volt\Component;
use Livewire\WithPagination;

new class extends Component {
    use WithPagination;

    public ?string $search = '';
    public int $perPage = 10;

    protected array $queryString = ['search'];

    protected $listeners = ['refresh-apotek-table' => '$refresh',
        //refresh table from event
        'record-deleted' => '$refresh',
        'record-updated' => '$refresh',];

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
        return ['apoteks' => Apotek::with(['branch'])
            ->when($this->search,
                fn($query, $search) => $query->whereAny(['sap_id', 'name',
                    'email'], 'like', "%{$search}%"))
            ->paginate(10),];
    }
}; ?>

<div
    class="relative flex flex-col w-full h-full px-4 text-zinc-700 bg-zinc-50 shadow-md rounded-xl bg-clip-border dark:bg-zinc-700 dark:text-zinc-300">
    <div class="flex items-center justify-between pt-6 pb-4 gap-2 md:flex-row md:space-y-0">

        <flux:modal.trigger name="add-apotek" wire:click="$dispatch('loadApotek', { id: null })">
            <flux:button variant="primary">
                {{  __('Add New Apotek') }}
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
            @forelse($apoteks as $apotek)
                <div
                    class="rounded-xl border bg-white dark:bg-zinc-950 dark:border-zinc-800 text-zinc-800 shadow-xs mb-4 mx-auto">
                    <x-accordion
                        :title="$apotek->sap_id . ' - ' . $apotek->name"
                        :id="$apotek->sap_id"
                        x-model="activeAccordion"
                    >
                        <x-slot name="details">
                            <a :href="'https://www.google.com/maps/@' . {{ $apotek->latitude ?? null }},{{ $apotek->longitude ?? null }}">
                                <div class="flex gap-2">
                                    <flux:icon.map-pin class="text-emerald-500"/>
                                    <span>{{ $apotek->address }}</span>
                                </div>
                            </a>
                        </x-slot>
                        <div
                            class="w-full py-4 px-6 xs:px-[24rem] sm:px-40 2xl:px-[24rem] text-left dark:bg-zinc-700 rounded-lg">
                            @foreach([
                                 'Kode' => $apotek->sap_id,
                                 'Nama' => $apotek->name,
                                 'Type Store' => $apotek->store_type,
                                 'Tgl Operasional' => $apotek->operational_date,
                                 'Alamat' => $apotek->address,
                                 'Longitude' => $apotek->longitude,
                                 'Latitude' => $apotek->latitude,
                                 'Kode Pos' => $apotek->zipcode,
                                 'No. Telp' => '+62' . $apotek->phone,
                                 'Status' => $apotek->status ? 'Aktif' : 'Nonaktif'
                                 ] as $column => $value)
                                <x-accordion-details :column="$column" :value="$value"/>
                            @endforeach
                        </div>
                        <div class="flex items-center justify-center gap-4 mx-auto mt-4">
                            <flux:modal.trigger name="add-apotek"
                                                wire:click="$dispatch('loadApotek', { id: {{ $apotek->id }} })">
                                <flux:button variant="primary">
                                    {{  __('Edit') }}
                                </flux:button>
                            </flux:modal.trigger>
                            <flux:modal.trigger name="switch-modal"
                                                wire:click="$dispatch('switchStatus', {id: {{ $apotek->id }}, model: 'Apotek', attribute: 'status' })"
                            >
                                @if($apotek->status)
                                    <flux:button icon="lock-closed" variant="danger">Block</flux:button>
                                @else
                                    <flux:button icon="lock-open"
                                                 class="hover:!bg-emerald-500/30 hover:!text-emerald-500">Activate
                                    </flux:button>
                                @endif
                            </flux:modal.trigger>
                            @hasrole(\App\Enums\Roles::SuperAdmin->value)
                            <flux:modal.trigger variant="danger" name="delete-modal"
                                                x-data="{ recordId: {{  json_encode([$apotek->id]) }} }"
                                                wire:click="$dispatch('bulkDelete', {recordIds: recordId, model: 'Apotek' })"
                            >
                                <flux:button variant="danger" class="cursor-pointer" icon="archive-box-x-mark">
                                    {{  __('Delete') }}
                                </flux:button>
                            </flux:modal.trigger>
                            @endhasrole
                        </div>
                    </x-accordion>
                </div>
            @empty
                <div class="py-16 text-center">
                    <div class="flex flex-col items-center justify-center">
                        <svg class="w-24 h-24 text-gray-400 dark:text-gray-600 mb-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                        <h3 class="text-xl font-semibold text-gray-700 dark:text-gray-300 mb-2">Belum Ada Data Apotek</h3>
                        <p class="text-gray-500 dark:text-gray-400 mb-6 max-w-md">
                            Mulai tambahkan data apotek untuk mengelola informasi outlet, lokasi, dan status operasional
                        </p>
                        <flux:modal.trigger name="add-apotek" wire:click="$dispatch('loadApotek', { id: null })">
                            <flux:button variant="primary" size="lg" icon="plus">
                                Tambah Apotek Pertama
                            </flux:button>
                        </flux:modal.trigger>
                    </div>
                </div>
            @endforelse
        </div>
    </div>
    <div>
        {{ $apoteks->links('simple-pagination', data: ['scrollTo' => false]) }}
    </div>

    {{-- Modals --}}
    <livewire:apoteks.create/>
    <livewire:delete-modal/>
    <livewire:switch-modal/>
</div>
