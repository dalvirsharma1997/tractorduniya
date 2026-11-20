<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Translation extends Model
{
    protected $fillable = ['entity_type', 'entity_id', 'language_code', 'field_name', 'field_value'];

    public static function valueFor(string $entityType, int $entityId, string $languageCode, string $field): ?string
    {
        return static::where('entity_type', $entityType)
            ->where('entity_id', $entityId)
            ->where('language_code', $languageCode)
            ->where('field_name', $field)
            ->value('field_value');
    }
}
