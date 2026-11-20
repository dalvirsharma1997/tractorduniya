<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TractorSpec extends Model
{
    public $timestamps = false;

    protected $fillable = ['tractor_id', 'spec_group', 'spec_key', 'spec_value', 'sort_order'];

    public function tractor(): BelongsTo
    {
        return $this->belongsTo(Tractor::class);
    }
}
