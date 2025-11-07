<?php

use Flux\Flux;
use Livewire\Attributes\On;
use Livewire\Volt\Component;

new class extends Component {
    public string $model = '';
    public array $recordIds = [];

    public function mount(string $model = '', array $recordIds = []): void
    {
        $this->model = $model;
        $this->recordIds = $recordIds;
    }

    #[On('bulkDelete')]
    public function loadData(string $model = '', array $recordIds = []): void
    {
        $this->model = $model;
        $this->recordIds = $recordIds;
//        dd($this->model, $this->recordIds);
    }

    public function delete(): void
    {
        // Dynamically resolve the model
        $modelClass = 'App\\Models\\' . $this->model;
//        dd($modelClass);

        if (class_exists($modelClass)) {
            $record = $modelClass::whereIn('id', $this->recordIds);
//            dd($record);
            $record->delete();

            $this->dispatch('notify', title: 'success', message: 'Data berhasil dihapus');
            // Emit an event to refresh any relevant parent components
            $this->dispatch('record-deleted', $this->recordIds);
        }

        Flux::modals()->close();
    }
}
?>

<flux:modal name="delete-modal" class="min-w-[22rem]">
    <form wire:submit="delete">
        <div>
            <flux:heading size="lg">Delete data?</flux:heading>

            <flux:subheading>
                <p>You're about to delete this data.</p>
                <p>This action cannot be reversed.</p>
            </flux:subheading>
        </div>

        <div class="flex gap-2 mt-4">
            <flux:spacer/>

            <flux:modal.close>
                <flux:button variant="ghost">Cancel</flux:button>
            </flux:modal.close>

            <flux:button type="submit" variant="danger">Delete</flux:button>
        </div>
    </form>
</flux:modal>

