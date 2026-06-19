<?php

namespace App\Models;

use Database\Factories\AddressFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Address extends Model
{
    /** @use HasFactory<AddressFactory> */
    use HasFactory;

    protected $fillable = [
        'street',
        'zip_code',
        'neighborhood',
        'city',
        'state',
    ];

    public function patients(): HasMany
    {
        return $this->hasMany(Patient::class);
    }
}
