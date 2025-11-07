<?php

use App\Enums\StoreTypes;
use App\Livewire\Forms\ApotekForm;
use App\Livewire\Forms\BankingForm;
use App\Models\Apotek;
use App\Models\Bank;
use App\Models\Branch;
use App\Models\VendorBank;
use Flux\Flux;
use Illuminate\Support\Collection;
use Livewire\Attributes\On;
use Livewire\Volt\Component;

new class extends Component {
    public ?VendorBank $vendorBank = null;
    public BankingForm $form;
    public $banks;

    public function mount(): void
    {
        $this->banks = Bank::all();
    }

    #[On('loadBank')]
    public function loadBank($id): void
    {
        if ($id) {
            $this->vendorBank = VendorBank::find($id);
            $this->form->setVendorBank($this->vendorBank);
        } else {
            $this->resetForm();
        }
    }

    public function save(): void
    {
        $this->form->vendor_id = auth()->user()->vendors()->first()->id;
        $this->form->save();

        $this->dispatch('notify', title: 'success', message: 'Data bank berhasil diupdate!');
        $this->reset();
        Flux::modals()->close();
        // refresh Apoteks Page after saving
        $this->dispatch('refresh-vendor-banks-table');
    }

    public function resetForm(): void
    {
        $this->reset();
    }

}; ?>

<section class="space-y-6">

    <flux:modal name="add-bank" :show="$errors->isNotEmpty() || $vendorBank"
                @close="resetForm()"
                class="w-2xl space-y-6">
        <form wire:submit="save">
            <div>
                <flux:heading size="lg">
                    @if(!$vendorBank)
                        Add new Bank Account
                    @else
                        Update Bank Account
                    @endif
                </flux:heading>
                <flux:subheading>
                    @if(is_null($vendorBank))
                        Fill in the details below.
                    @else
                        Make changes to your Bank Account.
                    @endif
                </flux:subheading>
            </div>

            <div class="flex flex-col gap-4 my-8">
                <div>
                    <label class="mt-4 mb-2">Nama Bank</label>
                    <x-tom-select
                        :options="collect($banks)->map(fn($bank) => ['id'=>$bank->id, 'title'=>$bank->name])"
                        wire:model="form.bank_id"/>
                    <flux:error for="form.bank_id"/>
                </div>
                <flux:input label="Cabang" placeholder="Cabang Bank" wire:model.blur="form.bank_branch"/>
                <flux:input label="Alamat Bank" placeholder="Jl. Bank No.1" wire:model.blur="form.bank_address"/>
                <flux:input label="No Rekening" placeholder="123456789-0" wire:model.blur="form.account_number"/>
                <flux:input label="Nama Pemilik Rekening" placeholder="John Doe"
                            wire:model.blur="form.account_holder_name"/>
            </div>

            <div class="flex gap-2">
                <flux:modal.close>
                    <flux:button @click="resetForm" variant="filled">{{ __('Cancel') }}</flux:button>
                </flux:modal.close>

                <flux:button type="submit" variant="primary">
                    @if(!$vendorBank)
                        Create
                    @else
                        Update
                    @endif
                </flux:button>
            </div>
        </form>
    </flux:modal>
</section>
