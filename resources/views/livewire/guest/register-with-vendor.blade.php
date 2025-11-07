<?php

use App\Helpers\WithToast;
use App\Livewire\Forms\UserForm;
use App\Livewire\Forms\VendorForm;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('components.layouts.guest.index')] class extends Component {
    use WithToast;

    public ?User $user;
    public UserForm $userForm;
    public VendorForm $vendorForm;

    public int $currentStep = 1;
    public int $totalSteps = 2;

    public function nextStep(): void
    {
        if ($this->currentStep === 1) {
            $this->userForm->validate();
        }
        $this->currentStep++;
    }

    public function prevStep(): void
    {
        $this->currentStep--;
    }

    /**
     * Handle an incoming registration request.
     */
    public function register(): void
    {
        $this->userForm->validate();
        $this->vendorForm->validate();

        $this->userForm->save();

        $this->user = User::find($this->userForm->userId);
        $this->user->assignRole(\App\Enums\Roles::Vendor->value);

        $this->vendorForm->user_id = $this->user->id; // Pass user ID to vendor form
        $this->vendorForm->save();

        Auth::login($this->user);

        $this->redirect(route('dashboard', absolute: false), navigate: true);
        $this->toast('Selamat datang, ' . auth()->user()->name . '.', 'success');
    }
}; ?>

