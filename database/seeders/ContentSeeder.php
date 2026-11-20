<?php

namespace Database\Seeders;

use App\Models\Blog;
use App\Models\Faq;
use App\Models\News;
use App\Models\Tractor;
use App\Models\Translation;
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

        $howToChooseBlog = Blog::where('slug', Str::slug('How to Choose the Right HP Tractor for Your Farm'))->first();
        if ($howToChooseBlog) {
            Translation::updateOrCreate(
                ['entity_type' => 'blog', 'entity_id' => $howToChooseBlog->id, 'language_code' => 'hi', 'field_name' => 'title'],
                ['field_value' => 'अपने खेत के लिए सही एचपी ट्रैक्टर कैसे चुनें']
            );
            Translation::updateOrCreate(
                ['entity_type' => 'blog', 'entity_id' => $howToChooseBlog->id, 'language_code' => 'hi', 'field_name' => 'excerpt'],
                ['field_value' => 'अपनी ज़मीन के आकार और उपकरणों के अनुसार ट्रैक्टर की हॉर्स पावर चुनने की एक त्वरित गाइड।']
            );
            Translation::updateOrCreate(
                ['entity_type' => 'blog', 'entity_id' => $howToChooseBlog->id, 'language_code' => 'hi', 'field_name' => 'body'],
                ['field_value' => '<p>सही हॉर्स पावर वाला ट्रैक्टर चुनना आपकी ज़मीन के आकार, मिट्टी के प्रकार और आप जिन उपकरणों का उपयोग करने की योजना बना रहे हैं, उस पर निर्भर करता है। यह गाइड बुनियादी बातों को समझाती है ताकि आप न तो ज़्यादा खर्च करें और न ही अपने खेत के लिए कम क्षमता वाला ट्रैक्टर चुनें।</p>']
            );
        }

        News::updateOrCreate(
            ['slug' => 'new-emission-norms-for-tractors-announced'],
            [
                'author_id' => $admin?->id,
                'title' => 'New Emission Norms for Tractors Announced',
                'category' => 'Industry News',
                'excerpt' => 'Regulators have announced updated emission norms affecting new tractor models from next year.',
                'body' => '<p>The announcement affects new tractor models sold from next year onward; existing owners are not required to retrofit. Manufacturers are expected to update engine lineups ahead of the deadline.</p>',
                'status' => 'published',
                'published_at' => now(),
                'meta_title' => 'New Emission Norms for Tractors Announced',
                'meta_description' => 'Updated emission norms for new tractor models — what buyers need to know.',
            ]
        );

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

        $pageFaqs = [
            ['q' => 'Is TractorDuniya.in free to use?', 'a' => 'Yes, browsing listings, comparing tractors, and contacting dealers is completely free for buyers and sellers.'],
            ['q' => 'How do I sell my used tractor?', 'a' => 'Use the "Sell your tractor" form to submit your listing with photos. Our team reviews it before it goes live.'],
            ['q' => 'Are the dealers on this site verified?', 'a' => 'Dealers marked "Verified" have been confirmed by our team. We recommend always verifying details before making a purchase.'],
        ];

        foreach ($pageFaqs as $i => $faq) {
            Faq::updateOrCreate(
                ['faqable_type' => 'page', 'faqable_id' => null, 'question' => $faq['q']],
                ['answer' => $faq['a'], 'sort_order' => $i]
            );
        }
    }
}
