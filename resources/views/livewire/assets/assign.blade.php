<?php

use App\Helpers\WithToast;
use App\Models\Asset;
use App\Models\User;
use Livewire\Attributes\On;
use Livewire\Volt\Component;

new class extends Component {
    use WithToast;

    public ?int $assigned_to = null;
    public ?Asset $asset = null;

    #[On('assign-asset')]
    public function mount(?Asset $asset = null): void
    {
        $this->asset = $asset;
        Flux::modal('assign-asset')->show();
    }

    public function with(): array
    {
        $assignees = User::whereHas("roles", fn($q) => $q->where("name", \App\Enums\Roles::User->value))
            ->get();

        return [
            'assignees' => $assignees,
            'asset' => $this->asset
        ];
    }

    public function assignTo(): void
    {
        $validated = $this->validate([
            'assigned_to' => 'required'
        ]);

        if ($validated) {
            $this->asset->update($validated);
        } elseif ($validated->assigned_to = null) {
            $this->asset->update(['assigned_to' => null]);
        }
        \Flux\Flux::modal('assign-asset')->close();
        $this->toast('Aset berhasil dimutasi', 'success');
        $this->dispatch('reload-assets');
    }
}; ?>

<flux:modal name="assign-asset"
            class="w-lg bg-white/30 dark:bg-zinc-800/30 backdrop-blur-lg rounded-r-2xl drop-shadow-2xl border border-zinc-200/50 dark:border-zinc-800/50">
    <form wire:submit="assignTo">
        <div class="py-4">
            <flux:heading size="lg">
                Mutasi Aset
            </flux:heading>
            <flux:subheading>
                Silakan pilih user penerima aset!
            </flux:subheading>
        </div>
        <div class="gap-4 flex flex-col mt-4">
            <div>
                <livewire:search-dropdown
                    :key="'search-dropdown-'.now()" {{-- avoid double render --}}
                    label="User"
                    model="User"
                    searchColumn="name"
                    valueColumn="id"
                    placeholder="Search User..."
                    selected="{{ $asset->assignee?->id }}" {{-- pass id to child component --}}
                    selectedName="{{ $asset->assignee?->name }}"
                    wire:model.defer="assigned_to" {{-- avoid double post request --}}
                />
            </div>

            <div class="flex gap-2">
                <flux:modal.close>
                    <flux:button variant="filled">{{ __('Cancel') }}</flux:button>
                </flux:modal.close>

                <flux:button type="submit">Assign</flux:button>
            </div>
        </div>
    </form>
</flux:modal>
