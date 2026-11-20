<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Implement;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

/** DEMO DATA — placeholder specs/prices, replace before go-live. */
class ImplementSeeder extends Seeder
{
    public function run(): void
    {
        $rows = [
            ['name' => 'Fieldking Rotavator FKR-180', 'type' => 'Rotavator', 'hp' => [35, 60], 'price' => [65000, 95000]],
            ['name' => 'Shaktiman Cultivator 9 Tyne', 'type' => 'Cultivator', 'hp' => [40, 70], 'price' => [38000, 52000]],
            ['name' => 'Khedut Seed Drill 9 Row', 'type' => 'Seed Drill', 'hp' => [35, 55], 'price' => [45000, 60000]],
            ['name' => 'Maschio Gaspardo Mower MD', 'type' => 'Mower', 'hp' => [30, 50], 'price' => [72000, 98000]],
            ['name' => 'Lemken Reversible Plough 3F', 'type' => 'Plough', 'hp' => [50, 80], 'price' => [110000, 150000]],
            ['name' => 'Fieldking Trailer 3 Tonne', 'type' => 'Trailer', 'hp' => [35, 60], 'price' => [85000, 120000]],
        ];

        foreach ($rows as $row) {
            $category = Category::where('type', 'implement_type')->where('name', $row['type'])->first();

            Implement::updateOrCreate(
                ['slug' => Str::slug($row['name'])],
                [
                    'category_id' => $category?->id,
                    'name' => $row['name'],
                    'type' => $row['type'],
                    'compatible_hp_min' => $row['hp'][0],
                    'compatible_hp_max' => $row['hp'][1],
                    'price_min' => $row['price'][0],
                    'price_max' => $row['price'][1],
                    'description' => "{$row['name']} — compatible with tractors of {$row['hp'][0]}–{$row['hp'][1]} HP.",
                    'status' => 'active',
                ]
            );
        }
    }
}
