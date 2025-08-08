<?php

namespace App\Http\Controllers;

use App\Models\SheetRow;
use Illuminate\Http\Request;

class TestController extends Controller
{
    public function index()
    {
        return SheetRow::all();
    }
}
