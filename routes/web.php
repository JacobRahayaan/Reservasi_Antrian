<?php

use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Cs\DashboardController as CsDashboardController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\System\ErrorDemoController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes — Sprint 0 (Placeholder)
|--------------------------------------------------------------------------
|
| Seluruh route di bawah ini masih placeholder. Middleware autentikasi/
| otorisasi, validasi, dan logika bisnis akan ditambahkan pada sprint
| fitur yang relevan.
|
*/

Route::get('/', [LandingController::class, 'index'])->name('landing');

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
});

Route::prefix('cs')->name('cs.')->group(function () {
    Route::get('/dashboard', [CsDashboardController::class, 'index'])->name('dashboard');
});

Route::prefix('system')->name('system.')->group(function () {
    Route::get('/error-demo', [ErrorDemoController::class, 'index'])->name('error-demo');
});