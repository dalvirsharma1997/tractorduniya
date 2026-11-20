<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;

class News extends Model
{
    protected $table = 'news';

    protected $fillable = [
        'author_id', 'title', 'slug', 'category', 'cover_image', 'excerpt',
        'body', 'status', 'published_at', 'meta_title', 'meta_description',
    ];

    protected function casts(): array
    {
        return ['published_at' => 'datetime'];
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function translations(): Builder
    {
        return Translation::query()->where('entity_type', 'news')->where('entity_id', $this->id);
    }
}
