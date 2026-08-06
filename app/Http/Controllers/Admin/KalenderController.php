<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\KalenderJadwalService;
use Carbon\CarbonImmutable;
use Illuminate\Http\Request;
use Illuminate\View\View;

class KalenderController extends Controller
{
    public function __construct(private readonly KalenderJadwalService $kalenderJadwalService)
    {
    }

    /**
     * Tampilkan kalender ringkasan slot & kuota per bulan. Klik pada
     * tanggal yang punya jadwal mengarahkan Admin ke Kelola Jadwal & Kuota
     * (Sprint 9) dengan filter tanggal tersebut.
     */
    public function index(Request $request): View
    {
        $bulan = $request->filled('bulan')
            ? CarbonImmutable::parse($request->query('bulan') . '-01')->startOfMonth()
            : CarbonImmutable::now()->startOfMonth();

        $data = $this->kalenderJadwalService->ringkasanBulan($bulan);

        $hrefBuilder = fn ($tanggal) => route('admin.jadwal.index', ['tanggal' => $tanggal->toDateString()]);

        return view('dashboard.admin.kalender.index', [
            'bulan' => $bulan,
            'hariData' => $data['hari'],
            'ringkasan' => $data['ringkasan'],
            'hrefBuilder' => $hrefBuilder,
        ]);
    }
}