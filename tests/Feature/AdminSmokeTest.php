<?php

namespace Tests\Feature;

use App\Models\User;
use Tests\TestCase;

class AdminSmokeTest extends TestCase
{
    public function test_admin_pages_load(): void
    {
        $user = User::where('email', 'admin@tractorduniya.in')->first();
        $this->actingAs($user);

        $tractor = \App\Models\Tractor::first();
        $implement = \App\Models\Implement::first();
        $dealer = \App\Models\Dealer::first();
        $lead = \App\Models\Lead::firstOrCreate(
            ['phone' => '9000000000'],
            ['name' => 'Test Lead', 'status' => 'new']
        );
        $usedListing = \App\Models\UsedListing::firstOrCreate(
            ['seller_phone' => '9111111111'],
            [
                'listing_type' => 'tractor', 'tractor_id' => $tractor->id,
                'seller_name' => 'Test Seller', 'city' => 'Pune', 'condition' => 'good',
                'asking_price' => 300000, 'status' => 'pending',
            ]
        );
        $rule = \App\Models\LeadAssignmentRule::first();
        $blog = \App\Models\Blog::first();
        $news = \App\Models\News::firstOrCreate(
            ['slug' => 'test-news'],
            ['title' => 'Test News', 'body' => '<p>body</p>', 'status' => 'draft']
        );
        $video = \App\Models\Video::firstOrCreate(
            ['slug' => 'test-video'],
            ['title' => 'Test Video', 'embed_url' => 'https://youtube.com/embed/x']
        );
        $faq = \App\Models\Faq::first();
        $review = \App\Models\Review::firstOrCreate(
            ['reviewable_type' => 'tractor', 'reviewable_id' => $tractor->id, 'reviewer_name' => 'Test'],
            ['rating' => 5, 'body' => 'great', 'status' => 'pending']
        );
        $role = \App\Models\Role::first();
        $redirect = \App\Models\Redirect::firstOrCreate(['from_path' => '/old'], ['to_path' => '/new', 'type' => '301']);
        $seoMeta = \App\Models\SeoMeta::firstOrCreate(['path' => '/about'], ['meta_title' => 'About']);

        foreach ([
            '/admin',
            '/admin/brands', '/admin/brands/create',
            '/admin/categories', '/admin/categories/create',
            '/admin/states', '/admin/states/create',
            '/admin/tractors', '/admin/tractors/create', "/admin/tractors/{$tractor->id}/edit",
            '/admin/implements', '/admin/implements/create', "/admin/implements/{$implement->id}/edit",
            '/admin/dealers', '/admin/dealers/create', "/admin/dealers/{$dealer->id}/edit",
            '/admin/used-listings', '/admin/used-listings/create', "/admin/used-listings/{$usedListing->id}/edit",
            '/admin/leads', '/admin/leads/create', "/admin/leads/{$lead->id}/edit",
            '/admin/lead-assignment-rules', '/admin/lead-assignment-rules/create', "/admin/lead-assignment-rules/{$rule->id}/edit",
            '/admin/blogs', '/admin/blogs/create', "/admin/blogs/{$blog->id}/edit",
            '/admin/news', '/admin/news/create', "/admin/news/{$news->id}/edit",
            '/admin/videos', '/admin/videos/create', "/admin/videos/{$video->id}/edit",
            '/admin/faqs', '/admin/faqs/create', "/admin/faqs/{$faq->id}/edit",
            '/admin/reviews', '/admin/reviews/create', "/admin/reviews/{$review->id}/edit",
            '/admin/users', '/admin/users/create', "/admin/users/{$user->id}/edit",
            '/admin/roles', '/admin/roles/create', "/admin/roles/{$role->id}/edit",
            '/admin/redirects', '/admin/redirects/create', "/admin/redirects/{$redirect->id}/edit",
            '/admin/seo-metas', '/admin/seo-metas/create', "/admin/seo-metas/{$seoMeta->id}/edit",
            '/admin/manage-settings',
        ] as $url) {
            $response = $this->get($url);
            $status = $response->getStatusCode();
            echo "$url -> $status\n";
            if ($status === 500) {
                echo $response->getContent();
            }
            $this->assertNotEquals(500, $status, "Failed: $url");
        }
    }
}
