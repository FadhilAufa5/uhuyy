<?php

use App\Livewire\Forms\VendorForm;
use App\Livewire\Vendors\Table;
use App\Models\Branch;
use App\Models\Vendor;
use Flux\Flux;
use Livewire\Attributes\On;
use Livewire\Volt\Component;

new class extends Component {
    public ?Vendor $vendor = null;
    public VendorForm $form;

    #[On('loadVendor')]
    public function loadVendor($id): void
    {
        if ($id) {
            $this->vendor = Vendor::find($id);
            $this->form->setVendor($this->vendor);
        } else {
            $this->resetForm(); // Completely reset when no ID is provided
        }

        $this->branches = Branch::all();
    }

    public function save(): void
    {
        $saved = $this->form->save();

        $this->dispatch('notify', title: 'success', message: 'Vendor berhasil diupdate!');
        $this->reset();
        Flux::modals()->close();
        // refresh Vendors Page after saving
        $this->dispatch('refresh-vendors-table')->to(Table::class);
    }

    public function resetForm(): void
    {
        $this->reset();
    }

}; ?>

<section class="space-y-6">

    <flux:modal name="add-vendor" :show="$errors->isNotEmpty() || $vendor" :dismissible="false"
                @close="resetForm()"
                class="max-w-lg space-y-6">
        <form wire:submit="save">
            <div>
                <flux:heading size="lg">
                    Update Vendor
                </flux:heading>
                <flux:subheading>
                    Make changes to your personal details.
                </flux:subheading>
            </div>

            <div class="flex flex-col gap-4 my-8">

                <flux:input label="Name" placeholder="Your name" wire:model.blur="form.name"/>
                <flux:input label="Vendorname" placeholder="Vendorname" wire:model.blur="form.vendorname"/>
                <flux:input label="Email" placeholder="mail@example.com" wire:model.blur="form.email"/>
                @if(!$vendor?->hasRole(\App\Enums\Roles::Vendor->value))
                    <flux:select label="Unit Bisnis" wire:model="form.branch_id" placeholder="Pilih BM">
                        @foreach($branches as $branch)
                            <flux:select.option.variants.default
                                value="{{ $branch->id }}">{{ $branch->name }}</flux:select.option.variants.default>
                        @endforeach
                    </flux:select>
                @endif
                @if(!$vendor)
                    <flux:input label="Password" type="password" viewable wire:model.live="form.password"/>
                    <flux:input label="Password Confirmation" type="password" viewable
                                wire:model.live="form.password_confirmation"/>
                @endif
            </div>

            <div class="flex gap-2">
                <flux:modal.close>
                    <flux:button variant="filled">{{ __('Cancel') }}</flux:button>
                </flux:modal.close>

                <flux:button type="submit" variant="primary">@if(!$vendor)
                        Create
                    @else
                        Update
                    @endif
                </flux:button>
            </div>
        </form>
    </flux:modal>

</section>


