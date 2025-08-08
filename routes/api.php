<?php

use App\Http\Controllers\RowController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\SheetController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::post('sheets/sync', [SheetController::class, 'sync'])
        ->name('sheets.sync');

    Route::get('sheets/generate', [SheetController::class, 'generate'])
        ->name('sheets.generate');

    Route::delete('sheets/truncate', [SheetController::class, 'truncate'])
        ->name('sheets.truncate');

    Route::resource('rows', RowController::class)
        ->except(['create', 'edit']);

    Route::post('rows/{row}/change-status', [RowController::class, 'changeStatus'])
        ->name('rows.change-status');

    Route::post('settings/update', [SettingsController::class, 'update'])
        ->name('settings.update');
});
