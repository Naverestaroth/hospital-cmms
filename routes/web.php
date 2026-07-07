<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/login');

Route::middleware('auth')->group(function () {

    Route::view('/dashboard', 'dashboard')->name('dashboard');

    Route::view('/assets', 'assets.index')->name('assets');

    Route::view('/tickets', 'tickets.index')->name('tickets');

    Route::view('/preventive', 'preventive.index')->name('preventive');

    Route::view('/vendors', 'vendors.index')->name('vendors');

    Route::view('/spareparts', 'spareparts.index')->name('spareparts');

    Route::view('/reports', 'reports.index')->name('reports');

    Route::view('/settings', 'settings.index')->name('settings');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

});

require __DIR__.'/auth.php';