<div class="flex flex-col gap-6 max-w-4xl mx-auto px-8 drop-shadow-2xl rounded-lg bg-zinc-50 dark:bg-zinc-800">
    <x-auth-header title="Register Your Account"
                   description="Enter your details below to create your account and vendor"/>

    <!-- Session Status -->
    <x-auth-session-status class="text-center" :status="session('status')"/>

    <form wire:submit.prevent="register" class="flex flex-col gap-6 mx-auto px-10">
        @csrf
        @if($currentStep == 1)
            <section class="grid grid-cols-2 gap-4">
                <!-- Name -->
                <flux:field class="col-span-full">
                    <flux:label>Name</flux:label>
                    <flux:input wire:model="userForm.name" id="userForm.name" type="text"
                                name="userForm.name" required autofocus autocomplete="name" placeholder="John Doe"/>
                    <flux:error name="userForm.name"/>
                </flux:field>

                <!-- Username -->
                <flux:input wire:model="userForm.username" id="username" label="{{ __('Username') }}"
                            type="text" name="userForm.username" required autocomplete="username"
                            placeholder="john.doe"/>

                <!-- Email Address -->
                <flux:input wire:model="userForm.email" id="email" label="{{ __('Email address') }}"
                            type="email" name="userForm.email" required autocomplete="email"
                            placeholder="john@doe.com"/>

                <!-- Password -->
                <flux:input
                    wire:model="userForm.password"
                    id="password"
                    label="{{ __('Password') }}"
                    type="password"
                    name="userForm.password"
                    required
                    autocomplete="new-password"
                    placeholder="Password"
                    viewable
                />

                <!-- Confirm Password -->
                <flux:input
                    wire:model="userForm.password_confirmation"
                    id="password_confirmation"
                    label="{{ __('Confirm password') }}"
                    type="password"
                    name="userForm.password_confirmation"
                    required
                    autocomplete="new-password"
                    placeholder="Confirm password"
                    viewable
                />
            </section>
        @endif

        @if($currentStep == 2)
            <section class="grid grid-cols-2 gap-6">
                <!-- Business Type -->
                <flux:select wire:model="vendorForm.business_type" label="{{ __('Jenis Bisnis') }}"
                             name="vendorForm.business_type"
                             required autocomplete="business_type" placeholder="Choose Business Type">
                    @foreach(\App\Enums\BusinessTypes::cases() as $businessType)
                        <flux:select.option value="{{ $businessType }}">
                            {{ $businessType->label() }}
                        </flux:select.option>
                    @endforeach
                </flux:select>

                <!-- Account Groups -->
                <flux:select wire:model="vendorForm.account_group" label="{{ __('Group') }}"
                             name="vendorForm.account_group"
                             required autocomplete="account_group" placeholder="Choose Account Group">
                    @foreach(\App\Enums\AccountGroups::cases() as $group)
                        <flux:select.option value="{{ $group }}">
                            {{ $group->label() }}
                        </flux:select.option>
                    @endforeach
                </flux:select>

                <!-- Industry Type -->
                <flux:select wire:model="vendorForm.industry_type" label="{{ __('Jenis Industri') }}"
                             name="vendorForm.industry_type"
                             required autocomplete="industry_type" placeholder="Choose Industry Types">
                    @foreach(\App\Enums\IndustryTypes::cases() as $group)
                        <flux:select.option value="{{ $group }}">
                            {{ $group->label() }}
                        </flux:select.option>
                    @endforeach
                </flux:select>

                <!-- PKP Status -->
                <flux:field class="flex">
                    <flux:switch wire:model.live="vendorForm.is_pkp" label="{{ __('Status PKP') }}"
                                 name="vendorForm.is_pkp"/>
                    @if($vendorForm->is_pkp)
                        <flux:input wire:model="vendorForm.pkp_id" name="vendorForm.pkp_id"/>
                    @endif
                </flux:field>
                <!-- Name -->
                <flux:field>
                    <flux:label>Nama Perusahaan</flux:label>
                    <flux:input.group>
                        <!-- Business Entity -->
                        <flux:select class="max-w-fit" wire:model="vendorForm.business_entity"
                                     name="vendorForm.business_entity" required>
                            @foreach(\App\Enums\BusinessEntities::cases() as $entity)
                                <flux:select.option value="{{ $entity }}">
                                    {{ $entity->label() }}
                                </flux:select.option>
                            @endforeach
                        </flux:select>
                        <flux:input wire:model="vendorForm.name" required
                                    name="vendorForm.name"/>
                    </flux:input.group>
                </flux:field>

                <!-- Alias -->
                <flux:input wire:model="vendorForm.alias" label="{{ __('Alias') }}" id="alias"
                            name="vendorForm.alias"/>

                <!-- Email -->
                <flux:input wire:model="vendorForm.email" label="{{ __('Email') }}" type="email"
                            id="email" name="vendorForm.email"/>

                <!-- Website -->
                <flux:field>
                    <flux:label>Website</flux:label>
                    <flux:input.group>
                        <flux:input.group.prefix>{{ __('https://') }}</flux:input.group.prefix>
                        <flux:input wire:model="vendorForm.website" id="website"
                                    name="vendorForm.website"/>
                    </flux:input.group>
                </flux:field>

                <!-- Phone -->
                <flux:fieldset class="bg-zinc-100 dark:bg-zinc-800 rounded-lg p-2 col-span-full drop-shadow-xl">
                    <flux:field>Telepon</flux:field>

                    <div class="space-y-6 p-2">
                        <div class="grid grid-cols-3 gap-4">
                            <flux:input wire:model="vendorForm.phone" label="No. Telp."
                                        placeholder="021 1234567" name="vendorForm.phone"/>
                            <flux:input wire:model="vendorForm.mobile" name="vendorForm.mobile"
                                        label="No. Ponsel" placeholder="0812 3456 7890"/>
                            <flux:input wire:model="vendorForm.fax" name="vendorForm.fax"
                                        label="No. Fax" placeholder="021 123 456"/>

                        </div>
                    </div>
                </flux:fieldset>

                <!-- Alamat -->
                <flux:fieldset class="bg-zinc-100 dark:bg-zinc-800 rounded-lg p-2 col-span-full drop-shadow-xl">
                    <flux:field>Alamat</flux:field>

                    <div class="space-y-6 p-2">
                        <flux:input wire:model="vendorForm.street" label="Alamat Kantor"
                                    placeholder="123 Main St" name="vendorForm.street"/>
                        <div class="grid grid-cols-2 gap-4">
                            <flux:input wire:model="vendorForm.district" label="Kecamatan"
                                        placeholder="Sawah Besar" name="vendorForm.district"/>
                            <flux:input wire:model="vendorForm.city" name="vendorForm.city"
                                        label="Kabupaten/Kota" placeholder="Jakarta Pusat"/>
                            <flux:input wire:model="vendorForm.region" name="vendorForm.region"
                                        label="Provinsi" placeholder="DKI Jakarta"/>
                            <flux:input wire:model="vendorForm.zipcode" name="vendorForm.zipcode"
                                        label="Kode Pos" placeholder="10710" mask="99999"/>
                        </div>
                    </div>
                </flux:fieldset>

            </section>
        @endif

        <div class="flex items-center gap-4 justify-center my-4">
            <flux:button href="{{ route('home') }}" variant="danger">Cancel</flux:button>
            <flux:button :disabled="$currentStep == 1"
                         wire:click="prevStep">Back
            </flux:button>
            @if($currentStep < $totalSteps)
                <flux:button wire:click="nextStep">Next</flux:button>
            @endif
            @if($currentStep == $totalSteps)
                <flux:button variant="primary" type="submit"
                             wire:loading.attr="disabled">
                    {{ __('Submit') }}
                </flux:button>
            @endif
        </div>
    </form>

</div>
