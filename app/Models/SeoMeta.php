<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SeoMeta extends Model
{
    protected $table = 'seo_meta';

    protected $fillable = [
        'routable_type', 'routable_id', 'path', 'meta_title',
        'meta_description', 'canonical_url', 'og_image',
    ];
}
