<?php

namespace Database\Seeders;

use App\Models\Page;
use Illuminate\Database\Seeder;

class PageSeeder extends Seeder
{
    public function run(): void
    {
        Page::firstOrCreate(['slug' => 'about'], [
            'title' => 'About TractorDuniya.in',
            'body' => '<p>TractorDuniya.in is an independent information and marketplace platform for the Indian tractor industry. We help farmers and buyers research tractor models, compare specifications and prices, locate authorised dealers, and buy or sell used tractors and implements.</p><p>Our listings cover every major brand sold in India, with state-wise pricing to account for RTO tax and registration charges that vary across the country.</p>',
        ]);

        Page::firstOrCreate(['slug' => 'privacy-policy'], [
            'title' => 'Privacy Policy',
            'body' => '<p>We collect only the information you choose to share with us through enquiry and listing forms — your name, phone number, email, and location — in order to connect you with relevant dealers and respond to your requests.</p><p>We do not sell your personal information to third parties. Your enquiry details are shared only with the dealer(s) relevant to your request.</p><p>This policy may be updated from time to time; the latest version will always be available at this page.</p>',
        ]);
    }
}
