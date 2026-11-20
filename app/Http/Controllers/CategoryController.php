<?php

namespace App\Http\Controllers;

use App\Models\Category;
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

        return view('categories.tractors', compact('category', 'tractors'));
    }
}
