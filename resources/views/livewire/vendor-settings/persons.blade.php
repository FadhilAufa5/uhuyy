<?php

use App\Livewire\Forms\PersonsInChargeForm;
use App\Livewire\Forms\VendorForm;
use App\Models\PersonsInCharge;
use App\Models\User;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;
use Livewire\Volt\Component;
use Livewire\WithFileUploads;

new class extends Component
{
    public PersonsInChargeForm $form;

    public string $title = 'Persons In Charge';
    public ?int $vendor_id;

    public function mount(): void
    {
        if (!Auth::check()) {
            dd('User not authenticated');
        }

        $this->vendor_id = auth()->user()->vendors()->first()?->id;

        $this->form->setPics();
    }

    public function updatePersonsInformation(): void
    {
        $this->form->vendor_id = $this->vendor_id;
        $this->form->save();

        $this->dispatch('notify', title: 'success', message: 'Data PIC berhasil diupdate!');
        $this->form->setPics();
    }
}; ?>

<section class="w-full">
    @include('partials.vendor-heading')

    <x-vendor-settings.layout heading="Data PIC Vendor" subheading="Update data PIC disini">
        <form wire:submit="updatePersonsInformation" class="my-6 w-full space-y-6">
            <section class="grid grid-cols-2 gap-6">

                <!-- Responsibilities -->
                @foreach ($form->responsibilities as $responsibility)
                    <flux:fieldset wire:key="responsibility-{{ $responsibility }}" class="bg-zinc-50 dark:bg-zinc-800 drop-shadow-xl p-3">
                        <flux:heading size="lg" class="font-semibold tracking-wider mb-2">{{ ucfirst($responsibility) }}</flux:heading>

                        <!-- Name -->
                        <flux:field>
                            <flux:label for="name-{{ $responsibility }}">Name</flux:label>
                            <flux:input type="text" id="name-{{ $responsibility }}"
                                   wire:model="form.pics.{{ $responsibility }}.name"/>
                            <flux:error name="form.pics.{{ $responsibility }}.name"/>
                        </flux:field>

                        <!-- Email -->
                        <flux:field>
                            <flux:label for="email-{{ $responsibility }}">Email</flux:label>
                            <flux:input type="email" id="email-{{ $responsibility }}"
                                   wire:model="form.pics.{{ $responsibility }}.email"/>
                            <flux:error name="form.pics.{{ $responsibility }}.email"/>
                        </flux:field>

                        <!-- Phone -->
                        <flux:field>
                            <flux:label for="phone-{{ $responsibility }}">Phone</flux:label>
                            <flux:input type="text" id="phone-{{ $responsibility }}"
                                   wire:model="form.pics.{{ $responsibility }}.phone"/>
                            <flux:error name="form.pics.{{ $responsibility }}.phone"/>
                        </flux:field>
                    </flux:fieldset>
                @endforeach

            </section>

            <div class="flex items-center gap-4">
                <flux:button type="submit" variant="primary">Save</flux:button>
            </div>
        </form>

    </x-vendor-settings.layout>
</section>
