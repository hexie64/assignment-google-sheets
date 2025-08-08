<?php

namespace Database\Seeders;

use App\Models\Setting;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class SettingsSeeder extends Seeder
{
    public function run(): void
    {
        Setting::create([
            'label' => 'Google Spreadsheets Url',
            'key' => 'google_spreadsheets_url',
            'value' => ''
        ]);
    }
}
