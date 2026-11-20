<?php

namespace Tests\Feature;

use App\Models\Blog;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Dealer;
use App\Models\Implement;
use App\Models\State;
use App\Models\Tractor;
use Tests\TestCase;

class PublicPagesSmokeTest extends TestCase
{
    public function test_public_pages_load(): void
    {
        $tractor = Tractor::with('brand')->first();
        $tractor2 = Tractor::where('id', '!=', $tractor->id)->first();
        $brand = $tractor->brand;
        $implement = Implement::first();
        $dealer = Dealer::first();
        $blog = Blog::where('status', 'published')->first();
        $state = State::first();
        $category = Category::where('type', 'tractor_category')->whereHas('tractors')->first()
            ?? Category::where('type', 'tractor_category')->first();
        $implementCategory = Category::where('type', 'implement_type')->first();

        $urls = [
            '/',
            '/tractors',
            '/tractors?brand=' . $brand->slug . '&hp_min=20&hp_max=80',
            "/tractors/{$brand->slug}",
            "/tractors/{$brand->slug}/{$tractor->slug}",
            "/compare/{$tractor->slug}-vs-{$tractor2->slug}",
            "/category/{$category->slug}",
            "/category/{$implementCategory->slug}",
            '/implements',
            "/implements/{$implement->slug}",
            '/used-tractors',
            '/sell',
            '/dealers',
            "/dealers/{$dealer->slug}",
            '/emi-calculator',
            '/faqs',
            '/about',
            '/contact',
            '/privacy-policy',
            '/blog',
            "/blog/{$blog->slug}",
            '/news',
            '/news/new-emission-norms-for-tractors-announced',
            "/tractors-in-{$state->slug}",
            '/robots.txt',
            '/sitemap.xml',
            '/sitemap-tractors.xml',
            '/sitemap-implements.xml',
            '/sitemap-dealers.xml',
            '/sitemap-blogs.xml',
            '/sitemap-news.xml',
        ];

        foreach ($urls as $url) {
            $response = $this->get($url);
            $status = $response->getStatusCode();
            echo "$url -> $status\n";
            if ($status >= 500) {
                echo $response->getContent();
            }
            $this->assertLessThan(500, $status, "Failed: $url");
        }
    }

    public function test_lead_submission_flow(): void
    {
        $tractor = Tractor::first();

        $response = $this->post('/leads', [
            'name' => 'Ramesh Kumar',
            'phone' => '9812345670',
            'interested_tractor_id' => $tractor->id,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('leads', ['phone' => '9812345670', 'interested_tractor_id' => $tractor->id]);
    }

    public function test_sell_listing_flow(): void
    {
        $state = State::first();
        $tractor = Tractor::first();

        $response = $this->post('/sell', [
            'listing_type' => 'tractor',
            'tractor_id' => $tractor->id,
            'seller_name' => 'Suresh Patel',
            'seller_phone' => '9123456780',
            'city' => 'Indore',
            'state_id' => $state->id,
            'condition' => 'good',
            'asking_price' => 250000,
        ]);

        $response->assertRedirect(route('used-listings.index'));
        $this->assertDatabaseHas('used_listings', ['seller_phone' => '9123456780', 'status' => 'pending']);
    }
}
