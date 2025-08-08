<?php

namespace App\Console\Commands;

use App\Models\SheetRow;
use App\Services\GoogleSpreadsheetService;
use Google\Service\Exception;
use Illuminate\Console\Command;

class SheetsSync extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sheets:sync';

    /**
     * @var string
     */
    protected $description = 'This command will sync all spreadsheet data';

    /**
     * @return void
     * @throws Exception
     */
    public function handle(): void
    {

        $sheets = SheetRow::where('status', '=', SheetRow::STATUS_ALLOWED)
            ->get()
            ->map(function ($row) {
                $fields = $row->cells->map(fn($cell) => $cell->content);
                return [
                    ...$fields,
                    $row->comment
                ];
            });

        $googleSheetsService = new GoogleSpreadsheetService();
        $googleSheetsService->clearValues('Sheet1!A1:Z');
        $googleSheetsService->updateValues('Sheet1!A1:Z', $sheets);
    }
}
