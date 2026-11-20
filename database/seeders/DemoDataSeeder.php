<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

/**
 * Removable demo content: tractors, implements, dealers, lead-assignment rules,
 * and sample blog/FAQ content. Run `php artisan demo:remove` to wipe all of it
 * back out without touching roles, users, states, brands, or categories.
 */
class DemoDataSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            TractorSeeder::class,
            ImplementSeeder::class,
            DealerSeeder::class,
            LeadAssignmentRuleSeeder::class,
            ContentSeeder::class,
            BannerSeeder::class,
        ]);
    }
}
