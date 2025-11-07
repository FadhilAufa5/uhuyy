<?php

use App\Helpers\WithToast;
use App\Livewire\Assets\Table;
use App\Livewire\Forms\AssetForm;
use App\Models\Asset;
use App\Models\Category;
use App\Models\Subcategory;
use Flux\Flux;
use Illuminate\Support\Collection;
use Livewire\Attributes\On;
use Livewire\Volt\Component;

new class extends Component {
    use WithToast;

    public AssetForm $form;
    public ?Asset $asset = null;

    public int $currentStep = 1;
    public int $totalSteps = 2;

    public function mount(): void
    {
        if (!$this->asset) {
            $this->form->reset();
        }
    }

    #[On('edit-asset')]
    public function editAsset($id): void
    {
        abort_unless(Str::isUuid($id), 400, 'Invalid asset ID');
        $asset = Asset::find($id);
        $this->form->setAsset($asset);
        Flux::modal('add-asset')->show();
    }

    public function with(): array
    {
        return [
            'categories' => Category::all(),
            'subcategories' => Subcategory::all()
        ];
    }

    public function validateForm(): void
    {
        if ($this->currentStep === 1) {
            $this->validate([
                'form.asset_type' => 'required|string',
                'form.book_value' => 'required|integer|in:2,4,8,16',
                'form.group' => 'required|integer|in:1,2',
            ]);
        } elseif ($this->currentStep === 2) {
            $this->validate([
                'form.brand' => 'required|max:255',
                'form.model' => 'required|min:10|max:255',
                'form.description' => 'required|min:10|max:255',
            ]);
        }
    }

    public function save(): void
    {
        $this->form->validate();
        $this->form->save();
        $this->resetForm();
        Flux::modals()->close();
        $this->dispatch('reload-assets')->to(Table::class);
        $this->toast('Asset berhasil diupdate!', 'success');
    }

    public function nextStep(): void
    {
        $this->validateForm();
        if ($this->currentStep < $this->totalSteps) {
            $this->currentStep++;
        }
    }

    public function prevStep(): void
    {
        $this->currentStep--;
    }

    public function resetForm(): void
    {
        $this->form->reset();
        $this->reset();
    }
}; ?>


