<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Dealer;
use App\Models\Faq;
use App\Models\State;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DealerController extends Controller
{
    public function index(Request $request): View
    {
        $query = Dealer::where('status', 'active')->with(['state', 'district', 'brands']);

        if ($stateId = $request->get('state')) {
            $query->where('state_id', $stateId);
        }

        if ($districtId = $request->get('district')) {
            $query->where('district_id', $districtId);
        }

        if ($brandSlug = $request->get('brand')) {
            $query->whereHas('brands', fn ($q) => $q->where('slug', $brandSlug));
        }

        $dealers = $query->paginate(12)->withQueryString();

        $states = State::orderBy('name')->get();
        $brands = Brand::where('status', 'active')->orderBy('name')->get();

        return view('dealers.index', compact('dealers', 'states', 'brands'));
    }

    public function show(Dealer $dealer): View
    {
        $dealer->load(['state', 'district', 'brands']);

        $faqs = Faq::where('faqable_type', 'dealer')->where('faqable_id', $dealer->id)->orderBy('sort_order')->get();

        return view('dealers.show', compact('dealer', 'faqs'));
    }
}
