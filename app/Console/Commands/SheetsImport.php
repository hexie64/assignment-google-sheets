<?php

namespace App\Console\Commands;

use App\Repository\SheetDataRepository;
use App\Services\SpreadsheetService;
use Illuminate\Console\Command;
use Illuminate\Http\Client\RequestException;

class SheetsImport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sheets:import';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import data from google spreadsheet';

    /**
     * Execute the console command.
     * @throws RequestException
     */
    public function handle(): void
    {
        $response = (new SpreadsheetService())->fetchAll();
        $values = $response['values'] ?? [];

        if (empty($values)) {
            return;
        }

        $columns = array_shift($values);

        foreach ($values as $key => $row) {
            $combined = array_combine($columns, $row);
            $model = (new SheetDataRepository())->store([
                'data' => $combined
            ]);
        }
    }
}
