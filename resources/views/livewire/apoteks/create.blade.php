<?php

use App\Enums\StoreTypes;
use App\Livewire\Forms\ApotekForm;
use App\Models\Apotek;
use App\Models\Branch;
use Flux\Flux;
use Illuminate\Support\Collection;
use Livewire\Attributes\On;
use Livewire\Volt\Component;

new class extends Component {
    public ?Apotek $apotek = null;
    public ApotekForm $form;
    public $branches;

    public function mount(): void
    {
        $this->branches = Branch::all();
    }

    #[On('loadApotek')]
    public function loadApotek($id): void
    {
        if ($id) {
            $this->apotek = Apotek::find($id);
            $this->form->setApotek($this->apotek);
        } else {
            $this->resetForm();
        }
    }

    public function save(): void
    {
        $saved = $this->form->save();

        $this->dispatch('notify', title: 'success', message: 'User berhasil diupdate!');
        $this->reset();
        Flux::modals()->close();
        // refresh Apoteks Page after saving
        $this->dispatch('refresh-apotek-table');
    }

    public function resetForm()
    {
        $this->reset();
    }

}; ?>

<section class="space-y-6">

    <flux:modal name="add-apotek" :show="$errors->isNotEmpty() || $apotek"
                @close="resetForm()"
                class="w-2xl space-y-6">
        <form wire:submit="save">
            <div>
                <flux:heading size="lg">
                    @if(!$apotek)
                        Add New Apotek
                    @else
                        Update Apotek
                    @endif
                </flux:heading>
                <flux:subheading>
                    @if(is_null($apotek))
                        Fill in the details below.
                    @else
                        Make changes to Apotek.
                    @endif
                </flux:subheading>
            </div>

            <div class="flex flex-col gap-4 my-8">
                <flux:input label="Kode SAP" wire:model.live.debounce="form.sap_id" placeholder="AA00"
                    mask="aa99"
                />
                <div>
                    <label class="mt-4 mb-2">Unit Bisnis</label>
                    <x-tom-select
                            :options="collect($branches)->map(fn($branch) => ['id'=>$branch->id, 'title'=>$branch->name])"
                            wire:model="form.branch_id"/>
                    <flux:error for="form.branch_id"/>
                </div>
                <flux:input label="Name" placeholder="Your name" wire:model.blur="form.name"/>
                <flux:select wire:model="form.store_type" label="{{ __('Business Type') }}" type="text" name="name"
                             required autocomplete="form.store_type" placeholder="Choose Store Type">
                    @foreach(\App\Enums\StoreTypes::cases() as $type)
                        <flux:select.option value="{{ $type }}">
                            {{ $type->label() }}
                        </flux:select.option>
                    @endforeach
                </flux:select>
                <flux:input label="Tgl Operasional" type="date" wire:model="form.operational_date"/>
                <flux:input label="Alamat" placeholder="Jl. Budi Utomo No. 1 Jakarta Pusat"
                            wire:model.blur="form.address"/>
                <flux:input label="Kode Pos" placeholder="10710" wire:model.blur="form.zipcode" mask="99999"/>
                <flux:input label="Longitude" placeholder="0.0000000" wire:model.blur="form.longitude"/>
                <flux:input label="Latitude" placeholder="0.0000000" wire:model.blur="form.latitude"/>
                <flux:field>
                    <flux:label>No Telp</flux:label>
                    <flux:input.group>
                        <flux:input.group.prefix>+62</flux:input.group.prefix>
                        <flux:input wire:model.live.debounce="form.phone" placeholder="812 3456 7890" mask="999 9999 9999"/>
                    </flux:input.group>
                    <flux:error name="form.phone"/>
                </flux:field>
            </div>

            <div class="flex gap-2">
                <flux:modal.close>
                    <flux:button variant="filled">{{ __('Cancel') }}</flux:button>
                </flux:modal.close>

                <flux:button type="submit" variant="primary">
                    @if(!$apotek)
                        Create
                    @else
                        Update
                    @endif
                </flux:button>
            </div>
        </form>
    </flux:modal>
</section>
