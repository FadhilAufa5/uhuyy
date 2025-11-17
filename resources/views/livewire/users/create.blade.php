<?php

use App\Livewire\Forms\UserForm;
use App\Livewire\Users\Table;
use App\Models\Branch;
use App\Models\User;
use Flux\Flux;
use Livewire\Attributes\On;
use Livewire\Volt\Component;


new class extends Component {
    use \App\Helpers\WithToast;

    public ?User $user = null;
    public UserForm $form;

    // public $branches = [];

    // public function mount(): void
    // {
    //     $this->branches = Branch::all();
    // }

    #[On('edit-user')]
    public function loadUser(?User $user = null): void
    {
        $this->user = $user;

        if ($user) {
            $this->form->setUser($user);
        }

        Flux::modal('add-user')->show();
    }

    // #[On('updateSelected')]
    // public function setSelected($id): void
    // {
    //     $this->form->branch_id = $id;
    // }

    public function save(): void
    {
        $saved = $this->form->save();

        $this->reset();
        // refresh Users Table after saving
        $this->dispatch('reload-users')->to(Table::class);
        Flux::modal('add-user')->close();
        $this->toast(message: 'User berhasil diupdate!', type: 'success');
    }

    public function resetForm(): void
    {
        $this->reset();
    }

}; ?>

<flux:modal name="add-user" :dismissible="false"
            @close="resetForm()"
            class="max-w-lg space-y-6 bg-white/30 dark:bg-zinc-800/30 backdrop-blur-lg rounded-r-2xl drop-shadow-7xl border border-zinc-200/50 dark:border-zinc-800/50">
    <form wire:submit="save">
        <div>
            <flux:heading size="lg">
                @if(!$user)
                    Add New User
                @else
                    Update User
                @endif</flux:heading>
            <flux:subheading>
                @if(!$user)
                    Fill in the details below.
                @else
                    Make changes to your personal details.
                @endif</flux:subheading>
        </div>

        <div class="flex flex-col gap-4 my-8">

            <flux:input label="Name" placeholder="Your name" wire:model.blur="form.name"/>
            <flux:input label="Username" placeholder="Username" wire:model.blur="form.username"/>
            <flux:input label="Email" placeholder="mail@example.com" wire:model.blur="form.email"/>
            @if(!$user)
                <flux:input label="Password" type="password" viewable wire:model="form.password"/>
                <flux:input label="Password Confirmation" type="password" viewable
                            wire:model="form.password_confirmation"/>
            @endif
            
{{--                <livewire:search-dropdown--}}
{{--                    :key="'search-dropdown-'.now()" --}}{{-- avoid double render --}}
{{--                    :selected="$user?->branch?->id" --}}{{-- pass id to child component --}}
{{--                    :selectedName="$user?->branch?->name ?? ''"--}}

            
                {{-- <flux:select label="Branch" placeholder="Pilih Unit" wire:model="form.branch_id">
                    <option value="IT">IT</option>
                    <option value="Manager Operasional">Manager Operasional</option>
                </flux:select> --}}
        </div>

        <div class="flex gap-2">
            <flux:modal.close>
                <flux:button variant="filled">{{ __('Cancel') }}</flux:button>
            </flux:modal.close>

            <flux:button type="submit" variant="primary">@if(!$user)
                    Create
                @else
                    Update
                @endif
            </flux:button>
        </div>
    </form>
</flux:modal>


