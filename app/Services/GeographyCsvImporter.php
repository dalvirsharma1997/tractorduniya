<?php

namespace App\Services;

use App\Models\District;
use App\Models\State;
use App\Models\Tehsil;
use Illuminate\Support\Str;

/**
 * Expected CSV header (one row per tehsil; district/state repeat across rows):
 * state,district,tehsil
 *
 * A row with no tehsil value still creates/updates the state and district.
 */
class GeographyCsvImporter
{
    public function importFromPath(string $path): array
    {
        $handle = fopen($path, 'r');
        $header = array_map('trim', fgetcsv($handle));
        $header = array_map('strtolower', $header);

        $counts = ['states' => 0, 'districts' => 0, 'tehsils' => 0];

        while (($row = fgetcsv($handle)) !== false) {
            $data = array_combine($header, $row);

            $stateName = trim($data['state'] ?? '');
            $districtName = trim($data['district'] ?? '');
            $tehsilName = trim($data['tehsil'] ?? '');

            if ($stateName === '') {
                continue;
            }

            $state = State::firstOrCreate(
                ['slug' => Str::slug($stateName)],
                ['name' => $stateName]
            );
            if ($state->wasRecentlyCreated) {
                $counts['states']++;
            }

            if ($districtName === '') {
                continue;
            }

            $district = District::firstOrCreate(
                ['state_id' => $state->id, 'slug' => Str::slug($districtName)],
                ['name' => $districtName]
            );
            if ($district->wasRecentlyCreated) {
                $counts['districts']++;
            }

            if ($tehsilName === '') {
                continue;
            }

            $tehsil = Tehsil::firstOrCreate(
                ['district_id' => $district->id, 'name' => $tehsilName]
            );
            if ($tehsil->wasRecentlyCreated) {
                $counts['tehsils']++;
            }
        }

        fclose($handle);

        return $counts;
    }
}
