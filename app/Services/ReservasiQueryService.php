<?php

namespace App\Services;

use App\Enums\StatusSinkronFisik;
use App\Models\Reservasi;
use Illuminate\Database\Eloquent\Builder;

class ReservasiQueryService
{
    /**
     * Query dasar reservasi dengan eager loading, search, filter, dan
     * sorting. Dipakai bersama oleh Admin\ReservasiController (tanpa
     * pembatasan status) dan Cs\ReservasiController (dibatasi statusGrup
     * sesuai tab Aktif/Riwayat) — satu sumber logika, menghindari duplikasi.
     *
     * Parameter $statusSinkronFisik bersifat opsional — dipakai oleh
     * halaman "Belum Dicetak Fisik" untuk menyaring reservasi yang
     * nomor antreannya belum tersinkron ke mesin antrean fisik.
     *
     * @param  array<string, string>  $filters
     * @param  array<int, string>|null  $statusGrup
     */
    public function queryDasar(array $filters, ?array $statusGrup = null, ?StatusSinkronFisik $statusSinkronFisik = null): Builder
    {
        return Reservasi::query()
            ->with([
                'layanan:id,nama_layanan,kode_layanan',
                'jadwal:id,tanggal,jam_mulai,jam_selesai',
                'statusHistories' => fn ($query) => $query->latest('changed_at')->limit(1)->with('petugas:id,nama_petugas'),
            ])
            ->when($statusGrup !== null, fn (Builder $query) => $query->whereIn('status', $statusGrup))
            ->when($statusSinkronFisik !== null, fn (Builder $query) => $query->where('status_sinkron_fisik', $statusSinkronFisik))
            ->when($filters['cari'] !== '', function (Builder $query) use ($filters) {
                $cari = $filters['cari'];
                $query->where(function (Builder $q) use ($cari) {
                    $q->where('nama', 'like', "%{$cari}%")
                        ->orWhere('nomor_antrean', 'like', "%{$cari}%")
                        ->orWhere('kode_reservasi', 'like', "%{$cari}%");
                });
            })
            ->when($filters['layanan_id'] !== '', fn (Builder $query) => $query->where('layanan_id', $filters['layanan_id']))
            ->when(
                $filters['status'] !== '' && ($statusGrup === null || in_array($filters['status'], $statusGrup, true)),
                fn (Builder $query) => $query->where('status', $filters['status'])
            )
            ->when($filters['tanggal_mulai'] !== '', function (Builder $query) use ($filters) {
                $query->whereHas('jadwal', fn (Builder $q) => $q->whereDate('tanggal', '>=', $filters['tanggal_mulai']));
            })
            ->when($filters['tanggal_akhir'] !== '', function (Builder $query) use ($filters) {
                $query->whereHas('jadwal', fn (Builder $q) => $q->whereDate('tanggal', '<=', $filters['tanggal_akhir']));
            })
            ->orderBy('created_at', ($filters['urutan'] ?? 'terbaru') === 'terlama' ? 'asc' : 'desc');
    }

    /**
     * Hitung jumlah reservasi per status untuk kartu statistik, mengikuti
     * filter (kecuali filter status itu sendiri) yang sedang aktif, dalam
     * satu query agregasi.
     *
     * @param  array<int, string>  $statusUntukDihitung
     * @param  array<string, string>  $filters
     * @param  array<int, string>|null  $statusGrup
     * @return array<string, int>
     */
    public function hitungStatistik(array $statusUntukDihitung, array $filters, ?array $statusGrup = null): array
    {
        $query = Reservasi::query()
            ->when($statusGrup !== null, fn (Builder $q) => $q->whereIn('status', $statusGrup))
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

        foreach ($statusUntukDihitung as $status) {
            $hasil[$status] = (int) ($counts[$status] ?? 0);
        }

        $hasil['total'] = (int) $counts->sum();

        return $hasil;
    }
}