<?php

namespace App\Livewire\Forms;

use App\Models\Apotek;
use Illuminate\Validation\Rule;
use Livewire\Form;

class ApotekForm extends Form
{
    public ?Apotek $apotek = null;

    public
        $branch_id,
        $sap_id,
        $name,
        $store_type,
        $operational_date,
        $address,
        $zipcode,
        $latitude,
        $longitude,
        $phone,
        $status = true;

    public function rules(): array
    {
        return [
            'branch_id' => ['required'],
            'sap_id' => ['required', 'min:4', 'max:6',
                Rule::unique('apoteks')->ignore($this->apotek)],
            'name' => ['required', 'min:3'],
            'store_type' => ['nullable'],
            'operational_date' => ['nullable', 'date'],
            'address' => ['nullable', 'string'],
            'zipcode' => ['nullable'],
            'longitude' => ['nullable'],
            'latitude' => ['nullable'],
            'phone' => ['nullable', 'string'],
            'status' => ['boolean'],
        ];
    }

    public function setApotek(?Apotek $apotek = null): void
    {
        $this->apotek = $apotek;

        $this->branch_id = $apotek?->branch_id;
        $this->sap_id = $apotek?->sap_id;
        $this->name = $apotek?->name;
        $this->store_type = $apotek?->store_type;
        $this->operational_date = $apotek?->operational_date;
        $this->address = $apotek?->address;
        $this->zipcode = $apotek?->zipcode;
        $this->longitude = $apotek?->longitude;
        $this->latitude = $apotek?->latitude;
        $this->phone = $apotek?->phone;
        $this->status = $apotek?->status;
    }

    public function save(): void
    {
        $validated = $this->validate();

        if (!$this->apotek) {
            Apotek::create($validated);
        } else {
            $this->apotek->update($validated);
        }

        $this->reset();
    }
}
