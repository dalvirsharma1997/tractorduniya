<?php

namespace App\Providers;

use App\Models\Blog;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Dealer;
use App\Models\Implement;
use App\Models\News;
use App\Models\Page;
use App\Models\Tractor;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Keeps seo_meta.routable_type as the short strings already used
        // throughout the app (tractor, implement, …) instead of FQCNs.
        Relation::morphMap([
            'tractor' => Tractor::class,
            'implement' => Implement::class,
            'dealer' => Dealer::class,
            'blog' => Blog::class,
            'news' => News::class,
            'brand' => Brand::class,
            'category' => Category::class,
            'page' => Page::class,
        ]);
    }
}
