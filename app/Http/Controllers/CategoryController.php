<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Implement;
use App\Models\Tractor;
use Illuminate\View\View;

class CategoryController extends Controller
{
    public function show(Category $category): View
    {
        if ($category->type === 'implement_type') {
            $implements = $category->implements()->where('status', 'active')->with(['brand', 'images'])->paginate(12);

            $hpRange = $category->implements()->where('status', 'active')
                ->selectRaw('MIN(compatible_hp_min) as min_hp, MAX(compatible_hp_max) as max_hp')
                ->first();

            $comparableTractors = Tractor::where('status', 'active')
                ->when($hpRange?->min_hp, fn ($q) => $q->where('hp', '>=', $hpRange->min_hp))
                ->when($hpRange?->max_hp, fn ($q) => $q->where('hp', '<=', $hpRange->max_hp))
                ->with(['brand', 'images'])
                ->limit(4)
                ->get();

            return view('categories.implements', compact('category', 'implements', 'comparableTractors'));
        }

        $tractors = $category->tractors()->where('status', 'active')->with(['brand', 'images', 'prices'])->paginate(12);

        $categoryImplements = Implement::where('status', 'active')
            ->with(['brand', 'images'])
            ->latest()
            ->limit(4)
            ->get();

        $comparableTractors = $category->tractors()->where('status', 'active')->limit(4)->get();

        return view('categories.tractors', compact('category', 'tractors', 'categoryImplements', 'comparableTractors'));
    }
}
