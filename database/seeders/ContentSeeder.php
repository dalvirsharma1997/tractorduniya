<?php

namespace Database\Seeders;

use App\Models\Blog;
use App\Models\Faq;
use App\Models\Tractor;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

/** DEMO DATA — sample blog posts and FAQs so content-driven pages aren't empty on first run. */
class ContentSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('email', 'admin@tractorduniya.in')->first();

        $posts = [
            [
                'title' => 'How to Choose the Right HP Tractor for Your Farm',
                'excerpt' => 'A quick guide to matching tractor horsepower with your land size and implements.',
                'body' => "<p>Choosing the right horsepower tractor depends on your land size, soil type, and the implements you plan to run. This guide walks through the basics so you don't overspend or underpower your farm.</p>",
            ],
            [
                'title' => 'Government Subsidy Schemes for Tractor Purchase in 2026',
                'excerpt' => 'An overview of central and state subsidy schemes farmers can apply for.',
                'body' => '<p>Several state governments offer subsidies on tractor and implement purchases under various farm mechanization schemes. Eligibility and subsidy percentage vary by state — check with your local agriculture department.</p>',
            ],
        ];

        foreach ($posts as $post) {
            Blog::updateOrCreate(
                ['slug' => Str::slug($post['title'])],
                [
                    'author_id' => $admin?->id,
                    'title' => $post['title'],
                    'category' => 'Buying Guide',
                    'excerpt' => $post['excerpt'],
                    'body' => $post['body'],
                    'status' => 'published',
                    'published_at' => now(),
                    'meta_title' => $post['title'],
                    'meta_description' => $post['excerpt'],
                ]
            );
        }

        $firstTractor = Tractor::first();
        if ($firstTractor) {
            $faqs = [
                ['q' => 'What is the on-road price of this tractor?', 'a' => 'On-road price varies by state due to RTO tax and registration charges. Use the on-road price calculator on this page for your state.'],
                ['q' => 'Does this tractor come with a warranty?', 'a' => 'Yes, this model includes the manufacturer warranty listed in the Overview tab above.'],
            ];

            foreach ($faqs as $i => $faq) {
                Faq::updateOrCreate(
                    ['faqable_type' => 'tractor', 'faqable_id' => $firstTractor->id, 'question' => $faq['q']],
                    ['answer' => $faq['a'], 'sort_order' => $i]
                );
            }
        }
    }
}
