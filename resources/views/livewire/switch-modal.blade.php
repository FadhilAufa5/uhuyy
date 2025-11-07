<?php

use Livewire\Attributes\On;
use Livewire\Volt\Component;

new class extends Component {
    public string $model = '';
    public string $id = '';
    public string $attribute = '';

    public function mount(string $model = '', string $id = '', string $attribute = '' ): void
    {
        $this->model = $model;
        $this->id = $id;
        $this->attribute = $attribute;
    }

    #[On('switchStatus')]
    public function loadData(string $model = '', string $id = '', string $attribute = ''): void
    {
        $this->model = $model;
        $this->id = $id;
        $this->attribute = $attribute;
    }

    public function switchStatus(): void
    {
        // Dynamically resolve the model
        $modelClass = 'App\\Models\\' . $this->model;

        if (class_exists($modelClass)) {
            $record = $modelClass::find($this->id);
            $record->update([$this->attribute => !$record->{$this->attribute}]);
            $this->dispatch('record-updated', $this->id);

            $this->dispatch('notify', title: 'success', message: 'Status berhasil diupdate', timeout: 2000);
            // Emit an event to refresh any relevant parent components
        }

        Flux::modals()->close();
    }
}
?>

<flux:modal name="switch-modal" class="min-w-[22rem]">
    <form wire:submit="switchStatus">
        <div>
            <flux:heading size="lg">Switch {{$attribute}} status?</flux:heading>

            <flux:subheading>
                <p>You're about to switch this data status.</p>
                <p>Please make sure before proceeding.</p>
            </flux:subheading>
        </div>

        <div class="flex gap-2 mt-4">
            <flux:spacer/>

            <flux:modal.close>
                <flux:button variant="ghost">Cancel</flux:button>
            </flux:modal.close>

            <flux:button type="submit" variant="danger">Confirm</flux:button>
        </div>
    </form>
</flux:modal>

