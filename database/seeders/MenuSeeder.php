<?php

namespace Database\Seeders;

use App\Models\MenuItem;
use Illuminate\Database\Seeder;

class MenuSeeder extends Seeder
{
    /**
     * Idempotent default header/footer menu, matching what was previously
     * hardcoded in the header/footer blade partials. Safe to re-run.
     */
    public function run(): void
    {
        if (MenuItem::where('location', 'header')->exists() || MenuItem::where('location', 'footer')->exists()) {
            return;
        }

        foreach ([
            ['label' => 'Tractors', 'url' => '/tractors'],
            ['label' => 'Implements', 'url' => '/implements'],
            ['label' => 'Used Tractors', 'url' => '/used-tractors'],
            ['label' => 'Dealers', 'url' => '/dealers'],
            ['label' => 'Blog', 'url' => '/blog'],
        ] as $i => $item) {
            MenuItem::create(['location' => 'header', 'label' => $item['label'], 'url' => $item['url'], 'sort_order' => $i]);
        }

        $browse = MenuItem::create(['location' => 'footer', 'label' => 'Browse', 'sort_order' => 0]);
        foreach ([
            ['label' => 'All Tractors', 'url' => '/tractors'],
            ['label' => 'Implements', 'url' => '/implements'],
            ['label' => 'Buy Used Tractors', 'url' => '/used-tractors'],
            ['label' => 'Sell Your Tractor', 'url' => '/sell'],
            ['label' => 'Dealer Directory', 'url' => '/dealers'],
        ] as $i => $item) {
            MenuItem::create(['location' => 'footer', 'parent_id' => $browse->id, 'label' => $item['label'], 'url' => $item['url'], 'sort_order' => $i]);
        }

        $resources = MenuItem::create(['location' => 'footer', 'label' => 'Resources', 'sort_order' => 1]);
        foreach ([
            ['label' => 'Blog', 'url' => '/blog'],
            ['label' => 'News', 'url' => '/news'],
            ['label' => 'EMI Calculator', 'url' => '/emi-calculator'],
            ['label' => 'FAQs', 'url' => '/faqs'],
        ] as $i => $item) {
            MenuItem::create(['location' => 'footer', 'parent_id' => $resources->id, 'label' => $item['label'], 'url' => $item['url'], 'sort_order' => $i]);
        }

        $company = MenuItem::create(['location' => 'footer', 'label' => 'Company', 'sort_order' => 2]);
        foreach ([
            ['label' => 'About Us', 'url' => '/about'],
            ['label' => 'Contact', 'url' => '/contact'],
            ['label' => 'Privacy Policy', 'url' => '/privacy-policy'],
        ] as $i => $item) {
            MenuItem::create(['location' => 'footer', 'parent_id' => $company->id, 'label' => $item['label'], 'url' => $item['url'], 'sort_order' => $i]);
        }
    }
}
