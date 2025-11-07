<?php

namespace App\Livewire\Forms;

use AllowDynamicProperties;
use App\Models\Asset;
use Livewire\Form;

#[AllowDynamicProperties] class AssetForm extends Form
{
    public ?Asset $asset = null;

    public
        $inventory_id = null,
        $asset_type = '',
        $book_value = null,
        $group = null,
        $category_id = null,
        $subcategory_id = null,
        $brand = null,
        $model = null,
        $description = null,
        $serial_number = null,
        $assigned_to = null,
        $status = null,
        $purchased_on = null,
        $purchase_value = null,
        $sap_voucher_number = null;

    public function rules(): array
    {
        return [
            'inventory_id' => 'nullable|string',
            'asset_type' => 'required|string',
            'book_value' => 'required|integer|in:2,4,8,16',
            'group' => 'required|integer|in:1,2',
            'category_id' => 'required|integer|exists:categories,id',
            'subcategory_id' => 'required|integer|exists:subcategories,id',
            'brand' => 'nullable|string',
            'model' => 'nullable|string',
            'description' => 'required|string|max:255',
            'serial_number' => 'required|string|max:255',
            'assigned_to' => 'nullable|integer|exists:users,id',
            'status' => 'required|string',
            'purchased_on' => 'required|date',
            'purchase_value' => 'required|integer|min:0',
            'sap_voucher_number' => 'required|string|max:255',
        ];
    }

    public function setAsset(?Asset $asset = null):void
    {
        $this->asset = $asset;

        $this->inventory_id = $asset->inventory_id;
        $this->asset_type = $asset->asset_type;
        $this->book_value = $asset->book_value;
        $this->group = $asset->group;
        $this->category_id = $asset->category_id;
        $this->subcategory_id = $asset->subcategory_id;
        $this->brand = $asset->brand;
        $this->model = $asset->model;
        $this->description = $asset->description;
        $this->serial_number = $asset->serial_number;
        $this->assigned_to = $asset->assigned_to;
        $this->status = $asset->status;
        $this->purchased_on = $asset->purchased_on;
        $this->purchase_value = $asset->purchase_value;
        $this->sap_voucher_number = $asset->sap_voucher_number;
    }

    public function save(): void
    {
        $validated = $this->validate();
        $validated['inventory_id'] = 'KFA-'.$this->asset_type.'-'.str()->slug($this->brand).'-'.str()->slug($this->model);

        if (!$this->asset) {
            Asset::create($validated);
        } else {
            $this->asset->update($validated);
        }

        $this->reset();
    }
}
