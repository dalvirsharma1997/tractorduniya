<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Faq;
use App\Models\Implement;
use App\Models\Review;
use App\Models\State;
use App\Models\Tractor;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TractorController extends Controller
{
    public function index(Request $request): View
    {
        $query = Tractor::where('status', 'active')->with(['brand', 'images', 'prices']);

        if ($brandSlug = $request->get('brand')) {
            $query->whereHas('brand', fn ($q) => $q->where('slug', $brandSlug));
        }

        if ($hpMin = $request->get('hp_min')) {
            $query->where('hp', '>=', $hpMin);
        }

        if ($hpMax = $request->get('hp_max')) {
            $query->where('hp', '<=', $hpMax);
        }

        if ($priceMax = $request->get('price_max')) {
            $query->whereHas('prices', fn ($q) => $q->where('price_min', '<=', $priceMax));
        }

        if ($priceMin = $request->get('price_min')) {
            $query->whereHas('prices', fn ($q) => $q->where('price_max', '>=', $priceMin));
        }

        if ($driveType = $request->get('drive_type')) {
            $query->where('drive_type', $driveType);
        }

        if ($stateSlug = $request->get('state')) {
            $query->whereHas('prices.state', fn ($q) => $q->where('slug', $stateSlug));
        }

        $tractors = $query->orderByDesc('is_featured')->paginate(12)->withQueryString();

        $brands = Brand::where('status', 'active')->orderBy('name')->get();
        $states = State::orderBy('name')->get();

        return view('tractors.index', compact('tractors', 'brands', 'states'));
    }

    public function show(Brand $brand, Tractor $tractor): View
    {
        abort_unless($tractor->brand_id === $brand->id, 404);

        $tractor->load(['brand', 'category', 'images', 'prices.state', 'specs']);

        $states = State::orderBy('name')->get();

        $similar = Tractor::where('status', 'active')
            ->where('id', '!=', $tractor->id)
            ->where(function ($q) use ($tractor) {
                $q->where('brand_id', $tractor->brand_id)
                    ->orWhereBetween('hp', [$tractor->hp - 10, $tractor->hp + 10]);
            })
            ->with(['brand', 'images', 'prices'])
            ->limit(4)
            ->get();

        $reviews = Review::where('reviewable_type', 'tractor')
            ->where('reviewable_id', $tractor->id)
            ->where('status', 'approved')
            ->latest()
            ->get();

        $averageRating = Review::averageRatingFor('tractor', $tractor->id);

        $faqs = Faq::where('faqable_type', 'tractor')
            ->where('faqable_id', $tractor->id)
            ->orderBy('sort_order')
            ->get();

        $compatibleImplements = Implement::where('status', 'active')
            ->where('compatible_hp_min', '<=', $tractor->hp)
            ->where('compatible_hp_max', '>=', $tractor->hp)
            ->with(['brand', 'images'])
            ->limit(4)
            ->get();

        $allTractors = Tractor::where('status', 'active')
            ->where('id', '!=', $tractor->id)
            ->with('brand')
            ->orderBy('name')
            ->get(['id', 'name', 'slug', 'brand_id']);

        return view('tractors.show', compact('tractor', 'states', 'similar', 'reviews', 'averageRating', 'faqs', 'compatibleImplements', 'allTractors'));
    }

    public function compare(string $slugs): View
    {
        $tractorSlugs = explode('-vs-', $slugs);

        $tractors = Tractor::whereIn('slug', $tractorSlugs)
            ->with(['brand', 'images', 'prices', 'specs'])
            ->get()
            ->sortBy(fn ($t) => array_search($t->slug, $tractorSlugs))
            ->values();

        abort_if($tractors->count() < 2, 404);

        return view('tractors.compare', compact('tractors'));
    }
}
