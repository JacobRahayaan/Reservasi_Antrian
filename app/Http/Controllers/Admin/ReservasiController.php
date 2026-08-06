<?php

namespace App\Http\Controllers\Admin;

use App\Enums\ReservasiStatus;
use App\Http\Controllers\Controller;
use App\Models\Layanan;
use App\Services\ReservasiQueryService;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ReservasiController extends Controller
{
    public function __construct(private readonly ReservasiQueryService $queryService)
    {
    }

    /**
     * Tampilkan seluruh reservasi (semua status) untuk keperluan monitoring
     * Admin. Bersifat read-only — perubahan status/catatan tetap menjadi
     * wewenang Customer Service (Sprint 5/6).
     */
    public function index(Request $request): View
    {
        $filters = $this->ambilFilter($request);

        $reservasis = $this->queryService->queryDasar($filters)
            ->paginate(10)
            ->withQueryString();

        $statusSemua = array_map(fn (ReservasiStatus $status) => $status->value, ReservasiStatus::cases());
        $statistik = $this->queryService->hitungStatistik($statusSemua, $filters);

        $layanans = Layanan::query()->orderBy('nama_layanan')->get(['id', 'nama_layanan']);

        $opsiStatus = collect(ReservasiStatus::cases())
            ->mapWithKeys(fn (ReservasiStatus $status) => [$status->value => $status->label()]);

        return view('dashboard.admin.reservasi.index', [
            'reservasis' => $reservasis,
            'statistik' => $statistik,
            'layanans' => $layanans,
            'opsiStatus' => $opsiStatus,
            'filters' => $filters,
        ]);
    }

    /**
     * Ekspor seluruh reservasi (mengikuti filter yang sedang aktif) ke CSV.
     */
    public function export(Request $request): StreamedResponse
    {
        $filters = $this->ambilFilter($request);

        $reservasis = $this->queryService->queryDasar($filters)->get();

        $namaFile = 'reservasi-admin-' . now()->format('Y-m-d-His') . '.csv';

        return response()->streamDownload(function () use ($reservasis) {
            $output = fopen('php://output', 'w');

            fputcsv($output, ['No. Antrean', 'Kode Reservasi', 'Nama', 'Nomor HP', 'Layanan', 'Tanggal', 'Jam', 'Status', 'Dibuat Pada']);

            foreach ($reservasis as $reservasi) {
                fputcsv($output, [
                    $reservasi->nomor_antrean,
                    $reservasi->kode_reservasi,
                    $reservasi->nama,
                    $reservasi->nomor_hp,
                    $reservasi->layanan->nama_layanan,
                    $reservasi->jadwal->tanggal->toDateString(),
                    substr($reservasi->jadwal->jam_mulai, 0, 5) . ' - ' . substr($reservasi->jadwal->jam_selesai, 0, 5),
                    $reservasi->status->label(),
                    $reservasi->created_at->format('Y-m-d H:i'),
                ]);
            }

            fclose($output);
        }, $namaFile, ['Content-Type' => 'text/csv']);
    }

    /**
     * @return array<string, string>
     */
    private function ambilFilter(Request $request): array
    {
        return [
            'cari' => trim((string) $request->query('cari', '')),
            'layanan_id' => $request->query('layanan_id', ''),
            'status' => $request->query('status', ''),
            'tanggal_mulai' => $request->query('tanggal_mulai', ''),
            'tanggal_akhir' => $request->query('tanggal_akhir', ''),
            'urutan' => $request->query('urutan') === 'terlama' ? 'terlama' : 'terbaru',
        ];
    }
}