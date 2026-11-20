<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $fillable = [
        'reviewable_type', 'reviewable_id', 'reviewer_name', 'rating', 'title', 'body', 'status',
    ];

    public function reviewable(): ?Model
    {
        return match ($this->reviewable_type) {
            'tractor' => Tractor::find($this->reviewable_id),
            'implement' => Implement::find($this->reviewable_id),
            'brand' => Brand::find($this->reviewable_id),
            default => null,
        };
    }

    public static function averageRatingFor(string $type, int $id): float
    {
        return (float) static::where('reviewable_type', $type)
            ->where('reviewable_id', $id)
            ->where('status', 'approved')
            ->avg('rating');
    }
}
