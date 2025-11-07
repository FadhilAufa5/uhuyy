<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Asset extends Model
{

    protected $fillable = [
        'inventory_id',
        'book_value',
        'asset_type',
        'group',
        'category_id',
        'subcategory_id',
        'brand',
        'model',
        'description',
        'serial_number',
        'assigned_to',
        'status',
        'purchased_on',
        'purchase_value',
        'sap_voucher_number',
        'image',
    ];

    protected $casts = [
        'id' => 'string', // ✅ ensure UUID is treated as string
    ];

    protected static function booted(): void
    {
        static::creating(function ($asset) {
            if (empty($asset->id)) {
                $asset->id = (string) Str::uuid();
            }
        });
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function subcategory(): BelongsTo
    {
        return $this->belongsTo(Subcategory::class);
    }

    public function assignee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to','id');
    }

}
