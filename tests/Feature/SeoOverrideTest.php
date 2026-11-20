<?php

namespace Tests\Feature;

use App\Models\Redirect;
use App\Models\SeoMeta;
use App\Models\Tractor;
use Tests\TestCase;

class SeoOverrideTest extends TestCase
{
    public function test_record_level_seo_override_wins_over_default_title(): void
    {
        $tractor = Tractor::with('brand')->first();

        SeoMeta::updateOrCreate(
            ['routable_type' => 'tractor', 'routable_id' => $tractor->id],
            [
                'meta_title' => 'Custom Override Title For SEO Test',
                'meta_description' => 'Custom override description.',
                'canonical_url' => 'https://tractorduniya.in/custom-canonical',
            ]
        );

        $response = $this->get(route('tractors.show', [$tractor->brand, $tractor]));

        $response->assertSee('Custom Override Title For SEO Test', false);
        $response->assertSee('Custom override description.', false);
        $response->assertSee('https://tractorduniya.in/custom-canonical', false);
    }

    public function test_path_level_seo_override_applies_to_static_page(): void
    {
        SeoMeta::updateOrCreate(
            ['path' => '/about'],
            ['meta_title' => 'Custom About Title', 'meta_description' => 'Custom about description.']
        );

        $response = $this->get('/about');

        $response->assertSee('Custom About Title', false);
        $response->assertSee('Custom about description.', false);
    }

    public function test_configured_redirect_is_followed(): void
    {
        Redirect::updateOrCreate(
            ['from_path' => '/old-tractor-page'],
            ['to_path' => '/tractors', 'type' => '301']
        );

        $response = $this->get('/old-tractor-page');

        $response->assertRedirect('/tractors');
        $response->assertStatus(301);
    }

    public function test_unmatched_path_without_redirect_404s(): void
    {
        $response = $this->get('/this-page-does-not-exist-anywhere');

        $response->assertStatus(404);
    }
}
