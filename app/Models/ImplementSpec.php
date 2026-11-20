<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ImplementSpec extends Model
{
    public $timestamps = false;

    protected $fillable = ['implement_id', 'spec_group', 'spec_key', 'spec_value', 'sort_order'];

    public function implement(): BelongsTo
    {
        return $this->belongsTo(Implement::class);
    }
}
