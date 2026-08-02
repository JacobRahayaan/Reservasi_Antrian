<?php

namespace App\Http\Controllers\Cs;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Tampilkan halaman ringkasan dashboard Customer Service.
     */
    public function index(): View
    {
        return view('dashboard.cs.index');
    }
}