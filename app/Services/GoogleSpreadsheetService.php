<?php

namespace App\Services;

use App\Models\Setting;
use Google\Client;
use Google\Exception as GoogleException;
use Google\Service\Exception;
use Google\Service\Drive;
use Google\Service\Sheets\UpdateValuesResponse;
use RuntimeException;
use Google_Service_Sheets_ValueRange as SheetsValueRange;
use Google_Service_Sheets as SheetsService;
use Google_Service_Sheets_ClearValuesRequest as ClearValuesRequest;

class GoogleSpreadsheetService
{

    private string $spreadsheetId;

    private Client $client;

    /**
     * @throws Exception
     * @throws GoogleException
     */
    public function __construct()
    {
        $this->spreadsheetId = $this->getSpreadSheetId();
        $this->authenticate();
    }

    /**
     * @return void
     * @throws GoogleException
     */
    protected function authenticate(): void
    {
        $this->getSpreadSheetId();

        $this->client = new Client();
        $credentials_path = storage_path('app/private/service_credentials.json');
        $this->client->setAuthConfig($credentials_path);
        $this->client->setApprovalPrompt('force');
        $this->client->useApplicationDefaultCredentials();
        $this->client->addScope(Drive::DRIVE);
    }

    /**
     * @return string
     */
    protected function getSpreadSheetId(): string
    {
        $spreadSheetUrl = Setting::fromKey('google_spreadsheets_url');
        preg_match('/\/d\/([a-zA-Z0-9-_]+)/', $spreadSheetUrl, $matches);
        $spreadSheetId = $matches[1];

        if (!$spreadSheetId) {
            throw new RuntimeException('Spreadsheet ID is not set.');
        }

        return $spreadSheetId;
    }

    /**
     * @param $range
     * @return object
     * @throws Exception
     */
    public function getValues($range): object
    {

        $service = new SheetsService($this->client);
        $response = $service->spreadsheets_values->get($this->spreadsheetId, $range);
        $response = collect($response->getValues());

        return (object)[
            'headers' => $response->first(),
            'rows' =>  $response->slice(1)->values()->toArray(),
        ];
    }

    /**
     * @throws Exception
     */
    public function updateValues($range, $values)
    {
        $service = new SheetsService($this->client);
        $body = new SheetsValueRange([
            'values' => $values
        ]);

        $response = $service->spreadsheets_values->update(
            $this->spreadsheetId,
            $range,
            $body,
            [
                'valueInputOption' => 'RAW'
            ]
        );


        return $response['updatedRows'];
    }

    /**
     * @param $range
     * @return void
     * @throws Exception
     */
    public function clearValues($range): void
    {
        $service = new SheetsService($this->client);
        $clearRequest = new ClearValuesRequest();
        $service->spreadsheets_values->clear($this->spreadsheetId, $range, $clearRequest);
    }

    /**
     * @param $range
     * @param $values
     * @return UpdateValuesResponse
     * @throws Exception
     */
    public function batchUpdate($range, $values): UpdateValuesResponse
    {
        $service = new SheetsService($this->client);
        $body = new SheetsValueRange([
            'values' => $values
        ]);

        $params = [
            'valueInputOption' => 'RAW'
        ];

        return $service->spreadsheets_values->update($this->spreadsheetId, $range, $body, $params);
    }
}
