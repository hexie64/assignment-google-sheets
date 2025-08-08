<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    /**
     * @return Response
     */
    public function index(): Response
    {
        return Inertia::render('Dashboard', [
            'spreadsheet_url' => Setting::fromKey('google_spreadsheets_url'),
        ]);
    }
}
