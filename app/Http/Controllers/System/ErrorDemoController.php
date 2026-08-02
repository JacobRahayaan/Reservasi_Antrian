<?php

namespace App\Http\Controllers\System;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class ErrorDemoController extends Controller
{
    /**
     * Tampilkan halaman contoh error/empty state (placeholder Sprint 0).
     */
    public function index(): View
    {
        return view('dashboard.system.error');
    }
}