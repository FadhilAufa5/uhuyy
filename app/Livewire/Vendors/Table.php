<?php

namespace App\Livewire\Vendors;

use App\Livewire\BaseTableComponent;
use App\Models\Vendor;
use Illuminate\View\View;
use Livewire\Attributes\On;

#[On('refresh-vendors-table')]
#[On('record-updated')]
#[On('record-deleted')]
class Table extends BaseTableComponent
{
    protected function getModelClass(): string
    {
        return Vendor::class;
    }

    protected function getRefreshEvent(): string
    {
        return 'refresh-vendors-table';
    }

    protected function getQuery()
    {
        return Vendor::with(['user', 'pics', 'banks'])
            ->when($this->search, fn($query, $search) => $query
                ->whereAny(['vendors.name', 'vendors.alias', 'vendors.email'], 'like', "%{$search}%")
            )
            ->orderByRaw("CASE WHEN {$this->sortField} IS NULL THEN 1 ELSE 0 END, {$this->sortField} {$this->sortDirection}");
    }

    public function render(): View
    {
        return view('livewire.vendors.table', [
            'vendors' => $this->getRecords(),
        ]);
    }
}
