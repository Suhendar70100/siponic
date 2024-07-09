<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DeviceController;
use App\Http\Controllers\GardenController;
use App\Http\Controllers\HistoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MonitoringController;
use App\Http\Controllers\InformationController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::middleware('auth:sanctum')->as('api.')->group(function () {
    // garden
    Route::get('/garden', [GardenController::class, 'dataTable'])->name('garden.dataTable');
    Route::post('/garden', [GardenController::class, 'store'])->name('garden.store');
    Route::get('/garden/{id}', [GardenController::class, 'show'])->name('garden.show');
    Route::put('/garden/{id}', [GardenController::class, 'update'])->name('garden.update');
    Route::delete('/garden/{id}', [GardenController::class, 'delete'])->name('garden.delete');

     // device
    Route::get('/device', [DeviceController::class, 'dataTable'])->name('device.dataTable');
    Route::post('/device', [DeviceController::class, 'store'])->name('device.store');
    Route::post('device/status/{device}', [DeviceController::class, 'status'])->name('device.status.update');
    Route::get('/device/{id}', [DeviceController::class, 'show'])->name('device.show');
    Route::put('/device/{id}', [DeviceController::class, 'update'])->name('device.update');
    Route::delete('/device/{id}', [DeviceController::class, 'delete'])->name('device.delete');

    // information
    Route::get('/information', [InformationController::class, 'dataTable'])->name('information.dataTable');
    Route::post('/information', [InformationController::class, 'store'])->name('information.store');
    Route::get('/information/{id}', [InformationController::class, 'show'])->name('information.show');
    Route::put('/information/{id}', [InformationController::class, 'update'])->name('information.update');
    Route::delete('/information/{id}', [InformationController::class, 'delete'])->name('information.delete');

    // history
    Route::get('/history', [HistoryController::class, 'dataTable'])->name('history.dataTable');
    //  monitoring
    Route::get('/monitoring', [MonitoringController::class, 'getDailyAverages'])->name('monitoring');
    // realtime data
    Route::get('/realtime', [DashboardController::class, 'realtimeData'])->name('realtimeData');
});


