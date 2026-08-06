<?php

namespace App\Http\Controllers\Admin;

use App\Enums\ReservasiStatus;
use App\Http\Controllers\Controller;
use App\Models\Layanan;
use App\Models\Reservasi;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class LaporanController extends Controller
{
    /**
     * Tampilkan halaman Laporan: ringkasan statistik untuk rentang tanggal
     * (berdasarkan tanggal jadwal kedatangan) dan jenis layanan tertentu.
     */
    public function index(Request $request): View
    {
        $filters = $this->ambilFilter($request);

        $ringkasanStatus = $this->hitungRingkasanStatus($filters);
        $kpi = $this->hitungKpi($ringkasanStatus);
        $trenHarian = $this->trenHarian($filters);
        $distribusiLayanan = $this->distribusiLayanan($filters);
        $ringkasanPerLayanan = $this->ringkasanPerLayanan($filters);

        $layanans = Layanan::query()->orderBy('nama_layanan')->get(['id', 'nama_layanan']);

        return view('dashboard.admin.laporan.index', [
            'filters' => $filters,
            'ringkasanStatus' => $ringkasanStatus,
            'kpi' => $kpi,
            'trenHarian' => $trenHarian,
            'distribusiLayanan' => $distribusiLayanan,
            'totalDistribusiLayanan' => array_sum(array_column($distribusiLayanan, 'jumlah')),
            'ringkasanPerLayanan' => $ringkasanPerLayanan,
            'layanans' => $layanans,
        ]);
    }

    /**
     * Ekspor ringkasan per layanan (mengikuti filter periode yang aktif) ke CSV.
     */
    public function export(Request $request): StreamedResponse
    {
        $filters = $this->ambilFilter($request);
        $ringkasanPerLayanan = $this->ringkasanPerLayanan($filters);

        $namaFile = 'laporan-reservasi-' . now()->format('Y-m-d-His') . '.csv';

        return response()->streamDownload(function () use ($ringkasanPerLayanan, $filters) {
            $output = fopen('php://output', 'w');

            fputcsv($output, ['Periode', $filters['tanggal_mulai'] . ' s/d ' . $filters['tanggal_akhir']]);
            fputcsv($output, []);
            fputcsv($output, ['Layanan', 'Total', 'Menunggu Review', 'Perlu Datang', 'Selesai Online', 'Selesai', 'Dibatalkan']);

            foreach ($ringkasanPerLayanan as $baris) {
                fputcsv($output, [
                    $baris['nama_layanan'],
                    $baris['total'],
                    $baris['menunggu_review'],
                    $baris['perlu_datang'],
                    $baris['selesai_online'],
                    $baris['selesai'],
                    $baris['dibatalkan'],
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
            'tanggal_mulai' => $request->filled('tanggal_mulai')
                ? $request->query('tanggal_mulai')
                : now()->subDays(29)->toDateString(),
            'tanggal_akhir' => $request->filled('tanggal_akhir')
                ? $request->query('tanggal_akhir')
                : now()->toDateString(),
            'layanan_id' => $request->query('layanan_id', ''),
        ];
    }

    /**
     * @param  array<string, string>  $filters
     */
    private function baseQuery(array $filters): Builder
    {
        return Reservasi::query()
            ->join('jadwals', 'jadwals.id', '=', 'reservasis.jadwal_id')
            ->whereBetween('jadwals.tanggal', [$filters['tanggal_mulai'], $filters['tanggal_akhir']])
            ->when(
                $filters['layanan_id'] !== '',
                fn (Builder $query) => $query->where('reservasis.layanan_id', $filters['layanan_id'])
            );
    }

    /**
     * @param  array<string, string>  $filters
     * @return array<string, int>
     */
    private function hitungRingkasanStatus(array $filters): array
    {
        $counts = $this->baseQuery($filters)
            ->selectRaw('reservasis.status as status, COUNT(*) as total')
            ->groupBy('reservasis.status')
            ->pluck('total', 'status');

        $hasil = ['total' => (int) $counts->sum()];

        foreach (ReservasiStatus::cases() as $status) {
            $hasil[$status->value] = (int) ($counts[$status->value] ?? 0);
        }

        return $hasil;
    }

    /**
     * Hitung 3 KPI sesuai PRD Section 20:
     * - Tingkat Penyelesaian Online: % Selesai Online dari (Selesai + Selesai Online)
     * - Tingkat Penyelesaian: % (Selesai + Selesai Online) dari Total
     * - Tingkat Pembatalan: % Dibatalkan dari Total
     *
     * @param  array<string, int>  $ringkasan
     * @return array<string, int>
     */
    private function hitungKpi(array $ringkasan): array
    {
        $totalSelesai = $ringkasan['selesai'] + $ringkasan['selesai_online'];

        return [
            'persen_selesai_online' => $totalSelesai > 0
                ? (int) round(($ringkasan['selesai_online'] / $totalSelesai) * 100)
                : 0,
            'persen_penyelesaian' => $ringkasan['total'] > 0
                ? (int) round(($totalSelesai / $ringkasan['total']) * 100)
                : 0,
            'persen_pembatalan' => $ringkasan['total'] > 0
                ? (int) round(($ringkasan['dibatalkan'] / $ringkasan['total']) * 100)
                : 0,
        ];
    }

    /**
     * @param  array<string, string>  $filters
     * @return array<string, int>
     */
    private function trenHarian(array $filters): array
    {
        $mulai = Carbon::parse($filters['tanggal_mulai']);
        $akhir = Carbon::parse($filters['tanggal_akhir']);

        $counts = $this->baseQuery($filters)
            ->selectRaw('jadwals.tanggal as tanggal, COUNT(*) as total')
            ->groupBy('jadwals.tanggal')
            ->pluck('total', 'tanggal');

        $data = [];

        for ($tanggal = $mulai->copy(); $tanggal->lte($akhir); $tanggal->addDay()) {
            $data[$tanggal->translatedFormat('d M')] = (int) ($counts[$tanggal->toDateString()] ?? 0);
        }

        return $data;
    }

    /**
     * Distribusi selalu dihitung lintas seluruh layanan (mengabaikan filter
     * layanan_id itu sendiri) agar donut chart tetap bermakna sebagai
     * perbandingan antar layanan, meski filter layanan sedang aktif.
     *
     * @param  array<string, string>  $filters
     * @return array<int, array{label: string, jumlah: int}>
     */
    private function distribusiLayanan(array $filters): array
    {
        $filtersTanpaLayanan = [...$filters, 'layanan_id' => ''];

        $counts = $this->baseQuery($filtersTanpaLayanan)
            ->selectRaw('reservasis.layanan_id as layanan_id, COUNT(*) as total')
            ->groupBy('reservasis.layanan_id')
            ->pluck('total', 'layanan_id');

        return Layanan::query()
            ->get()
            ->map(fn (Layanan $layanan) => [
                'label' => $layanan->nama_layanan,
                'jumlah' => (int) ($counts[$layanan->id] ?? 0),
            ])
            ->values()
            ->all();
    }

    /**
     * @param  array<string, string>  $filters
     * @return array<int, array<string, int|string>>
     */
    private function ringkasanPerLayanan(array $filters): array
    {
        $filtersTanpaLayanan = [...$filters, 'layanan_id' => ''];

        $counts = $this->baseQuery($filtersTanpaLayanan)
            ->selectRaw('reservasis.layanan_id as layanan_id, reservasis.status as status, COUNT(*) as total')
            ->groupBy('reservasis.layanan_id', 'reservasis.status')
            ->get();

        return Layanan::query()
            ->orderBy('nama_layanan')
            ->get()
            ->map(function (Layanan $layanan) use ($counts) {
                $baris = ['nama_layanan' => $layanan->nama_layanan, 'total' => 0];

                foreach (ReservasiStatus::cases() as $status) {
                    $jumlah = (int) $counts
                        ->where('layanan_id', $layanan->id)
                        ->where('status', $status->value)
                        ->sum('total');

                    $baris[$status->value] = $jumlah;
                    $baris['total'] += $jumlah;
                }

                return $baris;
            })
            ->all();
    }
}