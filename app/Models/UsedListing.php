<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class UsedListing extends Model
{
    protected $fillable = [
        'listing_type', 'tractor_id', 'implement_id', 'seller_name', 'seller_phone',
        'city', 'state_id', 'district_id', 'year_of_purchase', 'hours_used',
        'condition', 'asking_price', 'description', 'status', 'rejected_reason',
    ];

    public function tractor(): BelongsTo
    {
        return $this->belongsTo(Tractor::class);
    }

    public function implement(): BelongsTo
    {
        return $this->belongsTo(Implement::class);
    }

    public function state(): BelongsTo
    {
        return $this->belongsTo(State::class);
    }

    public function district(): BelongsTo
    {
        return $this->belongsTo(District::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(UsedListingImage::class)->orderBy('sort_order');
    }
}
