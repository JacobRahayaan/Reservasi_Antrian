<?php

namespace App\Http\Controllers\Cs;

use App\Enums\ReservasiStatus;
use App\Http\Controllers\Controller;
use Illuminate\View\View;

class PanduanController extends Controller
{
    /**
     * Tampilkan halaman Panduan Customer Service. Konten bersifat statis,
     * kecuali tabel arti status yang diambil langsung dari enum
     * ReservasiStatus agar selalu konsisten dengan label & deskripsi yang
     * benar-benar dipakai di seluruh sistem (bukan disalin ulang manual).
     */
    public function index(): View
    {
        $daftarStatus = ReservasiStatus::cases();

        return view('dashboard.cs.panduan.index', compact('daftarStatus'));
    }
}