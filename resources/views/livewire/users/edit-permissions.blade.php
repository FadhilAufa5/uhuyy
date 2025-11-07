<?php

use App\Livewire\Forms\UserForm;
use App\Livewire\Users\Table;
use App\Models\User;
use Livewire\Attributes\On;
use Livewire\Volt\Component;
use Spatie\Permission\Models\Permission;

new class extends Component {
    use \App\Helpers\WithToast;

    public ?User $user = null;
    public UserForm $form;
    public $roles = [];
    public $permissions = [];
    public $selectedPermissions = [];

    public function mount(?User $user = null): void
    {
        $this->form->setUser($user);
        $this->permissions = Permission::all();
    }

    #[On('loadUser')]
    public function loadUser($id): void
    {
        $this->user = $id ? User::with([ 'roles', 'permissions'])->find($id) : null;
        if ($this->user) {
            $this->form->setUser($this->user);;
        }
        $this->selectedPermissions = $this->user?->getAllPermissions()->pluck('name')->toArray();

        $this->permissions = Permission::all();
    }

    public function save(): void
    {
        $validated = $this->validate([
            'selectedPermissions' => ['required'],
        ]);

        $this->user->syncPermissions($this->selectedPermissions);
        $this->user->touch(); // update updated_at column


        $this->reset();
        Flux::modals()->close();
        // refresh Users Page after saving
        $this->dispatch('refresh-users-table')->to(Table::class);
        $this->toast(message: 'Permissions berhasil diupdate!', type: 'success');
    }

    public function resetForm(): void
    {
        $this->user = null;
    }

}; ?>

<flux:modal name="edit-permissions" :show="$errors->isNotEmpty() || $user" variant="flyout" :dismissible="false"
            @close="resetForm()"
            class="max-w-lg space-y-6">
    <form wire:submit="save">
        <div>
            <flux:heading size="lg">
                Update User Permission
            </flux:heading>
            <flux:subheading>
                Make changes to user's Permission. Make sure you assign appropriate permissions!
            </flux:subheading>
        </div>

        <div class="flex flex-col gap-4 my-8">

            <flux:input label="Name" wire:model="form.name" readonly/>
            <div>Role</div>
            <flux:checkbox.group label="Permissions" wire:model="selectedPermissions">
                @foreach($permissions as $permission)
                    <div class="flex flex-row gap-2 justify-between">
                        <flux:checkbox value="{{ $permission->name }}" label="{{ $permission->name }}"/>
                    </div>
                @endforeach
            </flux:checkbox.group>
        </div>

        <div class="flex gap-2">
            <flux:modal.close>
                <flux:button variant="filled">{{ __('Cancel') }}</flux:button>
            </flux:modal.close>

            <flux:button type="submit" variant="primary">Update</flux:button>
        </div>
    </form>
</flux:modal>

