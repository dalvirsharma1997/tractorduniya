<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use App\Models\Blog;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Implement;
use App\Models\Tractor;
use Illuminate\View\View;

class HomeController extends Controller
{
    /**
     * HP buckets shown in the "Tractors by HP" homepage section.
     * Each entry is [label, hp_min, hp_max] (hp_max null means "and above").
     */
    protected const HP_RANGES = [
        ['Below 20 HP', null, 19],
        ['20–30 HP', 20, 30],
        ['31–40 HP', 31, 40],
        ['41–50 HP', 41, 50],
        ['51–60 HP', 51, 60],
        ['Above 60 HP', 61, null],
    ];

    /**
     * Budget buckets (in rupees) shown in the "Tractors by Budget" section.
     * Each entry is [label, price_min, price_max] (price_max null means "and above").
     */
    protected const BUDGET_RANGES = [
        ['Under ₹3 Lakh', null, 300000],
        ['₹3–5 Lakh', 300000, 500000],
        ['₹5–7 Lakh', 500000, 700000],
        ['₹7–9 Lakh', 700000, 900000],
        ['₹9–11 Lakh', 900000, 1100000],
        ['Above ₹11 Lakh', 1100000, null],
    ];

    public function index(): View
    {
        $banners = Banner::where('is_active', true)->orderBy('sort_order')->get();

        $popularTractors = Tractor::where('status', 'active')
            ->where('is_featured', true)
            ->with(['brand', 'images', 'prices'])
            ->limit(8)
            ->get();

        $latestLaunches = Tractor::where('status', 'active')
            ->with(['brand', 'images'])
            ->latest('launch_year')
            ->limit(6)
            ->get();

        $brands = Brand::where('status', 'active')->orderBy('name')->get();

        $latestPosts = Blog::where('status', 'published')
            ->latest('published_at')
            ->limit(3)
            ->get();

        $miniTractorsCategory = Category::where('type', 'tractor_category')->where('slug', 'mini-tractors')->first();
        $miniTractors = $miniTractorsCategory
            ? Tractor::where('status', 'active')->where('category_id', $miniTractorsCategory->id)->with(['brand', 'images', 'prices'])->limit(8)->get()
            : collect();

        $popularImplements = Implement::where('status', 'active')
            ->with(['brand', 'images'])
            ->latest()
            ->limit(8)
            ->get();

        $hpRanges = self::HP_RANGES;
        $budgetRanges = self::BUDGET_RANGES;

        return view('home', compact(
            'banners', 'popularTractors', 'latestLaunches', 'brands', 'latestPosts',
            'miniTractorsCategory', 'miniTractors', 'popularImplements', 'hpRanges', 'budgetRanges'
        ));
    }
}
