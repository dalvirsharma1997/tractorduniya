<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use App\Models\Blog;
use App\Models\Brand;
use App\Models\Tractor;
use Illuminate\View\View;

class HomeController extends Controller
{
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

        return view('home', compact('banners', 'popularTractors', 'latestLaunches', 'brands', 'latestPosts'));
    }
}
