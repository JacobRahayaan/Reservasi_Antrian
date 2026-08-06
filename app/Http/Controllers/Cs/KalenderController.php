<?php

namespace App\Http\Controllers\Cs;

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
     * tanggal yang punya jadwal mengarahkan CS ke Reservasi Aktif
     * (Sprint 5) dengan filter tanggal tersebut.
     */
    public function index(Request $request): View
    {
        $bulan = $request->filled('bulan')
            ? CarbonImmutable::parse($request->query('bulan') . '-01')->startOfMonth()
            : CarbonImmutable::now()->startOfMonth();

        $data = $this->kalenderJadwalService->ringkasanBulan($bulan);

        $hrefBuilder = fn ($tanggal) => route('cs.reservasi.index', [
            'tab' => 'aktif',
            'tanggal_mulai' => $tanggal->toDateString(),
            'tanggal_akhir' => $tanggal->toDateString(),
        ]);

        return view('dashboard.cs.kalender.index', [
            'bulan' => $bulan,
            'hariData' => $data['hari'],
            'ringkasan' => $data['ringkasan'],
            'hrefBuilder' => $hrefBuilder,
        ]);
    }
}