<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Base reference data (roles, admin user, states/districts, brands/categories)
     * plus removable demo content. Run `php artisan demo:remove` after go-live to
     * strip the demo tractors/implements/dealers/content without touching the base data.
     */
    public function run(): void
    {
        $this->call([
            RolePermissionSeeder::class,
            StateDistrictSeeder::class,
            BrandCategorySeeder::class,
            DemoDataSeeder::class,
        ]);
    }
}
