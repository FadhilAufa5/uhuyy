<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

class Vendor extends Model
{
    protected $fillable = [
        'user_id', 'business_type', 'account_group', 'industry_type', 'is_pkp', 'pkp_id', 'name', 'business_entity',
        'alias', 'street', 'district', 'city', 'zipcode', 'region', 'country_id', 'phone', 'mobile', 'fax', 'email',
        'website'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function pics(): HasMany
    {
        return $this->hasMany(PersonsInCharge::class);
    }

    protected function casts(): array
    {
        return ['is_pkp' => 'boolean'];
    }

    public function vendorBanks(): HasMany
    {
        return $this->hasMany(VendorBank::class);
    }

    public function banks(): HasManyThrough
    {
        return $this->hasManyThrough(
            Bank::class,
            VendorBank::class,
            'vendor_id',
            'id',
            'id',
            'bank_id');
    }
}
