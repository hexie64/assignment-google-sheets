<?php

namespace App\Services;

use App\Models\Setting;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;
use RuntimeException;

class SpreadsheetService
{
    private const API_URL = 'https://sheets.googleapis.com/v4/spreadsheets/';
    private const SETTING_API_KEY = 'google_sheets_api_key';
    private const SETTING_SPREADSHEET_URL = 'google_spreadsheets_url';

    protected string $spreadSheetId;
    protected string $spreadSheetApiKey;

    public function __construct()
    {
        $this->spreadSheetApiKey = $this->getApiKey();
        $this->spreadSheetId = $this->getSpreadSheetId();
    }

    /**
     * @return string
     */
    protected function getApiKey(): string
    {
        return Setting::fromKey(self::SETTING_API_KEY);
    }

    /**
     * @return string|null
     */
    protected function getSpreadSheetId(): ?string
    {
        $spreadSheetUrl = Setting::fromKey(self::SETTING_SPREADSHEET_URL);
        preg_match('/\/d\/([a-zA-Z0-9-_]+)/', $spreadSheetUrl, $matches);
        $spreadSheetId = $matches[1];

        if (!$spreadSheetId) {
            throw new RuntimeException('Spreadsheet ID is not set.');
        }

        return $spreadSheetId;
    }

    /**
     * @param string $range
     * @return array
     * @throws RequestException
     */
    public function fetchAll(string $range = 'A1:Z20'): array
    {
        $url = self::API_URL . $this->spreadSheetId . "/values/{$range}";
        $response = Http::get($url, ['key' => $this->spreadSheetApiKey]);

        $response->throw();
        return $response->json();
    }

    /**
     * @param $data
     * @param string $range
     * @return array
     * @throws RequestException
     */
    public function updateAll($data, string $range = 'A1:Z20'): array
    {
        $url = self::API_URL . $this->spreadSheetId . "/values/{$range}";
        $response = Http::post($url, [
            'key' => $this->spreadSheetApiKey
        ]);

        $response->throw();
        return $response->json();
    }
}
