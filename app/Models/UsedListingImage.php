<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UsedListingImage extends Model
{
    protected $fillable = ['used_listing_id', 'path', 'webp_path', 'sort_order'];

    public function usedListing(): BelongsTo
    {
        return $this->belongsTo(UsedListing::class);
    }
}
