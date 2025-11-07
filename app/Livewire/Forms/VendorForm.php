<?php

namespace App\Livewire\Forms;

use App\Models\Vendor;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Validate;
use Livewire\Form;

class VendorForm extends Form
{
    public ?Vendor $vendor = null;

    public $user_id = null;
    public ?string $business_type = null;
    public ?string $account_group = null;
    public ?string $industry_type = null;
    public ?bool $is_pkp = false;
    public ?string $pkp_id = '';
    public ?string $name = null;
    public ?string $business_entity = null;
    public ?string $alias = null;
    public ?string $street = null;
    public ?string $district = null;
    public ?string $city = null;
    public ?string $zipcode = null;
    public ?string $region = null;
    public $country_id = null;
    public ?string $phone = null;
    public ?string $mobile = null;
    public ?string $fax = null;
    public ?string $email = null;
    public ?string $website = null;

    public function rules(): array
    {
        return [
            'user_id'         => 'nullable|integer|exists:users,id',
            'business_type'   => ['required', 'string'],
            'account_group'   => 'required',
            'industry_type'   => 'required',
            'is_pkp'          => ['required', 'boolean'],
            'pkp_id'          => ['nullable','required_if:is_pkp,1', 'string'],
            'name'            => ['required', 'string'],
            'business_entity' => 'required',
            'alias'           => ['required', 'string'],
            'street'          => ['required', 'string'],
            'district'        => ['required', 'string'],
            'city'            => ['required', 'string'],
            'zipcode'         => ['required', 'integer'],
            'region'          => ['required', 'string'],
            'country_id'      => 'nullable',
            'phone'           => ['required', 'string'],
            'mobile'          => ['required', 'string'],
            'fax'             => ['required', 'string'],
            'email'           => ['required', 'string', 'email',
                                  Rule::unique('vendors')->ignore($this->vendor)],
            'website'         => ['required', 'string'],
        ];
    }

    public function setVendor(?Vendor $vendor = null): void
    {
        $this->vendor = $vendor;

        $this->business_type = $vendor?->business_type;
        $this->account_group = $vendor?->account_group;
        $this->industry_type = $vendor?->industry_type;
        $this->is_pkp = $vendor?->is_pkp;
        $this->pkp_id = $vendor?->pkp_id;
        $this->name = $vendor?->name;
        $this->business_entity = $vendor?->business_entity;
        $this->alias = $vendor?->alias;
        $this->street = $vendor?->street;
        $this->district = $vendor?->district;
        $this->city = $vendor?->city;
        $this->zipcode = $vendor?->zipcode;
        $this->region = $vendor?->region;
        $this->country_id = $vendor?->country_id;
        $this->phone = $vendor?->phone;
        $this->mobile = $vendor?->mobile;
        $this->fax = $vendor?->fax;
        $this->email = $vendor?->email;
        $this->website = $vendor?->website;
    }

    /**
     * @throws \Exception
     */
    public function save(): void
    {
        $validated = $this->validate();

        if (!$this->user_id) {
            return;
        }

        if (!$this->vendor) {
            Vendor::create($validated);
        } else {
            $this->vendor->update($validated);
        }

        $this->reset();
    }
}
