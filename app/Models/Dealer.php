<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Dealer extends Model
{
    protected $fillable = [
        'name', 'slug', 'state_id', 'district_id', 'city', 'address', 'phone',
        'whatsapp', 'email', 'lat', 'lng', 'is_verified', 'status', 'created_by',
    ];

    protected function casts(): array
    {
        return ['is_verified' => 'boolean'];
    }

    public function state(): BelongsTo
    {
        return $this->belongsTo(State::class);
    }

    public function district(): BelongsTo
    {
        return $this->belongsTo(District::class);
    }

    public function brands(): BelongsToMany
    {
        return $this->belongsToMany(Brand::class, 'dealer_brands');
    }

    public function leads(): HasMany
    {
        return $this->hasMany(Lead::class, 'assigned_dealer_id');
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }
}
