<?php

namespace App\Http\Controllers;

use App\Models\Brand;
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

        $comparableTractors = $brand->tractors()->where('status', 'active')->limit(4)->get();

        return view('brands.show', compact('brand', 'tractors', 'relatedBrands', 'brandImplements', 'comparableTractors'));
    }
}
