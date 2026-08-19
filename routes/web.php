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

    // Asset QR Code Download & Print
    Route::get('/assets/{asset}/qr/download', [AssetController::class, 'downloadQr'])
        ->name('assets.qr.download');
    Route::get('/assets/{asset}/qr/print', [AssetController::class, 'printQr'])
        ->name('assets.qr.print');

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

    Route::middleware('role:developer')->group(function () {
        Route::post('/settings/wipe', function (\Illuminate\Http\Request $request) {

            $targets = $request->input('targets', []);

            if (empty($targets)) {
                return redirect()->back()->with('error', 'Pilih setidaknya satu kategori data input untuk dibersihkan.');
            }

            \Illuminate\Support\Facades\Schema::disableForeignKeyConstraints();
            $cleared = [];

            if (in_array('tickets', $targets)) {
                if (\Illuminate\Support\Facades\Schema::hasTable('ticket_activities')) \App\Models\TicketActivity::truncate();
                if (\Illuminate\Support\Facades\Schema::hasTable('ticket_technician')) \Illuminate\Support\Facades\DB::table('ticket_technician')->delete();
                if (\Illuminate\Support\Facades\Schema::hasTable('ticket_work_logs')) \Illuminate\Support\Facades\DB::table('ticket_work_logs')->delete();
                \App\Models\Ticket::truncate();
                $cleared[] = 'Tickets & History';
            }

            if (in_array('corrective', $targets)) {
                \App\Models\Corrective::truncate();
                $cleared[] = 'Corrective Maintenance';
            }

            if (in_array('preventive', $targets)) {
                \App\Models\Preventive::truncate();
                $cleared[] = 'Preventive Maintenance';
            }

            if (in_array('assets', $targets)) {
                \App\Models\Asset::truncate();
                $cleared[] = 'Assets / Equipment';
            }

            if (in_array('schedules', $targets) || in_array('technician_schedules', $targets)) {
                if (\Illuminate\Support\Facades\Schema::hasTable('technician_schedules')) \App\Models\TechnicianSchedule::truncate();
                if (\Illuminate\Support\Facades\Schema::hasTable('technician_schedule_exceptions')) \App\Models\TechnicianScheduleException::truncate();
                $cleared[] = 'Jadwal & History Teknisi';
            }

            if (in_array('spareparts', $targets)) {
                \App\Models\Sparepart::truncate();
                $cleared[] = 'Spareparts';
            }

            if (in_array('vendors', $targets)) {
                \App\Models\Vendor::truncate();
                $cleared[] = 'Vendors';
            }

            if (in_array('movements', $targets) || in_array('equipment_movements', $targets)) {
                if (\Illuminate\Support\Facades\Schema::hasTable('equipment_movements')) {
                    \Illuminate\Support\Facades\DB::table('equipment_movements')->delete();
                }
                if (\Illuminate\Support\Facades\Schema::hasTable('asset_movements')) {
                    \Illuminate\Support\Facades\DB::table('asset_movements')->delete();
                }
                if (\Illuminate\Support\Facades\Schema::hasTable('tickets')) {
                    \App\Models\Ticket::query()->update([
                        'sent_to_workshop_date' => null,
                        'sent_by' => null,
                        'received_by_workshop' => null,
                        'returned_date' => null,
                        'returned_by' => null,
                        'received_by_user' => null,
                        'equipment_completeness' => null,
                    ]);
                }
                $cleared[] = 'Equipment Movements History';
            }

            if (in_array('documents', $targets) || in_array('document_center', $targets)) {
                if (\Illuminate\Support\Facades\Schema::hasTable('documents')) {
                    \App\Models\Document::truncate();
                }
                $cleared[] = 'Document Center';
            }

            \Illuminate\Support\Facades\Schema::enableForeignKeyConstraints();

            $msg = 'Data input (' . implode(', ', $cleared) . ') berhasil dibersihkan dari database.';
            return redirect()->back()->with('success', $msg);
        })->name('settings.wipe');
    });


    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/history', [HistoryController::class, 'index'])
        ->name('history');

    Route::get('/technicians', [\App\Http\Controllers\TechnicianController::class, 'index'])
        ->name('technicians.index');
    Route::get('/technicians/duty-statuses', [\App\Http\Controllers\TechnicianController::class, 'dutyStatuses'])
        ->name('technicians.duty-statuses');
    Route::post('/technicians', [\App\Http\Controllers\TechnicianController::class, 'store'])
        ->name('technicians.store');
    Route::post('/technicians/import-schedule', [\App\Http\Controllers\TechnicianController::class, 'importSchedule'])
        ->name('technicians.import-schedule');
    Route::post('/technicians/exceptions', [\App\Http\Controllers\TechnicianController::class, 'storeException'])
        ->name('technicians.exceptions.store');
    Route::delete('/technicians/exceptions/{id}', [\App\Http\Controllers\TechnicianController::class, 'destroyException'])
        ->name('technicians.exceptions.destroy');
    Route::post('/technicians/{technician}/override', [\App\Http\Controllers\TechnicianController::class, 'toggleOverride'])
        ->name('technicians.override');
    Route::get('/technicians/{id}', [\App\Http\Controllers\TechnicianController::class, 'show'])
        ->name('technicians.show');
    Route::put('/technicians/{technician}', [\App\Http\Controllers\TechnicianController::class, 'update'])
        ->name('technicians.update');

    // Preventive - Fetch data (room/asset) for report refactor
    Route::get('/preventive-assets/by-room', [\App\Http\Controllers\PreventiveAssetController::class, 'assetsByRoom'])
        ->name('preventive-assets.by-room');

    Route::get('/preventive-assets/{asset}', [\App\Http\Controllers\PreventiveAssetController::class, 'assetDetail'])
        ->name('preventive-assets.detail');
});

require __DIR__ . '/auth.php';
