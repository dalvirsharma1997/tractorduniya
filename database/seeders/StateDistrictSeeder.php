<?php

namespace Database\Seeders;

use App\Models\District;
use App\Models\State;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class StateDistrictSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            'Uttar Pradesh' => ['Lucknow', 'Agra', 'Meerut', 'Kanpur', 'Varanasi'],
            'Punjab' => ['Ludhiana', 'Amritsar', 'Patiala', 'Bathinda'],
            'Haryana' => ['Karnal', 'Hisar', 'Rohtak', 'Panipat'],
            'Madhya Pradesh' => ['Indore', 'Bhopal', 'Gwalior', 'Jabalpur'],
            'Maharashtra' => ['Pune', 'Nashik', 'Nagpur', 'Aurangabad'],
            'Rajasthan' => ['Jaipur', 'Jodhpur', 'Kota', 'Udaipur'],
            'Gujarat' => ['Ahmedabad', 'Rajkot', 'Vadodara', 'Surat'],
            'Bihar' => ['Patna', 'Gaya', 'Muzaffarpur'],
            'Karnataka' => ['Belagavi', 'Bengaluru Rural', 'Hubballi'],
            'Tamil Nadu' => ['Coimbatore', 'Madurai', 'Salem'],
            'Andhra Pradesh' => ['Guntur', 'Krishna', 'Visakhapatnam'],
            'Telangana' => ['Nizamabad', 'Warangal', 'Karimnagar'],
        ];

        foreach ($data as $stateName => $districts) {
            $state = State::updateOrCreate(
                ['slug' => Str::slug($stateName)],
                ['name' => $stateName, 'rto_tax_notes' => 'RTO tax varies by state; verify with local RTO before quoting on-road price.']
            );

            foreach ($districts as $districtName) {
                District::updateOrCreate(
                    ['state_id' => $state->id, 'slug' => Str::slug($districtName)],
                    ['name' => $districtName]
                );
            }
        }
    }
}
