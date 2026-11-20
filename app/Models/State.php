<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class State extends Model
{
    protected $fillable = ['name', 'slug', 'rto_tax_notes'];

    public function districts(): HasMany
    {
        return $this->hasMany(District::class);
    }

    public function tractorPrices(): HasMany
    {
        return $this->hasMany(TractorPrice::class);
    }

    public function dealers(): HasMany
    {
        return $this->hasMany(Dealer::class);
    }
}
