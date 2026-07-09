<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AssetController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\PreventiveController;
use App\Http\Controllers\CorrectiveController;
use App\Http\Controllers\HistoryController;
use App\Http\Controllers\SparepartController;
use App\Http\Controllers\VendorController;
use App\Http\Controllers\ReportController;

Route::redirect('/', '/login');

Route::middleware('auth')->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    Route::resource('assets', AssetController::class);

    Route::resource('tickets', TicketController::class);

    Route::resource('preventives', PreventiveController::class);

    Route::resource('correctives', CorrectiveController::class);

    Route::resource('vendors', VendorController::class);

    Route::resource('spareparts', SparepartController::class);

    Route::get('/reports', [ReportController::class, 'index'])
    ->name('reports');

    Route::get('/reports/assets/pdf', [ReportController::class, 'exportPdf'])
    ->name('reports.assets.pdf');

    Route::view('/settings', 'settings.index')->name('settings');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::view('/corrective', 'corrective.index')->name('corrective');
    Route::get('/history', [HistoryController::class, 'index'])
        ->name('history');
});

require __DIR__ . '/auth.php';
