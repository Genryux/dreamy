<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SchoolSetting;

class SchoolSettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            'name' => 'Dreamy School',
            'short_name' => 'Dreamy',
            'address_line1' => 'Lot 23 Block 2 PSD 56216 Sitio Tanag, Brgy, San Isidro',
            'address_line2' => '',
            'city' => 'Rodriguez',
            'province' => 'Rizal',
            'country' => 'Philippines',
            'zip' => '1860',
            'phone' => '+63 917 630 0777',
            'email' => 'ph@dreamyedu.net',
            'website' => null,
            'logo_path' => null,
            // Financial defaults
            'down_payment' => null,
            // Due day of month: 10th
            'due_day_of_month' => 10,
            // Do not use last day fallback
            'use_last_day_if_shorter' => false,
        ];

        // Use updateOrCreate to be idempotent for multiple runs
        SchoolSetting::updateOrCreate(['id' => 1], $data);
    }
}
