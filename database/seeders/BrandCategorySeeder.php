<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class BrandCategorySeeder extends Seeder
{
    public function run(): void
    {
        $brands = [
            ['name' => 'Mahindra', 'country' => 'India'],
            ['name' => 'Swaraj', 'country' => 'India'],
            ['name' => 'John Deere', 'country' => 'USA'],
            ['name' => 'Sonalika', 'country' => 'India'],
            ['name' => 'Eicher', 'country' => 'India'],
            ['name' => 'New Holland', 'country' => 'Italy'],
            ['name' => 'Massey Ferguson', 'country' => 'France'],
            ['name' => 'Kubota', 'country' => 'Japan'],
            ['name' => 'Farmtrac', 'country' => 'India'],
            ['name' => 'Powertrac', 'country' => 'India'],
        ];

        foreach ($brands as $brand) {
            Brand::updateOrCreate(
                ['slug' => Str::slug($brand['name'])],
                ['name' => $brand['name'], 'country' => $brand['country'], 'status' => 'active']
            );
        }

        $tractorCategories = ['Mini Tractors', 'Compact Tractors', 'Utility Tractors', 'Orchard Tractors', '4WD Tractors'];
        foreach ($tractorCategories as $name) {
            Category::updateOrCreate(
                ['slug' => Str::slug($name)],
                ['name' => $name, 'type' => 'tractor_category']
            );
        }

        $implementTypes = ['Rotavator', 'Plough', 'Cultivator', 'Seed Drill', 'Trailer', 'Mower', 'Harrow', 'Thresher'];
        foreach ($implementTypes as $name) {
            Category::updateOrCreate(
                ['slug' => Str::slug($name)],
                ['name' => $name, 'type' => 'implement_type']
            );
        }
    }
}
