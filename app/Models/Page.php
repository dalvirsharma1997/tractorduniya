<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class Page extends Model
{
    protected $fillable = ['slug', 'title', 'body'];

    public function seoMeta(): MorphOne
    {
        return $this->morphOne(SeoMeta::class, 'routable');
    }
}
