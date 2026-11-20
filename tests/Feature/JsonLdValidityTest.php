<?php

namespace Tests\Feature;

use App\Models\Blog;
use App\Models\Dealer;
use App\Models\Implement;
use App\Models\News;
use App\Models\Tractor;
use Tests\TestCase;

class JsonLdValidityTest extends TestCase
{
    private function assertAllJsonLdValid(string $url): void
    {
        $html = $this->get($url)->getContent();

        preg_match_all('#<script type="application/ld\+json">(.*?)</script>#s', $html, $matches);

        $this->assertNotEmpty($matches[1], "No JSON-LD blocks found on $url");

        foreach ($matches[1] as $i => $json) {
            json_decode($json, true);
            $this->assertEquals(JSON_ERROR_NONE, json_last_error(), "Invalid JSON-LD block #$i on $url: ".json_last_error_msg());
        }
    }

    public function test_tractor_page_json_ld_is_valid(): void
    {
        $tractor = Tractor::with('brand')->first();
        $this->assertAllJsonLdValid(route('tractors.show', [$tractor->brand, $tractor]));
    }

    public function test_dealer_page_json_ld_is_valid(): void
    {
        $dealer = Dealer::first();
        $this->assertAllJsonLdValid(route('dealers.show', $dealer));
    }

    public function test_faqs_page_json_ld_is_valid(): void
    {
        $this->assertAllJsonLdValid('/faqs');
    }

    public function test_implement_page_json_ld_is_valid(): void
    {
        $implement = Implement::first();
        $this->assertAllJsonLdValid(route('implements.show', $implement));
    }

    public function test_blog_page_json_ld_is_valid(): void
    {
        $blog = Blog::where('status', 'published')->first();
        $this->assertAllJsonLdValid(route('blog.show', $blog));
    }

    public function test_news_page_json_ld_is_valid(): void
    {
        $news = News::where('status', 'published')->first();
        $this->assertAllJsonLdValid(route('news.show', $news));
    }

    public function test_brand_page_json_ld_is_valid(): void
    {
        $tractor = Tractor::with('brand')->first();
        $this->assertAllJsonLdValid(route('brands.show', $tractor->brand));
    }
}
