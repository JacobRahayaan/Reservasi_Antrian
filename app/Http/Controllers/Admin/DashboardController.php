<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Tampilkan halaman ringkasan dashboard Admin.
     */
    public function index(): View
    {
        return view('dashboard.admin.index');
    }
}