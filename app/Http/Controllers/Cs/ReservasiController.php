<?php

namespace App\Http\Controllers\Cs;

use App\Enums\ReservasiStatus;
use App\Http\Controllers\Controller;
use App\Models\Layanan;
use App\Models\Reservasi;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ReservasiController extends Controller
{
    private const STATUS_AKTIF = ['menunggu_review', 'perlu_datang'];
    private const STATUS_RIWAYAT = ['selesai_online', 'selesai', 'dibatalkan'];

    /**
     * Tampilkan halaman Reservasi Customer Service dengan dua tab:
     * Reservasi Aktif (Menunggu Review, Perlu Datang) dan Riwayat Reservasi
     * (Selesai Online, Selesai, Dibatalkan).
     */
    public function index(Request $request): View
    {
        $tab = $request->query('tab') === 'riwayat' ? 'riwayat' : 'aktif';
        $statusGrup = $tab === 'riwayat' ? self::STATUS_RIWAYAT : self::STATUS_AKTIF;

        $filters = [
            'cari' => trim((string) $request->query('cari', '')),
            'layanan_id' => $request->query('layanan_id', ''),
            'status' => $request->query('status', ''),
            'tanggal_mulai' => $request->query('tanggal_mulai', ''),
            'tanggal_akhir' => $request->query('tanggal_akhir', ''),
            'urutan' => $request->query('urutan') === 'terlama' ? 'terlama' : 'terbaru',
        ];

        $reservasis = $this->queryDasar($statusGrup, $filters)
            ->paginate(10)
            ->withQueryString();

        $statistik = $this->hitungStatistik($statusGrup, $filters);

        $layanans = Layanan::query()->orderBy('nama_layanan')->get(['id', 'nama_layanan']);

        $opsiStatus = collect($statusGrup)
            ->mapWithKeys(fn (string $value) => [$value => ReservasiStatus::from($value)->label()]);

        return view('dashboard.cs.reservasi.index', [
            'reservasis' => $reservasis,
            'statistik' => $statistik,
            'layanans' => $layanans,
            'opsiStatus' => $opsiStatus,
            'tab' => $tab,
            'filters' => $filters,
        ]);
    }

    /**
     * Ekspor daftar reservasi (mengikuti tab & filter yang sedang aktif) ke CSV.
     */
    public function export(Request $request): StreamedResponse
    {
        $tab = $request->query('tab') === 'riwayat' ? 'riwayat' : 'aktif';
        $statusGrup = $tab === 'riwayat' ? self::STATUS_RIWAYAT : self::STATUS_AKTIF;

        $filters = [
            'cari' => trim((string) $request->query('cari', '')),
            'layanan_id' => $request->query('layanan_id', ''),
            'status' => $request->query('status', ''),
            'tanggal_mulai' => $request->query('tanggal_mulai', ''),
            'tanggal_akhir' => $request->query('tanggal_akhir', ''),
            'urutan' => $request->query('urutan') === 'terlama' ? 'terlama' : 'terbaru',
        ];

        $reservasis = $this->queryDasar($statusGrup, $filters)->get();

        $namaFile = 'reservasi-' . $tab . '-' . now()->format('Y-m-d-His') . '.csv';

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
     * Query dasar dengan eager loading, search, filter, dan sorting.
     * Dipakai bersama oleh index() dan export() agar hasilnya konsisten.
     *
     * @param  array<int, string>  $statusGrup
     * @param  array<string, string>  $filters
     */
    private function queryDasar(array $statusGrup, array $filters): Builder
    {
        return Reservasi::query()
            ->with([
                'layanan:id,nama_layanan,kode_layanan',
                'jadwal:id,tanggal,jam_mulai,jam_selesai',
                'statusHistories' => fn ($query) => $query->latest('changed_at')->limit(1)->with('petugas:id,nama_petugas'),
            ])
            ->whereIn('status', $statusGrup)
            ->when($filters['cari'] !== '', function (Builder $query) use ($filters) {
                $cari = $filters['cari'];
                $query->where(function (Builder $q) use ($cari) {
                    $q->where('nama', 'like', "%{$cari}%")
                        ->orWhere('nomor_antrean', 'like', "%{$cari}%")
                        ->orWhere('kode_reservasi', 'like', "%{$cari}%");
                });
            })
            ->when($filters['layanan_id'] !== '', fn (Builder $query) => $query->where('layanan_id', $filters['layanan_id']))
            ->when($filters['status'] !== '' && in_array($filters['status'], $statusGrup, true), fn (Builder $query) => $query->where('status', $filters['status']))
            ->when($filters['tanggal_mulai'] !== '', function (Builder $query) use ($filters) {
                $query->whereHas('jadwal', fn (Builder $q) => $q->whereDate('tanggal', '>=', $filters['tanggal_mulai']));
            })
            ->when($filters['tanggal_akhir'] !== '', function (Builder $query) use ($filters) {
                $query->whereHas('jadwal', fn (Builder $q) => $q->whereDate('tanggal', '<=', $filters['tanggal_akhir']));
            })
            ->orderBy('created_at', $filters['urutan'] === 'terlama' ? 'asc' : 'desc');
    }

    /**
     * Hitung ringkasan jumlah reservasi per status untuk kartu statistik,
     * mengikuti filter (kecuali filter status itu sendiri) yang sedang aktif,
     * dalam satu query agregasi.
     *
     * @param  array<int, string>  $statusGrup
     * @param  array<string, string>  $filters
     * @return array<string, int>
     */
    private function hitungStatistik(array $statusGrup, array $filters): array
    {
        $query = Reservasi::query()
            ->whereIn('status', $statusGrup)
            ->when($filters['cari'] !== '', function (Builder $q) use ($filters) {
                $cari = $filters['cari'];
                $q->where(function (Builder $qq) use ($cari) {
                    $qq->where('nama', 'like', "%{$cari}%")
                        ->orWhere('nomor_antrean', 'like', "%{$cari}%")
                        ->orWhere('kode_reservasi', 'like', "%{$cari}%");
                });
            })
            ->when($filters['layanan_id'] !== '', fn (Builder $q) => $q->where('layanan_id', $filters['layanan_id']))
            ->when($filters['tanggal_mulai'] !== '', function (Builder $q) use ($filters) {
                $q->whereHas('jadwal', fn (Builder $qq) => $qq->whereDate('tanggal', '>=', $filters['tanggal_mulai']));
            })
            ->when($filters['tanggal_akhir'] !== '', function (Builder $q) use ($filters) {
                $q->whereHas('jadwal', fn (Builder $qq) => $qq->whereDate('tanggal', '<=', $filters['tanggal_akhir']));
            });

        $counts = (clone $query)
            ->selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        $hasil = [];

        foreach ($statusGrup as $status) {
            $hasil[$status] = (int) ($counts[$status] ?? 0);
        }

        $hasil['total'] = (int) $counts->sum();

        return $hasil;
    }
}