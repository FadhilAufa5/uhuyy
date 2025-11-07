<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;

abstract class BaseTableComponent extends Component
{
    use WithPagination;

    public $checked = [];
    public $checkPage = false;
    public $checkAll = false;
    public $search = '';
    public $perPage = 10;
    public $sortField;
    public $sortDirection = 'desc';
    public $dateRange = null;
    public $dateArray = [];

    protected $queryString = ['search', 'sortField', 'sortDirection'];

    abstract protected function getQuery();
    abstract protected function getModelClass(): string;

    public function mount(): void
    {
        if (!$this->sortField) {
            $modelClass = $this->getModelClass();
            $table = (new $modelClass)->getTable();
            $this->sortField = "{$table}.updated_at";
        }
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
        $this->resetChecked();
    }

    public function sortBy($field): void
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortDirection = 'asc';
        }
        $this->sortField = $field;
    }

    public function updatedCheckPage($value): void
    {
        if ($value) {
            $this->checked = $this->getRecords()->pluck('id')->toArray();
        } else {
            $this->checked = [];
        }
    }

    public function selectAll(): void
    {
        $this->checkAll = true;
        $this->checked = $this->getQuery()
            ->pluck('id')
            ->map(fn($item) => (string)$item)
            ->toArray();
    }

    public function delete($id): void
    {
        $modelClass = $this->getModelClass();
        $modelClass::destroy($id);
        $this->dispatchRefresh();
    }

    public function deleteChecked(): void
    {
        $this->resetChecked();
    }

    public function isChecked($recordId): bool
    {
        return in_array($recordId, $this->checked);
    }

    public function getCheckedIdsProperty(): string
    {
        return json_encode($this->checked);
    }

    protected function resetChecked(): void
    {
        $this->checked = [];
        $this->checkPage = false;
        $this->checkAll = false;
    }

    protected function getRecords()
    {
        return $this->getQuery()->paginate($this->perPage);
    }

    protected function dispatchRefresh(): void
    {
        $this->dispatch($this->getRefreshEvent());
    }

    protected function getRefreshEvent(): string
    {
        return 'refresh-table';
    }
}
