<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Bank extends Model
{
    protected $fillable = [
        'code',
        'name',
    ];

    public function vendors(): BelongsToMany
    {
        return $this->belongsToMany(Vendor::class)->through('vendor_bank', 'bank_id', 'vendor_id');
    }
}
