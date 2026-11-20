<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Brand extends Model
{
    protected $fillable = ['name', 'slug', 'logo_path', 'country', 'description', 'status'];

    public function tractors(): HasMany
    {
        return $this->hasMany(Tractor::class);
    }

    public function implements(): HasMany
    {
        return $this->hasMany(Implement::class);
    }

    public function dealers(): BelongsToMany
    {
        return $this->belongsToMany(Dealer::class, 'dealer_brands');
    }
}
