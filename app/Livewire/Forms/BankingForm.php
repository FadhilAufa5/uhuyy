<?php

namespace App\Livewire\Forms;

use App\Models\VendorBank;
use Livewire\Form;

class BankingForm extends Form
{
    public ?VendorBank $vendorBank = null;
    public ?int $vendor_id = null;
    public ?int $bank_id = null;
    public ?string $bank_branch = null;
    public ?string $bank_address = null;
    public ?string $account_number = null;
    public ?string $account_holder_name = null;
    public ?int $country_id = null;

    public function mount(): void
    {
        $this->vendor_id = auth()->user()->vendors()->first()->id;
    }

    public function rules(): array
    {
        return [
            'bank_id' => 'required|integer|exists:banks,id',
            'bank_branch' => 'required|string|min:3|max:100',
            'bank_address' => 'required|string|min:3|max:255',
            'account_number' => 'required|string|min:5|max:20',
            'account_holder_name' => 'required|string|min:3|max:255',
            'country_id' => 'nullable|integer|exists:countries,id',
        ];
    }

    public function setVendorBank(?VendorBank $vendorBank = null): void
    {
        $this->vendorBank = $vendorBank;

        $this->bank_id = $vendorBank?->bank_id;
        $this->bank_branch = $vendorBank?->bank_branch;
        $this->bank_address  = $vendorBank?->bank_address;
        $this->account_number = $vendorBank?->account_number;
        $this->account_holder_name = $vendorBank?->account_holder_name;
        $this->country_id = $vendorBank?->country_id;
    }

    public function save(): void
    {
        $validated = $this->validate();
        $validated['vendor_id'] = $this->vendor_id;

        $conditions = [
            'vendor_id' => $this->vendor_id,
            'bank_id' => $validated['bank_id'],
            'account_number' => $validated['account_number'],
        ];

        VendorBank::updateOrCreate($conditions, $validated);

        $this->reset();
    }
}
