<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\Dealer;
use App\Models\District;
use App\Models\LeadAssignmentRule;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

/** DEMO DATA — wires the seeded demo dealers up so the lead-assignment logic is testable out of the box. */
class LeadAssignmentRuleSeeder extends Seeder
{
    public function run(): void
    {
        $rules = [
            ['dealer' => 'Annapurna Tractors', 'district' => 'Ludhiana', 'brand' => 'Mahindra'],
            ['dealer' => 'Kisan Motors', 'district' => 'Karnal', 'brand' => 'John Deere'],
            ['dealer' => 'Bharat Agro Sales', 'district' => 'Indore', 'brand' => 'Sonalika'],
            ['dealer' => 'Shree Krishi Seva Kendra', 'district' => 'Pune', 'brand' => 'Mahindra'],
            ['dealer' => 'Jai Kisan Tractors', 'district' => 'Jaipur', 'brand' => 'Eicher'],
        ];

        foreach ($rules as $rule) {
            $dealer = Dealer::where('slug', Str::slug($rule['dealer']))->first();
            $district = District::where('slug', Str::slug($rule['district']))->first();
            $brand = Brand::where('slug', Str::slug($rule['brand']))->first();

            LeadAssignmentRule::updateOrCreate(
                ['district_id' => $district->id, 'brand_id' => $brand->id],
                ['dealer_id' => $dealer->id, 'priority' => 10]
            );

            // State-level fallback using the same dealer, lower priority.
            LeadAssignmentRule::updateOrCreate(
                ['district_id' => null, 'state_id' => $district->state_id, 'brand_id' => $brand->id],
                ['dealer_id' => $dealer->id, 'priority' => 5]
            );
        }
    }
}
