<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Apotek extends Model
{
    protected $fillable = ['branch_id',
                           'sap_id',
                           'name',
                           'store_type',
                           'operational_date',
                           'address',
                           'zipcode',
                           'latitude',
                           'longitude',
                           'phone',
                           'status'];

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class, 'branch_id', 'id');
    }

}
