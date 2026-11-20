<?php

namespace Tests\Feature;

use App\Models\Blog;
use App\Models\User;
use Tests\TestCase;

class ContentTranslationTest extends TestCase
{
    public function test_hi_blog_page_shows_translated_title_and_body(): void
    {
        $blog = Blog::where('slug', 'how-to-choose-the-right-hp-tractor-for-your-farm')->first();

        $response = $this->get(route('hi.blog.show', $blog));

        $response->assertSee('अपने खेत के लिए सही एचपी ट्रैक्टर कैसे चुनें', false);
        $response->assertDontSee('How to Choose the Right HP Tractor for Your Farm');
    }

    public function test_en_blog_page_still_shows_english_title(): void
    {
        $blog = Blog::where('slug', 'how-to-choose-the-right-hp-tractor-for-your-farm')->first();

        $response = $this->get(route('blog.show', $blog));

        $response->assertSee('How to Choose the Right HP Tractor for Your Farm');
    }

    public function test_hi_blog_index_shows_translated_title(): void
    {
        $response = $this->get(route('hi.blog.index'));

        $response->assertSee('अपने खेत के लिए सही एचपी ट्रैक्टर कैसे चुनें', false);
    }

    public function test_blog_without_hi_translation_falls_back_to_english(): void
    {
        $blog = Blog::where('slug', '!=', 'how-to-choose-the-right-hp-tractor-for-your-farm')->first();

        $response = $this->get(route('hi.blog.show', $blog));

        $response->assertSee($blog->title);
    }

    public function test_admin_translation_resource_accessible(): void
    {
        $admin = User::where('email', 'admin@tractorduniya.in')->first();
        $this->actingAs($admin);

        $blog = Blog::first();

        $this->assertEquals(200, $this->get('/admin/translations')->getStatusCode());
        $this->assertEquals(200, $this->get('/admin/translations/create')->getStatusCode());

        $translation = \App\Models\Translation::where('entity_type', 'blog')->first();
        $this->assertEquals(200, $this->get("/admin/translations/{$translation->id}/edit")->getStatusCode());
    }
}
