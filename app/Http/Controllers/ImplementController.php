<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Faq;
use App\Models\Implement;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ImplementController extends Controller
{
    public function index(Request $request): View
    {
        $query = Implement::where('status', 'active')->with(['brand', 'category', 'images']);

        if ($type = $request->get('type')) {
            $query->where('type', $type);
        }

        if ($brandSlug = $request->get('brand')) {
            $query->whereHas('brand', fn ($q) => $q->where('slug', $brandSlug));
        }

        if ($hp = $request->get('hp')) {
            $query->where('compatible_hp_min', '<=', $hp)->where('compatible_hp_max', '>=', $hp);
        }

        $implements = $query->paginate(12)->withQueryString();

        $types = Category::where('type', 'implement_type')->orderBy('name')->get();
        $brands = Brand::where('status', 'active')->orderBy('name')->get();

        return view('implements.index', compact('implements', 'types', 'brands'));
    }

    public function show(Implement $implement): View
    {
        $implement->load(['brand', 'category', 'images', 'specs']);

        $faqs = Faq::where('faqable_type', 'implement')->where('faqable_id', $implement->id)->orderBy('sort_order')->get();

        $similar = Implement::where('status', 'active')
            ->where('id', '!=', $implement->id)
            ->where('type', $implement->type)
            ->with(['brand', 'images'])
            ->limit(4)
            ->get();

        return view('implements.show', compact('implement', 'faqs', 'similar'));
    }
}
