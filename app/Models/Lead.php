<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Lead extends Model
{
    protected $fillable = [
        'name', 'phone', 'email', 'state_id', 'district_id', 'tehsil', 'city',
        'interested_tractor_id', 'interested_implement_id', 'source',
        'utm_campaign', 'utm_medium', 'utm_source', 'status',
        'assigned_dealer_id', 'notes',
    ];

    public function state(): BelongsTo
    {
        return $this->belongsTo(State::class);
    }

    public function district(): BelongsTo
    {
        return $this->belongsTo(District::class);
    }

    public function tractor(): BelongsTo
    {
        return $this->belongsTo(Tractor::class, 'interested_tractor_id');
    }

    public function implement(): BelongsTo
    {
        return $this->belongsTo(Implement::class, 'interested_implement_id');
    }

    public function assignedDealer(): BelongsTo
    {
        return $this->belongsTo(Dealer::class, 'assigned_dealer_id');
    }

    public function activity(): HasMany
    {
        return $this->hasMany(LeadActivityLog::class);
    }
}
