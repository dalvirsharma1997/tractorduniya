<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    protected $fillable = [
        'title', 'slug', 'embed_url', 'thumbnail', 'related_type', 'related_id', 'published_at',
    ];

    protected function casts(): array
    {
        return ['published_at' => 'datetime'];
    }

    public function related(): ?Model
    {
        return match ($this->related_type) {
            'tractor' => Tractor::find($this->related_id),
            'implement' => Implement::find($this->related_id),
            'brand' => Brand::find($this->related_id),
            default => null,
        };
    }
}
