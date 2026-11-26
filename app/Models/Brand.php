<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class Brand extends Model
{
    protected $fillable = ['name', 'slug', 'logo_path', 'country', 'description', 'footer_content', 'status'];

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

    public function seoMeta(): MorphOne
    {
        return $this->morphOne(SeoMeta::class, 'routable');
    }
}
