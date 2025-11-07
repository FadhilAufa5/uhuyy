<?php

use App\Livewire\Forms\VendorForm;
use App\Models\User;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;
use Livewire\Volt\Component;
use Livewire\WithFileUploads;

new class extends Component {
    use WithFileUploads;

    public ?Vendor $vendor = null;
    public VendorForm $form;

    public string $title = 'General Info';

    /**
     * Mount the component.
     */
    public function mount(): void
    {
        $this->vendor = Auth::user()->vendors()?->first();
        $this->form->setVendor($this->vendor);
    }

    /**
     * Update the vendor's basic information for the currently authenticated user.
     */
    public function updateBasicInformation(): void
    {
        $vendor = Auth::user()->vendors()?->first();

        $this->form->save();

        $this->dispatch('notify', title: 'success', message: 'Vendor berhasil diupdate!');
    }
}; ?>

<section class="w-full">
    @include('partials.vendor-heading')

    <x-vendor-settings.layout heading="General Information" subheading="Update your business basic information">
        <form wire:submit="updateBasicInformation" class="my-6 w-full space-y-6">
            <section class="grid grid-cols-2 gap-6">
                <!-- Business Type -->
                <flux:select wire:model="form.business_type" label="{{ __('Jenis Bisnis') }}"
                             name="form.business_type"
                             required autocomplete="form.business_type" placeholder="Choose Business Type">
                    @foreach(\App\Enums\BusinessTypes::cases() as $businessType)
                        <flux:select.option value="{{ $businessType }}">
                            {{ $businessType->label() }}
                        </flux:select.option>
                    @endforeach
                </flux:select>

                <!-- Account Groups -->
                <flux:select wire:model="form.account_group" label="{{ __('Group') }}"
                             name="form.account_group"
                             required autocomplete="form.account_group" placeholder="Choose Account Group">
                    @foreach(\App\Enums\AccountGroups::cases() as $group)
                        <flux:select.option value="{{ $group }}">
                            {{ $group->label() }}
                        </flux:select.option>
                    @endforeach
                </flux:select>

                <!-- Industry Type -->
                <flux:select wire:model="form.industry_type" label="{{ __('Jenis Industri') }}"
                             name="form.industry_type"
                             required autocomplete="industry_type" placeholder="Choose Industry Types">
                    @foreach(\App\Enums\IndustryTypes::cases() as $group)
                        <flux:select.option value="{{ $group }}">
                            {{ $group->label() }}
                        </flux:select.option>
                    @endforeach
                </flux:select>

                <!-- PKP Status -->
                <flux:field class="flex items-center gap-2">
                    <flux:switch wire:model.live="form.is_pkp" label="{{ __('Status PKP') }}"
                                 name="form.is_pkp"/>
                    @if($form->is_pkp)
                        <flux:input wire:model="form.pkp_id" name="form.pkp_id" class="flex-1"/>
                    @endif
                </flux:field>

                <!-- Name -->
                <flux:field>
                    <flux:label>Nama Perusahaan</flux:label>
                    <flux:input.group>
                        <!-- Business Entity -->
                        <flux:select class="max-w-fit" wire:model="form.business_entity"
                                     name="form.business_entity" required>
                            @foreach(\App\Enums\BusinessEntities::cases() as $entity)
                                <flux:select.option value="{{ $entity }}">
                                    {{ $entity->label() }}
                                </flux:select.option>
                            @endforeach
                        </flux:select>
                        <flux:input wire:model="form.name" required
                                    name="form.name"/>
                    </flux:input.group>
                </flux:field>

                <!-- Alias -->
                <flux:input wire:model="form.alias" label="{{ __('Alias') }}" id="alias"
                            name="form.alias"/>

                <!-- Email -->
                <flux:input wire:model="form.email" label="{{ __('Email') }}" type="email"
                            id="email" name="form.email"/>

                <!-- Website -->
                <flux:field>
                    <flux:label>Website</flux:label>
                    <flux:input.group>
                        <flux:input.group.prefix>{{ __('https://') }}</flux:input.group.prefix>
                        <flux:input wire:model="form.website" id="website"
                                    name="form.website"/>
                    </flux:input.group>
                </flux:field>

                <!-- Phone -->
                <flux:fieldset class="bg-zinc-100 dark:bg-zinc-800 rounded-lg p-2 col-span-full drop-shadow-xl">
                    <flux:field>Telepon</flux:field>

                    <div class="space-y-6 p-2">
                        <div class="grid grid-cols-3 gap-4">
                            <flux:input wire:model="form.phone" label="No. Telp."
                                        placeholder="021 1234567" name="form.phone"/>
                            <flux:input wire:model="form.mobile" name="form.mobile"
                                        label="No. Ponsel" placeholder="0812 3456 7890"/>
                            <flux:input wire:model="form.fax" name="form.fax"
                                        label="No. Fax" placeholder="021 123 456"/>

                        </div>
                    </div>
                </flux:fieldset>

                <!-- Alamat -->
                <flux:fieldset class="bg-zinc-100 dark:bg-zinc-800 rounded-lg p-2 col-span-full drop-shadow-xl">
                    <flux:field>Alamat</flux:field>

                    <div class="space-y-6 p-2">
                        <flux:input wire:model="form.street" label="Alamat Kantor"
                                    placeholder="123 Main St" name="form.street"/>
                        <div class="grid grid-cols-2 gap-4">
                            <flux:input wire:model="form.district" label="Kecamatan"
                                        placeholder="Sawah Besar" name="form.district"/>
                            <flux:input wire:model="form.city" name="form.city"
                                        label="Kabupaten/Kota" placeholder="Jakarta Pusat"/>
                            <flux:input wire:model="form.region" name="form.region"
                                        label="Provinsi" placeholder="DKI Jakarta"/>
                            <flux:input wire:model="form.zipcode" name="form.zipcode"
                                        label="Kode Pos" placeholder="10710" mask="99999"/>
                        </div>
                    </div>
                </flux:fieldset>

            </section>

            <div class="flex items-center gap-4">
                <flux:button type="submit" variant="primary">Update</flux:button>
            </div>
        </form>

    </x-vendor-settings.layout>
</section>
