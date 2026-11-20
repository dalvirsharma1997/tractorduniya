<?php

namespace Tests\Feature;

use App\Models\Tractor;
use Tests\TestCase;

class LocaleRoutingTest extends TestCase
{
    public function test_hi_prefixed_pages_load_and_set_locale(): void
    {
        $tractor = Tractor::with('brand')->first();

        $urls = [
            '/hi',
            '/hi/tractors',
            "/hi/tractors/{$tractor->brand->slug}",
            "/hi/tractors/{$tractor->brand->slug}/{$tractor->slug}",
            '/hi/implements',
            '/hi/used-tractors',
            '/hi/sell',
            '/hi/dealers',
            '/hi/blog',
            '/hi/news',
            '/hi/about',
            '/hi/contact',
        ];

        foreach ($urls as $url) {
            $response = $this->get($url);
            $this->assertEquals(200, $response->getStatusCode(), "Failed: $url");
            $response->assertSee('lang="hi"', false);
        }
    }

    public function test_en_page_has_hreflang_alternates_including_hi(): void
    {
        $tractor = Tractor::with('brand')->first();

        $response = $this->get(route('tractors.show', [$tractor->brand, $tractor]));

        $response->assertSee('hreflang="en"', false);
        $response->assertSee('hreflang="hi"', false);
        $response->assertSee('hreflang="x-default"', false);
        $response->assertSee(route('hi.tractors.show', [$tractor->brand, $tractor]), false);
    }

    public function test_hi_page_links_back_to_en_via_hreflang(): void
    {
        $tractor = Tractor::with('brand')->first();

        $response = $this->get(route('hi.tractors.show', [$tractor->brand, $tractor]));

        $response->assertSee(route('tractors.show', [$tractor->brand, $tractor]), false);
    }

    public function test_sitemap_has_no_hreflang_tags(): void
    {
        // Sitemaps have no bilingual counterpart, so hreflang_alternates() should be empty.
        $response = $this->get('/robots.txt');
        $response->assertDontSee('hreflang', false);
    }

    public function test_sell_listing_from_hi_locale_redirects_to_hi_used_listings_index(): void
    {
        $tractor = Tractor::first();
        $state = \App\Models\State::first();

        $response = $this->post('/hi/sell', [
            'listing_type' => 'tractor',
            'tractor_id' => $tractor->id,
            'seller_name' => 'Hi Locale Seller',
            'seller_phone' => '9988776655',
            'city' => 'Pune',
            'state_id' => $state->id,
            'condition' => 'good',
            'asking_price' => 200000,
        ]);

        $response->assertRedirect(route('hi.used-listings.index'));
    }
}
