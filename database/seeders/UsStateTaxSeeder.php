<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UsStateTaxSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Get USA country ID
        $usaCountryId = DB::table('countries')
            ->where('iso_3166_2', 'US')
            ->orWhere('iso_3166_3', 'USA')
            ->orWhere('name', 'United States')
            ->value('id');

        if (!$usaCountryId) {
            // Create USA country if it doesn't exist
            $usaCountryId = DB::table('countries')->insertGetId([
                'name' => 'United States',
                'currency' => 'USD',
                'currency_symbol' => '$',
                'iso_3166_2' => 'US',
                'iso_3166_3' => 'USA',
                'calling_code' => '1',
                'flag' => '🇺🇸',
            ]);
        }

        $states = [
            ['name' => 'Alabama', 'tax_rate' => 9.29],
            ['name' => 'Alaska', 'tax_rate' => 1.82],
            ['name' => 'Arizona', 'tax_rate' => 8.38],
            ['name' => 'Arkansas', 'tax_rate' => 9.45],
            ['name' => 'California', 'tax_rate' => 8.85],
            ['name' => 'Colorado', 'tax_rate' => 7.81],
            ['name' => 'Connecticut', 'tax_rate' => 6.35],
            ['name' => 'Delaware', 'tax_rate' => 0],
            ['name' => 'Florida', 'tax_rate' => 7.00],
            ['name' => 'Georgia', 'tax_rate' => 7.38],
            ['name' => 'Hawaii', 'tax_rate' => 4.50],
            ['name' => 'Idaho', 'tax_rate' => 6.03],
            ['name' => 'Illinois', 'tax_rate' => 8.86],
            ['name' => 'Indiana', 'tax_rate' => 7.00],
            ['name' => 'Iowa', 'tax_rate' => 6.94],
            ['name' => 'Kansas', 'tax_rate' => 8.65],
            ['name' => 'Kentucky', 'tax_rate' => 6.00],
            ['name' => 'Louisiana', 'tax_rate' => 9.56],
            ['name' => 'Maine', 'tax_rate' => 5.50],
            ['name' => 'Maryland', 'tax_rate' => 6.00],
            ['name' => 'Massachusetts', 'tax_rate' => 6.25],
            ['name' => 'Michigan', 'tax_rate' => 6.00],
            ['name' => 'Minnesota', 'tax_rate' => 8.04],
            ['name' => 'Mississippi', 'tax_rate' => 7.06],
            ['name' => 'Missouri', 'tax_rate' => 8.39],
            ['name' => 'Montana', 'tax_rate' => 0],
            ['name' => 'Nebraska', 'tax_rate' => 6.97],
            ['name' => 'Nevada', 'tax_rate' => 8.24],
            ['name' => 'New Hampshire', 'tax_rate' => 0],
            ['name' => 'New Jersey', 'tax_rate' => 6.60],
            ['name' => 'New Mexico', 'tax_rate' => 7.62],
            ['name' => 'New York', 'tax_rate' => 8.53],
            ['name' => 'North Carolina', 'tax_rate' => 7.00],
            ['name' => 'North Dakota', 'tax_rate' => 7.04],
            ['name' => 'Ohio', 'tax_rate' => 7.24],
            ['name' => 'Oklahoma', 'tax_rate' => 8.99],
            ['name' => 'Oregon', 'tax_rate' => 0],
            ['name' => 'Pennsylvania', 'tax_rate' => 6.34],
            ['name' => 'Rhode Island', 'tax_rate' => 7.00],
            ['name' => 'South Carolina', 'tax_rate' => 7.50],
            ['name' => 'South Dakota', 'tax_rate' => 6.11],
            ['name' => 'Tennessee', 'tax_rate' => 9.55],
            ['name' => 'Texas', 'tax_rate' => 8.20],
            ['name' => 'Utah', 'tax_rate' => 7.25],
            ['name' => 'Vermont', 'tax_rate' => 6.36],
            ['name' => 'Virginia', 'tax_rate' => 5.77],
            ['name' => 'Washington', 'tax_rate' => 9.38],
            ['name' => 'West Virginia', 'tax_rate' => 6.57],
            ['name' => 'Wisconsin', 'tax_rate' => 5.70],
            ['name' => 'Wyoming', 'tax_rate' => 5.44],
        ];

        foreach ($states as $state) {
            // Check if state already exists
            $existingState = DB::table('states')
                ->where('name', $state['name'])
                ->where('country_id', $usaCountryId)
                ->first();

            if ($existingState) {
                // Update existing state with tax rate
                DB::table('states')
                    ->where('id', $existingState->id)
                    ->update([
                        'tax_rate' => $state['tax_rate'],
                        'updated_at' => now(),
                    ]);
            } else {
                // Insert new state
                DB::table('states')->insert([
                    'name' => $state['name'],
                    'country_id' => $usaCountryId,
                    'tax_rate' => $state['tax_rate'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        $this->command->info('US states with tax rates seeded successfully!');
    }
}
