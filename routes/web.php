<?php

use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\JadwalController;
use App\Http\Controllers\Admin\LayananController;
use App\Http\Controllers\Admin\PengumumanController;
use App\Http\Controllers\Cs\DashboardController as CsDashboardController;
use App\Http\Controllers\Cs\ReservasiController as CsReservasiController;
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
    Route::get('/{reservasi}/ubah-jadwal', [ReservasiController::class, 'editJadwal'])->name('ubah-jadwal.edit');
    Route::put('/{reservasi}/ubah-jadwal', [ReservasiController::class, 'updateJadwal'])->name('ubah-jadwal.update');
    Route::delete('/{reservasi}/batalkan', [ReservasiController::class, 'batalkan'])->name('batalkan');
    Route::get('/{reservasi}/dokumen/{dokumen}/download', [ReservasiController::class, 'downloadDokumen'])
        ->name('dokumen.download');
    Route::get('/{reservasi}/dokumen/{dokumen}/preview', [ReservasiController::class, 'previewDokumen'])
        ->name('dokumen.preview');
});

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    Route::resource('layanan', LayananController::class);
    Route::patch('layanan/{layanan}/toggle-status', [LayananController::class, 'toggleStatus'])
        ->name('layanan.toggle-status');

    Route::get('jadwal/export', [JadwalController::class, 'export'])->name('jadwal.export');
    Route::resource('jadwal', JadwalController::class);
    Route::patch('jadwal/{jadwal}/toggle-status', [JadwalController::class, 'toggleStatus'])
        ->name('jadwal.toggle-status');

    Route::resource('pengumuman', PengumumanController::class);
    Route::patch('pengumuman/{pengumuman}/toggle-status', [PengumumanController::class, 'toggleStatus'])
        ->name('pengumuman.toggle-status');
});

Route::prefix('cs')->name('cs.')->group(function () {
    Route::get('/dashboard', [CsDashboardController::class, 'index'])->name('dashboard');

    Route::get('reservasi/export', [CsReservasiController::class, 'export'])->name('reservasi.export');
    Route::get('reservasi', [CsReservasiController::class, 'index'])->name('reservasi.index');
    Route::get('reservasi/{reservasi}', [CsReservasiController::class, 'show'])->name('reservasi.show');
    Route::put('reservasi/{reservasi}/status', [CsReservasiController::class, 'updateStatus'])->name('reservasi.status.update');
    Route::post('reservasi/{reservasi}/catatan', [CsReservasiController::class, 'storeCatatan'])->name('reservasi.catatan.store');
});

Route::prefix('system')->name('system.')->group(function () {
    Route::get('/error-demo', [ErrorDemoController::class, 'index'])->name('error-demo');
});