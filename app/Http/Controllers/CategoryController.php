<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Implement;
use Illuminate\View\View;

class CategoryController extends Controller
{
    public function show(Category $category): View
    {
        if ($category->type === 'implement_type') {
            $implements = $category->implements()->where('status', 'active')->with(['brand', 'images'])->paginate(12);

            return view('categories.implements', compact('category', 'implements'));
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
