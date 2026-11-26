<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Faq;
use App\Models\Implement;
use App\Models\Tractor;
use Illuminate\View\View;

class BrandController extends Controller
{
    public function show(Brand $brand): View
    {
        $tractors = $brand->tractors()
            ->where('status', 'active')
            ->with(['brand', 'images', 'prices'])
            ->paginate(12);

        $relatedBrands = Brand::where('status', 'active')
            ->where('id', '!=', $brand->id)
            ->orderBy('name')
            ->limit(8)
            ->get();

        $brandImplements = $brand->implements()
            ->where('status', 'active')
            ->with(['brand', 'images'])
            ->limit(4)
            ->get();

        if ($brandImplements->count() < 4) {
            $fallbackImplements = Implement::where('status', 'active')
                ->where('brand_id', '!=', $brand->id)
                ->with(['brand', 'images'])
                ->latest()
                ->limit(4 - $brandImplements->count())
                ->get();
            $brandImplements = $brandImplements->concat($fallbackImplements);
        }

        $comparableTractors = $brand->tractors()->where('status', 'active')->with(['brand', 'images', 'prices'])->limit(4)->get();

        if ($comparableTractors->count() < 2) {
            $fallbackTractors = Tractor::where('status', 'active')
                ->where('brand_id', '!=', $brand->id)
                ->with(['brand', 'images', 'prices'])
                ->inRandomOrder()
                ->limit(4 - $comparableTractors->count())
                ->get();
            $comparableTractors = $comparableTractors->concat($fallbackTractors)->values();
        }

        $faqs = Faq::where('faqable_type', 'brand')->where('faqable_id', $brand->id)->orderBy('sort_order')->get();

        return view('brands.show', compact('brand', 'tractors', 'relatedBrands', 'brandImplements', 'comparableTractors', 'faqs'));
    }
}
