<?php

use App\Livewire\Forms\UserForm;
use App\Livewire\Users\Table;
use App\Models\User;
use Livewire\Attributes\On;
use Livewire\Volt\Component;
use Spatie\Permission\Models\Role;

new class extends Component {
    public ?User $user = null;
    public UserForm $form;
    public $roles = [];
    public $selectedRole = '';

    public function mount(?User $user = null): void
    {
        $this->form->setUser($user);

        $this->roles = Role::whereIn('name', ['User', 'Admin'])->get();
    }

    #[On('loadUser')]
    public function loadUser($id): void
    {
        $this->user = User::with(['roles'])->find($id);

        if ($this->user) {
            $this->form->setUser($this->user);;
        }
        $this->selectedRole = $this->user?->getRoleNames()->first();

        $this->roles = Role::whereIn('name', ['User', 'Admin'])->get();
    }

    public function save(): void
    {
        $validated = $this->validate([
            'selectedRole' => ['required'],
        ]);
        $this->user->syncRoles($this->selectedRole);
        $this->user->touch(); // update updated_at column

        $this->dispatch('notify', title: 'success', message: 'Roles berhasil diupdate!');

        $this->reset();
        Flux::modals()->close();
        // refresh Users Page after saving
        $this->dispatch('refresh-users-table')->to(Table::class);
    }

    public function with()
    {
        return [
            'user' => $this->user,
        ];
    }

    public function resetForm()
    {
        $this->user = null;
    }

}; ?>

<section class="space-y-6">

    <flux:modal name="edit-roles" :show="$errors->isNotEmpty() || $user" variant="flyout" :dismissible="false"
                @close="resetForm()"
                class="max-w-lg space-y-6">
        <form wire:submit="save">
            <div>
                <flux:heading size="lg">
                    Update User Role
                </flux:heading>
                <flux:subheading>
                    Make changes to user's Role. Make sure you assign appropriate role!
                </flux:subheading>
            </div>

            <div class="flex flex-col gap-4 my-8">

                <flux:input label="Name" wire:model="form.name" readonly/>
                <flux:input label="Username" wire:model="form.username" readonly/>
                <flux:input label="Email" wire:model="form.email" readonly/>
                <flux:radio.group label="Roles" variant="segmented" wire:model="selectedRole">
                    @foreach($roles as $role)
                        <flux:radio value="{{ $role->name }}" label="{{ $role->name }}"/>
                    @endforeach
                </flux:radio.group>
            </div>

            <div class="flex gap-2">
                <flux:modal.close>
                    <flux:button variant="filled">{{ __('Cancel') }}</flux:button>
                </flux:modal.close>

                <flux:button type="submit" variant="primary">Update</flux:button>
            </div>
        </form>
    </flux:modal>

</section>

