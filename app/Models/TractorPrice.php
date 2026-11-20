<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TractorPrice extends Model
{
    protected $fillable = [
        'tractor_id', 'state_id', 'price_min', 'price_max',
        'on_road_price', 'effective_date', 'created_by',
    ];

    protected function casts(): array
    {
        return ['effective_date' => 'date'];
    }

    public function tractor(): BelongsTo
    {
        return $this->belongsTo(Tractor::class);
    }

    public function state(): BelongsTo
    {
        return $this->belongsTo(State::class);
    }
}
