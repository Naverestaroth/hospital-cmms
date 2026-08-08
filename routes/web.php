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
use App\Http\Controllers\DocumentController;

Route::redirect('/', '/login');

Route::middleware('auth')->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    Route::get('/api/dashboard/ticket-analytics', [DashboardController::class, 'ticketAnalytics'])
        ->name('dashboard.ticket-analytics');

    // Asset import (Excel) - declare before resource routes to avoid path conflicts
    Route::get('/assets/import', [\App\Http\Controllers\AssetImportController::class, 'showUpload'])
        ->name('assets.import.upload');

    Route::post('/assets/import/preview', [\App\Http\Controllers\AssetImportController::class, 'preview'])
        ->name('assets.import.preview-action');

    Route::get('/assets/import/preview', [\App\Http\Controllers\AssetImportController::class, 'previewPage'])
        ->name('assets.import.preview');

    Route::post('/assets/import', [\App\Http\Controllers\AssetImportController::class, 'confirmImport'])
        ->name('assets.import.confirm');

    Route::resource('assets', AssetController::class);

    Route::resource('documents', DocumentController::class);

    Route::get(
        '/documents/{document}/view',
        [DocumentController::class, 'view']
    )->name('documents.view');

    Route::get('/tickets/assets-by-room', [TicketController::class, 'assetsByRoom'])
        ->name('tickets.assets-by-room');
    Route::post('/tickets/{ticket}/approve', [TicketController::class, 'approve'])
        ->name('tickets.approve');
    Route::post('/tickets/{ticket}/reject', [TicketController::class, 'reject'])
        ->name('tickets.reject');
    Route::post('/tickets/{ticket}/assign', [TicketController::class, 'assignTechnicians'])
        ->name('tickets.assign');
    Route::post('/tickets/{ticket}/self-assign', [TicketController::class, 'selfAssign'])
        ->name('tickets.self-assign');
    Route::post('/tickets/{ticket}/accept', [TicketController::class, 'accept'])
        ->name('tickets.accept');
    Route::post('/tickets/{ticket}/status', [TicketController::class, 'updateStatus'])
        ->name('tickets.update-status');
    Route::post('/tickets/{ticket}/close', [TicketController::class, 'close'])
        ->name('tickets.close');
    Route::post('/tickets/{ticket}/work-performed', [TicketController::class, 'updateWorkPerformed'])
        ->name('tickets.update-work-performed');
    Route::get('/equipment-movements', [TicketController::class, 'movements'])
        ->name('tickets.movements');
    Route::post('/tickets/{ticket}/movement', [TicketController::class, 'updateMovement'])
        ->name('tickets.update-movement');

    Route::resource('tickets', TicketController::class);

    // Preventive import (Excel)
    Route::get('/preventives/import', [\App\Http\Controllers\PreventiveImportController::class, 'showUpload'])
        ->name('preventives.import.upload');
    Route::post('/preventives/import/preview', [\App\Http\Controllers\PreventiveImportController::class, 'preview'])
        ->name('preventives.import.preview-action');
    Route::get('/preventives/import/preview', [\App\Http\Controllers\PreventiveImportController::class, 'previewPage'])
        ->name('preventives.import.preview');
    Route::post('/preventives/import', [\App\Http\Controllers\PreventiveImportController::class, 'confirmImport'])
        ->name('preventives.import.confirm');

    Route::resource('preventives', PreventiveController::class);

    // Preventive dependent dropdown (Room -> Assets) + Asset detail (JSON)
    Route::get('/preventives/assets-by-room', [\App\Http\Controllers\PreventiveAssetController::class, 'assetsByRoom'])
        ->name('preventives.assets-by-room');

    Route::get('/preventives/asset-detail/{asset}', [\App\Http\Controllers\PreventiveAssetController::class, 'assetDetail'])
        ->name('preventives.asset-detail');

    // Corrective import (Excel)
    Route::get('/correctives/import', [\App\Http\Controllers\CorrectiveImportController::class, 'showUpload'])
        ->name('correctives.import.upload');
    Route::post('/correctives/import/preview', [\App\Http\Controllers\CorrectiveImportController::class, 'preview'])
        ->name('correctives.import.preview-action');
    Route::get('/correctives/import/preview', [\App\Http\Controllers\CorrectiveImportController::class, 'previewPage'])
        ->name('correctives.import.preview');
    Route::post('/correctives/import', [\App\Http\Controllers\CorrectiveImportController::class, 'confirmImport'])
        ->name('correctives.import.confirm');

    Route::resource('correctives', CorrectiveController::class);

    Route::resource('vendors', VendorController::class);

    // Spareparts import (Excel)
    Route::get('/spareparts/import', [\App\Http\Controllers\SparepartImportController::class, 'showUpload'])
        ->name('spareparts.import.upload');
    Route::post('/spareparts/import/preview', [\App\Http\Controllers\SparepartImportController::class, 'preview'])
        ->name('spareparts.import.preview-action');
    Route::get('/spareparts/import/preview', [\App\Http\Controllers\SparepartImportController::class, 'previewPage'])
        ->name('spareparts.import.preview');
    Route::post('/spareparts/import', [\App\Http\Controllers\SparepartImportController::class, 'confirmImport'])
        ->name('spareparts.import.confirm');

    Route::resource('spareparts', SparepartController::class);

    Route::get('/reports', [ReportController::class, 'index'])
        ->name('reports');

    Route::get('/reports/assets/pdf', [ReportController::class, 'exportPdf'])
        ->name('reports.assets.pdf');

    Route::view('/settings', 'settings.index')->name('settings');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/history', [HistoryController::class, 'index'])
        ->name('history');

    Route::view('/technicians', 'technicians.index')
        ->name('technicians.index');

    Route::get('/technicians/{id}', function ($id) {
        $technicians = [
            1 => [
                'name' => 'Andi Pratama',
                'email' => 'andi.pratama@example.com',
                'phone' => '+62 812-3456-7890',
                'buildings' => [
                    ['name' => 'Gedung A', 'description' => 'Main hospital wing'],
                    ['name' => 'Gedung C', 'description' => 'Equipment storage'],
                ],
                'summary' => [
                    'active_tasks' => 5,
                    'completed_corrective' => 8,
                    'completed_preventive' => 4,
                    'total_maintenance' => 17,
                ],
                'active_corrective' => [
                    ['ticket' => 'C-021', 'building' => 'Gedung A', 'due' => '2026-08-05'],
                    ['ticket' => 'C-017', 'building' => 'Gedung C', 'due' => '2026-08-10'],
                ],
                'active_preventive' => [
                    ['schedule' => 'P-102', 'building' => 'Gedung A', 'due' => '2026-08-03'],
                    ['schedule' => 'P-109', 'building' => 'Gedung C', 'due' => '2026-08-11'],
                ],
                'history' => [
                    ['date' => '2026-06-20', 'activity' => 'AC unit replacement'],
                    ['date' => '2026-05-15', 'activity' => 'Pump repair'],
                    ['date' => '2026-04-08', 'activity' => 'Generator inspection'],
                ],
            ],
            2 => [
                'name' => 'Budi Santoso',
                'email' => 'budi.santoso@example.com',
                'phone' => '+62 812-9876-5432',
                'buildings' => [
                    ['name' => 'Gedung B', 'description' => 'Main maintenance block'],
                    ['name' => 'Gedung D', 'description' => 'Storage and repairs'],
                ],
                'summary' => [
                    'active_tasks' => 3,
                    'completed_corrective' => 12,
                    'completed_preventive' => 6,
                    'total_maintenance' => 21,
                ],
                'active_corrective' => [
                    ['ticket' => 'C-043', 'building' => 'Gedung B', 'due' => '2026-08-07'],
                    ['ticket' => 'C-038', 'building' => 'Gedung D', 'due' => '2026-08-12'],
                ],
                'active_preventive' => [
                    ['schedule' => 'P-118', 'building' => 'Gedung B', 'due' => '2026-08-04'],
                    ['schedule' => 'P-121', 'building' => 'Gedung D', 'due' => '2026-08-14'],
                ],
                'history' => [
                    ['date' => '2026-06-12', 'activity' => 'Boiler inspection'],
                    ['date' => '2026-05-06', 'activity' => 'Valve replacement'],
                    ['date' => '2026-04-22', 'activity' => 'Lighting check'],
                ],
            ],
        ];

        $technician = $technicians[$id] ?? $technicians[1];

        return view('technicians.show', ['technician' => $technician]);
    })
        ->name('technicians.show');

    // Preventive - Fetch data (room/asset) for report refactor
    Route::get('/preventive-assets/by-room', [\App\Http\Controllers\PreventiveAssetController::class, 'assetsByRoom'])
        ->name('preventive-assets.by-room');

    Route::get('/preventive-assets/{asset}', [\App\Http\Controllers\PreventiveAssetController::class, 'assetDetail'])
        ->name('preventive-assets.detail');
});

require __DIR__ . '/auth.php';
