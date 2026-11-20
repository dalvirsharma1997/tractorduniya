<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RateLimitHit extends Model
{
    public $timestamps = false;

    protected $fillable = ['ip_hash', 'route', 'hit_count', 'window_started_at'];

    protected function casts(): array
    {
        return ['window_started_at' => 'datetime'];
    }
}
