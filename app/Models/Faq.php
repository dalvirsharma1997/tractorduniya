<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Faq extends Model
{
    protected $fillable = ['faqable_type', 'faqable_id', 'question', 'answer', 'sort_order'];

    public function faqable(): ?Model
    {
        return match ($this->faqable_type) {
            'tractor' => Tractor::find($this->faqable_id),
            'implement' => Implement::find($this->faqable_id),
            'brand' => Brand::find($this->faqable_id),
            default => null,
        };
    }
}
