<?php

namespace App\Console\Commands;

use App\Models\SheetRow;
use Illuminate\Console\Command;

class SheetsGenerate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sheets:generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        SheetRow::factory()
            ->count(10)
            ->columns()
            ->create();
    }
}
