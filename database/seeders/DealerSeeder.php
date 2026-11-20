<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\Dealer;
use App\Models\District;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

/** DEMO DATA — fictional dealers for layout/workflow testing. Replace before go-live. */
class DealerSeeder extends Seeder
{
    public function run(): void
    {
        $rows = [
            ['name' => 'Annapurna Tractors', 'district' => 'Ludhiana', 'brands' => ['Swaraj', 'Mahindra']],
            ['name' => 'Kisan Motors', 'district' => 'Karnal', 'brands' => ['John Deere']],
            ['name' => 'Bharat Agro Sales', 'district' => 'Indore', 'brands' => ['Sonalika', 'Farmtrac']],
            ['name' => 'Shree Krishi Seva Kendra', 'district' => 'Pune', 'brands' => ['Mahindra', 'Massey Ferguson']],
            ['name' => 'Jai Kisan Tractors', 'district' => 'Jaipur', 'brands' => ['Eicher']],
        ];

        foreach ($rows as $i => $row) {
            $district = District::where('slug', Str::slug($row['district']))->first();

            $dealer = Dealer::updateOrCreate(
                ['slug' => Str::slug($row['name'])],
                [
                    'name' => $row['name'],
                    'state_id' => $district->state_id,
                    'district_id' => $district->id,
                    'city' => $row['district'],
                    'address' => "Main GT Road, {$row['district']}",
                    'phone' => '98'.str_pad((string) (10000000 + $i), 8, '0', STR_PAD_LEFT),
                    'is_verified' => true,
                    'status' => 'active',
                ]
            );

            $brandIds = Brand::whereIn('slug', array_map(fn ($b) => Str::slug($b), $row['brands']))->pluck('id');
            $dealer->brands()->syncWithoutDetaching($brandIds);
        }
    }
}
