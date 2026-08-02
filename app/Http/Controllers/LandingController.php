<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class LandingController extends Controller
{
    /**
     * Tampilkan halaman landing publik SIRA-PLN.
     */
    public function index(): View
    {
        return view('pages.landing');
    }
}