<?php

namespace App\Console\Commands;

use App\Services\SpreadsheetService;
use Illuminate\Console\Command;
use Illuminate\Http\Client\RequestException;
use App\Repository\SheetDataRepository;

class SheetsFetch extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sheets:fetch {amount=1000}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * @return void
     * @throws RequestException
     */
    public function handle(): void
    {
        $amount = 1000;
        if ($this->argument('amount')) {
            $amount = $this->argument('amount');
        }

        $range = 'A1:Z2' . $amount;
        $rows = (new SpreadsheetService())->fetchAll($range);

        foreach($rows['values'] as $data) {
            (new SheetDataRepository())->store([
                'data' => $data
            ]);
        }
    }
}
