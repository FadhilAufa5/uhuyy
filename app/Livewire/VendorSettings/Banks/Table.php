<?php

namespace App\Livewire\VendorSettings\Banks;

use App\Models\VendorBank;
use Illuminate\View\View;
use Livewire\Attributes\On;
use Livewire\Component;

#[On('refresh-vendor-banks-table')]
#[On('record-updated')]
#[On('record-deleted')]
class Table extends Component
{
    public
        $checked = [],
        $checkPage = false,
        $sortField = 'vendor_banks.updated_at',
        $sortDirection = 'desc';
    protected $queryString = ['sortField', 'sortDirection'];

    public function mount(): void
    {

    }

    public function updatedSearch(): void
    {
        $this->checked = [];
        $this->checkPage = false;
    }

    public function sortBy($field): void
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection == 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortDirection = 'asc';
        }

        $this->sortField = $field;
    }

    public function render(): View
    {
        return view('livewire.vendor-settings.banks.table', [
            'vendorBanks' => $this->vendorBanks,
        ]);
    }

    public function getVendorBanksQueryProperty(): \Illuminate\Database\Eloquent\Builder
    {
        return VendorBank::with(['bank'])
            ->orderByRaw("CASE WHEN {$this->sortField} IS NULL THEN 1 ELSE 0 END, {$this->sortField} {$this->sortDirection}");
    }

    public function getVendorBanksProperty()
    {
        return $this->vendorBanksQuery->get();
    }

    public function delete($id): void
    {
        VendorBank::destroy($id);
        $this->dispatch('refresh-vendor-banks-table');
    }

    public function getCheckedIdsProperty(): string
    {
        return json_encode($this->checked);
    }
}