<flux:modal name="add-asset" :show="$errors->isNotEmpty() || $asset" :dismissible="false"
            @close="resetForm()" class="md:w-5xl min-w-4xl">
    <div class="grid grid-cols-3 items-center mb-6 gap-10 text-center">
        <div class="flex justify-center flex-col">
            <flux:button class="!rounded-full !w-8 !h-8 mx-auto !bg-emerald-500 text-zinc-50 font-bold text-xl">1
            </flux:button>
            <p class="mt-2.5 font-bold text-emerald-500">General Information</p>
        </div>
        <flux:separator class="text-emerald-500 disabled:text-zinc-400" :disabled="$currentStep < 2"/>
        <div class="flex justify-center flex-col">
            <flux:button
                class="!rounded-full !w-8 !h-8 mx-auto font-bold text-xl text-zinc-50 disabled:!text-zinc-400 !bg-emerald-500 disabled:!bg-zinc-500"
                :disabled="$currentStep < 2">
                2
            </flux:button>
            <p class="mt-2.5 font-bold {{ $currentStep < 2 ? 'text-zinc-500' : 'text-emerald-500' }}">
                Detil Aset
            </p>
        </div>
    </div>

    <form wire:submit="save" enctype="multipart/form-data">

        <!-- Step 1: Personal Information -->
        @if ($currentStep == 1)
            <div class="shadow-lg space-y-2 overflow-hidden p-4 mb-6">
                <div class="text-lg bg-emerald-500 p-3 mb-8 rounded">
                    Step 1: General Information
                </div>
                <!-- Jenis Aset -->
                <flux:select label="Jenis Aset" id="form.asset_type" name="form.asset_type"
                             wire:model="form.asset_type">
                    <flux:select.option value="" selected disabled>Pilih jenis aset...</flux:select.option>
                    <flux:select.option value="{{ __('asset') }}">{{ __('Aset') }}</flux:select.option>
                    <flux:select.option value="{{ __('inventory') }}">{{ __('Inventaris') }}</flux:select.option>
                </flux:select>

                <!-- Masa Buku -->
                <flux:select label="Masa Buku" id="form.book_value" name="form.book_value" wire:model="form.book_value">
                    <flux:select.option value="" selected hidden>Pilih masa buku aset...</flux:select.option>
                    @foreach([2,4,8,16] as $value)
                        <flux:select.option value="{{ $value }}">{{ $value }}</flux:select.option>
                    @endforeach
                </flux:select>

                <!-- Golongan -->
                <flux:select label="Golongan" id="form.group" name="form.group" wire:model="form.group">
                    <flux:select.option value="" selected hidden>Pilih golongan aset ...</flux:select.option>
                    @foreach([1,2] as $value)
                        <flux:select.option value="{{ $value }}">{{ $value }}</flux:select.option>
                    @endforeach
                </flux:select>

                <!-- Kategori -->
                <flux:select label="Kategori" id="form.category_id" name="form.category_id"
                             wire:model="form.category_id">
                    <flux:select.option value="" selected hidden>Pilih kategori aset ...</flux:select.option>
                    @foreach($categories as $category)
                        <flux:select.option value="{{ $category->id }}">
                            {{ Str::headline($category->name) }}
                        </flux:select.option>
                    @endforeach
                </flux:select>

                <!-- Subkategori -->
                <flux:select label="Subkategori" id="form.subcategory_id" name="form.subcategory_id"
                             wire:model="form.subcategory_id">
                    <flux:select.option value="" selected hidden>Pilih subkategori aset ...</flux:select.option>
                    @foreach($subcategories as $subcategory)
                        <flux:select.option value="{{ $subcategory->id }}">
                            {{ Str::headline($subcategory->name) }}
                        </flux:select.option>
                    @endforeach
                </flux:select>
            </div>

            <!-- Step 2: Asset Details -->
        @elseif($currentStep == 2)
            <div class="shadow-lg space-y-2 overflow-hidden p-4 mb-6">
                <flux:field class="text-lg bg-emerald-500 text-slate-50 p-3 rounded-lg">
                    Step 2: Detil Aset
                </flux:field>
                <!-- Brand -->
                <flux:input label="Brand" id="form.brand" name="form.brand" type="text" class="block w-full mt-1"
                            wire:model="form.brand" required/>
                <flux:input label="Model" id="form.model" name="form.model" type="text" class="block w-full mt-1"
                            wire:model="form.model" required/>
                <flux:input label="Deskripsi" id="form.description" name="form.description" type="text"
                            class="block w-full mt-1"
                            wire:model="form.description" required/>
                <flux:input label="Serial Number" id="form.serial_number" name="form.serial_number" type="text"
                            class="block w-full mt-1"
                            wire:model="form.serial_number" required/>
                <flux:select label="Kondisi" id="form.status" name="form.status" wire:model="form.status">
                    <flux:select.option value="" disabled>Pilih kondisi aset...</flux:select.option>
                    @foreach(['baru','bekas','rekondisi'] as $value)
                        <flux:select.option value="{{ $value }}">{{ ucfirst($value) }}</flux:select.option>
                    @endforeach
                </flux:select>
                <flux:input label="Tanggal Perolehan" id="form.purchased_on" name="form.purchased_on" type="date"
                            class="block w-full mt-1"
                            wire:model="form.purchased_on" required/>
                <flux:input label="Nilai Perolehan" id="form.purchase_value" name="form.purchase_value" type="number"
                            class="block w-full mt-1"
                            wire:model="form.purchase_value" required/>
                <flux:input label="No Voucher SAP" id="form.sap_voucher_number" name="form.sap_voucher_number"
                            type="text"
                            class="block w-full mt-1"
                            wire:model="form.sap_voucher_number" required/>
            </div>
        @endif

        <div class="flex gap-2">
            <flux:modal.close>
                <flux:button variant="danger" wire:click="resetForm()">{{ __('Cancel') }}</flux:button>
            </flux:modal.close>
            <flux:button
                :disabled="$currentStep==1"
                wire:click="prevStep">Back
            </flux:button>
            <flux:button
                :disabled="$currentStep == $totalSteps"
                wire:click="nextStep">Next
            </flux:button>
            <flux:button :hidden="$currentStep != $totalSteps" type="submit" variant="primary">
                Save
            </flux:button>
        </div>
    </form>
</flux:modal>

