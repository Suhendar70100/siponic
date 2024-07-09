<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DeviceController;
use App\Http\Controllers\GardenController;
use App\Http\Controllers\HistoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MonitoringController;
use App\Http\Controllers\InformationController;

Route::get('/', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth', 'admin')->group(function () {
    // route garden
    Route::get('/garden', [GardenController::class, 'index'])->name('garden');
});

Route::middleware('auth')->group(function () {
    // route garden
    Route::get('/device', [DeviceController::class, 'index'])->name('device');
    // route history
    Route::get('/history', [HistoryController::class, 'index'])->name('history');
    //  route monitoring
    Route::get('/monitoring', [MonitoringController::class, 'index'])->name('monitoring');
    // route information
    Route::get('/information', [InformationController::class, 'index'])->name('information');

});

require __DIR__.'/auth.php';
