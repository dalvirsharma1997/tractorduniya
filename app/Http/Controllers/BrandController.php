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

        return view('brands.show', compact('brand', 'tractors'));
    }
}
