<?php

namespace Tests\Feature;

use App\Models\Tractor;
use Tests\TestCase;

class HindiTranslationTest extends TestCase
{
    public function test_homepage_renders_hindi_text_under_hi_prefix(): void
    {
        $response = $this->get('/hi');

        $response->assertSee('ट्रैक्टर', false);
        $response->assertSee('अपना ट्रैक्टर बेचें', false);
        $response->assertSee('अपने खेत के लिए सही ट्रैक्टर खोजें', false);
        $response->assertDontSee('Find the right tractor for your farm', false);
    }

    public function test_english_homepage_still_renders_english_text(): void
    {
        $response = $this->get('/');

        $response->assertSee('Find the right tractor for your farm', false);
        $response->assertDontSee('अपने खेत के लिए सही ट्रैक्टर खोजें', false);
    }

    public function test_tractor_detail_page_renders_hindi_tab_labels(): void
    {
        $tractor = Tractor::with('brand')->first();

        $response = $this->get(route('hi.tractors.show', [$tractor->brand, $tractor]));

        $response->assertSee('अवलोकन', false); // Overview
        $response->assertSee('पूरी स्पेसिफिकेशन', false); // Full Specs
        $response->assertSee('सबसे अच्छी कीमत पाएं', false); // Get Best Price
    }

    public function test_sell_page_header_nav_is_in_hindi(): void
    {
        $response = $this->get('/hi/sell');

        $response->assertSee('अपना ट्रैक्टर बेचें', false); // header nav/CTA translated even on pages without full translation yet
    }
}
