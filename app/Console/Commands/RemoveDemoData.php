<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class RemoveDemoData extends Command
{
    protected $signature = 'demo:remove {--force : Skip the confirmation prompt}';

    protected $description = 'Remove all demo tractors, implements, dealers, lead-assignment rules, and sample content seeded by DemoDataSeeder. Leaves roles, users, states, brands, and categories untouched.';

    /** Order matters: children before parents, to respect foreign keys. */
    protected array $tables = [
        'tractor_specs', 'tractor_prices', 'tractor_images', 'tractors',
        'implement_specs', 'implement_images', 'implements',
        'dealer_brands', 'lead_assignment_rules', 'dealers',
        'faqs', 'blogs', 'news', 'videos', 'reviews', 'banners',
        'used_listing_images', 'used_listings',
        'leads', 'lead_activity_log',
    ];

    public function handle(): int
    {
        if (! $this->option('force') && ! $this->confirm('This will permanently delete all demo tractors, implements, dealers, leads, and sample content. Continue?')) {
            $this->info('Cancelled.');

            return self::SUCCESS;
        }

        $driver = DB::connection()->getDriverName();
        $driver === 'sqlite'
            ? DB::statement('PRAGMA foreign_keys = OFF')
            : DB::statement('SET FOREIGN_KEY_CHECKS=0');

        foreach ($this->tables as $table) {
            DB::table($table)->delete();
        }

        $driver === 'sqlite'
            ? DB::statement('PRAGMA foreign_keys = ON')
            : DB::statement('SET FOREIGN_KEY_CHECKS=1');

        $this->info('Demo data removed. Roles, users, states, brands, and categories were left intact.');

        return self::SUCCESS;
    }
}
