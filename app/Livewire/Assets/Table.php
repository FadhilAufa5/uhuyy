<?php

namespace App\Livewire\Assets;

use App\Livewire\BaseTableComponent;
use App\Models\Asset;
use Illuminate\View\View;
use Livewire\Attributes\On;

#[On('reload-assets')]
class Table extends BaseTableComponent
{
    protected function getModelClass(): string
    {
        return Asset::class;
    }

    protected function getRefreshEvent(): string
    {
        return 'refresh-assets-table';
    }

    protected function getQuery()
    {
        return Asset::with(['assignee'])
            ->leftJoin('users', 'assets.assigned_to', '=', 'users.id')
            ->select('assets.*', 'users.name as assignee_name')
            ->when($this->search, fn($query, $search) => $query
                ->whereAny(['assets.brand', 'assets.description', 'assets.model', 'assets.asset_type', 'users.name'], 'like', "%{$search}%")
            )
            ->orderByRaw("CASE WHEN {$this->sortField} IS NULL THEN 1 ELSE 0 END, {$this->sortField} {$this->sortDirection}");
    }

    public function render(): View
    {
        return view('livewire.assets.table', [
            'assets' => $this->getRecords(),
        ]);
    }

    public function editAsset($id): void
    {
        $this->dispatch('edit-asset', $id);
    }

    public function assignAsset($id): void
    {
        $this->dispatch('assign-asset', $id);
    }
}
