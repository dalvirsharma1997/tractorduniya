<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Tractor;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

/**
 * DEMO DATA. Model names are real brand/series examples for layout testing;
 * exact specs/prices are placeholders and MUST be verified and replaced with
 * current official figures before go-live. Removed entirely by `php artisan demo:remove`.
 */
class TractorSeeder extends Seeder
{
    public function run(): void
    {
        $utility = Category::where('slug', 'utility-tractors')->first();
        $compact = Category::where('slug', 'compact-tractors')->first();
        $fourWd = Category::where('slug', '4wd-tractors')->first();

        $rows = [
            ['brand' => 'Mahindra', 'name' => 'Mahindra 575 DI XP Plus', 'hp' => 45, 'category' => $utility, 'price' => [650000, 720000]],
            ['brand' => 'Mahindra', 'name' => 'Mahindra Jivo 245 DI', 'hp' => 24, 'category' => $compact, 'price' => [430000, 480000]],
            ['brand' => 'Swaraj', 'name' => 'Swaraj 744 FE', 'hp' => 48, 'category' => $utility, 'price' => [670000, 740000]],
            ['brand' => 'Sonalika', 'name' => 'Sonalika Tiger DI 75', 'hp' => 75, 'category' => $fourWd, 'price' => [980000, 1080000]],
            ['brand' => 'Eicher', 'name' => 'Eicher 380', 'hp' => 38, 'category' => $utility, 'price' => [560000, 610000]],
            ['brand' => 'John Deere', 'name' => 'John Deere 5050 D', 'hp' => 50, 'category' => $utility, 'price' => [750000, 820000]],
            ['brand' => 'Farmtrac', 'name' => 'Farmtrac 60 Powermaxx', 'hp' => 60, 'category' => $fourWd, 'price' => [830000, 910000]],
            ['brand' => 'Massey Ferguson', 'name' => 'Massey Ferguson 241 DI', 'hp' => 42, 'category' => $utility, 'price' => [610000, 670000]],
        ];

        foreach ($rows as $row) {
            $brand = Brand::where('slug', Str::slug($row['brand']))->first();

            $tractor = Tractor::updateOrCreate(
                ['slug' => Str::slug($row['name'])],
                [
                    'brand_id' => $brand->id,
                    'category_id' => $row['category']?->id,
                    'name' => $row['name'],
                    'hp' => $row['hp'],
                    'engine_cc' => $row['hp'] * 55,
                    'cylinders' => $row['hp'] > 50 ? 4 : 3,
                    'fuel_tank_litres' => 55,
                    'transmission_type' => 'Constant Mesh',
                    'gearbox' => '8F + 2R',
                    'pto_hp' => (int) round($row['hp'] * 0.85),
                    'lifting_capacity_kg' => $row['hp'] * 25,
                    'drive_type' => $row['category'] === $fourWd ? '4WD' : '2WD',
                    'warranty_text' => '2 years or 2000 hours, whichever is earlier',
                    'launch_year' => 2023,
                    'status' => 'active',
                    'is_featured' => true,
                ]
            );

            $tractor->prices()->updateOrCreate(
                ['state_id' => null, 'effective_date' => now()->toDateString()],
                ['price_min' => $row['price'][0], 'price_max' => $row['price'][1]]
            );

            foreach (['Power Steering', 'Oil Immersed Brakes', 'Dual Clutch'] as $i => $feature) {
                $tractor->specs()->updateOrCreate(
                    ['spec_group' => 'features', 'spec_key' => $feature],
                    ['spec_value' => 'Yes', 'sort_order' => $i]
                );
            }
        }
    }
}
