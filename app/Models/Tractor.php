<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class Tractor extends Model
{
    protected $fillable = [
        'brand_id', 'category_id', 'name', 'slug', 'series', 'hp', 'engine_cc',
        'cylinders', 'fuel_tank_litres', 'transmission_type', 'gearbox', 'pto_hp',
        'lifting_capacity_kg', 'drive_type', 'warranty_text', 'description', 'footer_content', 'launch_year',
        'status', 'is_featured', 'show_comparison', 'created_by',
    ];

    protected function casts(): array
    {
        return [
            'is_featured' => 'boolean',
            'show_comparison' => 'boolean',
        ];
    }

    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(TractorImage::class)->orderBy('sort_order');
    }

    public function prices(): HasMany
    {
        return $this->hasMany(TractorPrice::class);
    }

    public function specs(): HasMany
    {
        return $this->hasMany(TractorSpec::class)->orderBy('sort_order');
    }

    public function seoMeta(): MorphOne
    {
        return $this->morphOne(SeoMeta::class, 'routable');
    }

    public function faqs(): MorphMany
    {
        return $this->morphMany(Faq::class, 'faqable')->orderBy('sort_order');
    }

    public function priceFor(?int $stateId): ?TractorPrice
    {
        return $this->prices()
            ->where('state_id', $stateId)
            ->latest('effective_date')
            ->first()
            ?? $this->prices()->whereNull('state_id')->latest('effective_date')->first();
    }
}
