<?php

namespace App\Services;

use App\Models\Brand;
use App\Models\Tractor;
use Illuminate\Support\Str;

/**
 * Expected CSV header (one row per tractor):
 * brand,name,hp,drive_type,engine_cc,cylinders,fuel_tank_litres,transmission_type,
 * gearbox,pto_hp,lifting_capacity_kg,launch_year,status,price_min,price_max
 */
class TractorCsvImporter
{
    public function importFromPath(string $path): int
    {
        $handle = fopen($path, 'r');
        $header = fgetcsv($handle);
        $imported = 0;

        while (($row = fgetcsv($handle)) !== false) {
            $data = array_combine($header, $row);

            $brand = Brand::firstOrCreate(
                ['slug' => Str::slug($data['brand'])],
                ['name' => $data['brand'], 'status' => 'active']
            );

            $tractor = Tractor::updateOrCreate(
                ['slug' => Str::slug($data['name'])],
                [
                    'brand_id' => $brand->id,
                    'name' => $data['name'],
                    'hp' => $data['hp'] ?? 0,
                    'drive_type' => $data['drive_type'] ?? '2WD',
                    'engine_cc' => $data['engine_cc'] ?: null,
                    'cylinders' => $data['cylinders'] ?: null,
                    'fuel_tank_litres' => $data['fuel_tank_litres'] ?: null,
                    'transmission_type' => $data['transmission_type'] ?? null,
                    'gearbox' => $data['gearbox'] ?? null,
                    'pto_hp' => $data['pto_hp'] ?: null,
                    'lifting_capacity_kg' => $data['lifting_capacity_kg'] ?: null,
                    'launch_year' => $data['launch_year'] ?: null,
                    'status' => $data['status'] ?? 'active',
                ]
            );

            if (! empty($data['price_min']) && ! empty($data['price_max'])) {
                $tractor->prices()->updateOrCreate(
                    ['state_id' => null, 'effective_date' => now()->toDateString()],
                    ['price_min' => $data['price_min'], 'price_max' => $data['price_max']]
                );
            }

            $imported++;
        }

        fclose($handle);

        return $imported;
    }
}
