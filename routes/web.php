<?php

use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\LayananController;
use App\Http\Controllers\Cs\DashboardController as CsDashboardController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\ReservasiController;
use App\Http\Controllers\System\ErrorDemoController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', [LandingController::class, 'index'])->name('landing');

Route::prefix('reservasi')->name('reservasi.')->group(function () {
    Route::get('/create', [ReservasiController::class, 'create'])->name('create');
    Route::get('/jadwal-tersedia', [ReservasiController::class, 'jadwalTersedia'])->name('jadwal-tersedia');
    Route::post('/', [ReservasiController::class, 'store'])->name('store');
    Route::get('/{reservasi}', [ReservasiController::class, 'show'])->name('show');
    Route::get('/{reservasi}/dokumen/{dokumen}/download', [ReservasiController::class, 'downloadDokumen'])
        ->name('dokumen.download');
});

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    Route::resource('layanan', LayananController::class);
    Route::patch('layanan/{layanan}/toggle-status', [LayananController::class, 'toggleStatus'])
        ->name('layanan.toggle-status');
});

Route::prefix('cs')->name('cs.')->group(function () {
    Route::get('/dashboard', [CsDashboardController::class, 'index'])->name('dashboard');
});

Route::prefix('system')->name('system.')->group(function () {
    Route::get('/error-demo', [ErrorDemoController::class, 'index'])->name('error-demo');
});