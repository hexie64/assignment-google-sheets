<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\RowController;
use App\Http\Controllers\SheetController;
use Illuminate\Support\Facades\Route;

Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
Route::get('/row/create', [RowController::class, 'create'])->name('row.create');
Route::get('/row/{row}', [RowController::class, 'edit'])->name('row.edit');

Route::get('/fetch/{amount?}', [SheetController::class, 'fetch'])
    ->name('sheets.fetch');

Route::get('/fetch-progress/{amount?}', [SheetController::class, 'fetchProgress'])
    ->name('sheets.fetch-progress');

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
