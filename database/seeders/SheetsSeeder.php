<?php

namespace Database\Seeders;

use App\Models\SheetRow;

use Illuminate\Database\Seeder;

class SheetsSeeder extends Seeder
{
    public function run(): void
    {
        SheetRow::factory()->create();
    }
}
