<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use App\Models\SheetRow;
use App\Repository\SheetDataRepository;
use App\Utils\BrowserTable;
use Illuminate\Http\JsonResponse;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Symfony\Component\Console\{Helper\Table, Output\BufferedOutput};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Symfony\Component\HttpFoundation\StreamedResponse;

use App\Console\Commands\SheetsFetch;

class SheetController extends Controller
{
    private SheetDataRepository $sheetRepository;

    public function __construct(SheetDataRepository $sheetDataRepository)
    {
        $this->sheetRepository = $sheetDataRepository;
    }

    /**
     * @return JsonResponse
     */
    public function generate(): JsonResponse
    {
        SheetRow::factory()
            ->count(10)
            ->state(new Sequence(
                ['status' => 'allowed'],
                ['status' => 'prohibited']
            ))
            ->columns()
            ->create();

        $sheets = SheetRow::all();
        return response()->json($sheets);
    }

    /**
     * @return JsonResponse
     */
    public function truncate(): JsonResponse
    {
        SheetRow::truncate();
        return response()->json(['status' => 'success']);
    }

    public function sync(Request $request): JsonResponse
    {
        Setting::where('key', 'google_spreadsheets_url')->update([
            'value' => request('value')
        ]);

        Artisan::call(SheetsFetch::class);

        return response()->json(['status' => 'success']);
    }

    /**
     * @return StreamedResponse
     */
    public function fetch(): StreamedResponse
    {
        $headers = $this->sheetRepository->getHeaders();
        $rows = $this->sheetRepository->getRows(request('amount'));

        $output = new BufferedOutput();
        (new Table($output))->setHeaders($headers)
            ->setRows($rows)
            ->render();

        $result = $output->fetch();

        return response()->stream(function () use ($result) {
            echo "<pre>{$result}</pre>";
        }, 200, [
            'Content-Type' => 'text/html',
            'Cache-Control' => 'no-cache',
            'Connection' => 'keep-alive',
        ]);
    }

    /**
     * @return StreamedResponse
     */
    public function fetchProgress(): StreamedResponse
    {
        $headers = $this->sheetRepository->getHeaders();
        $rows = $this->sheetRepository->getRows(request('amount'));

        $browserTable = new BrowserTable($headers, $rows);

        return response()->stream(function () use ($browserTable) {
            $browserTable->render();
        }, 200, [
            'Content-Type' => 'text/event-stream',
            'Cache-Control' => 'no-cache',
            'Connection' => 'keep-alive',
        ]);
    }
}
