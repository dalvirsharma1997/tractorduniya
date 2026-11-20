<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ImplementImage extends Model
{
    protected $fillable = ['implement_id', 'path', 'webp_path', 'sort_order', 'is_primary'];

    protected function casts(): array
    {
        return ['is_primary' => 'boolean'];
    }

    public function implement(): BelongsTo
    {
        return $this->belongsTo(Implement::class);
    }
}
