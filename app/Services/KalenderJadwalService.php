<?php

namespace App\Services;

use App\Models\Jadwal;
use Carbon\Carbon;
use Carbon\CarbonImmutable;
use Illuminate\Support\Collection;

class KalenderJadwalService
{
    /**
     * Susun ringkasan jadwal untuk satu bulan penuh, dihitung dalam satu
     * query agregasi per tanggal (bukan query per hari), sehingga sebuah
     * bulan dengan 28–31 hari tetap hanya butuh satu query database.
     *
     * @return array{hari: Collection, ringkasan: array<string, int>}
     */
    public function ringkasanBulan(CarbonImmutable $bulan): array
    {
        $awal = $bulan->startOfMonth();
        $akhir = $bulan->endOfMonth();

        $agregat = Jadwal::query()
            ->whereBetween('tanggal', [$awal->toDateString(), $akhir->toDateString()])
            ->selectRaw('tanggal, COUNT(*) as jumlah_slot, SUM(kuota_maksimal) as kuota_maksimal, SUM(kuota_terpakai) as kuota_terpakai')
            ->groupBy('tanggal')
            ->get()
            ->keyBy(fn ($row) => Carbon::parse($row->tanggal)->toDateString());

        $hari = collect();

        for ($tanggal = $awal; $tanggal->lte($akhir); $tanggal = $tanggal->addDay()) {
            $key = $tanggal->toDateString();
            $data = $agregat->get($key);

            $jumlahSlot = $data ? (int) $data->jumlah_slot : 0;
            $kuotaMaksimal = $data ? (int) $data->kuota_maksimal : 0;
            $kuotaTerpakai = $data ? (int) $data->kuota_terpakai : 0;

            $hari->put($key, [
                'tanggal' => $tanggal,
                'jumlah_slot' => $jumlahSlot,
                'kuota_maksimal' => $kuotaMaksimal,
                'kuota_terpakai' => $kuotaTerpakai,
                'sisa_kuota' => max(0, $kuotaMaksimal - $kuotaTerpakai),
                'persentase_terisi' => $kuotaMaksimal > 0 ? (int) round(($kuotaTerpakai / $kuotaMaksimal) * 100) : 0,
                'ada_jadwal' => $jumlahSlot > 0,
            ]);
        }

        $ringkasan = [
            'total_slot' => (int) $hari->sum('jumlah_slot'),
            'total_kuota' => (int) $hari->sum('kuota_maksimal'),
            'total_terisi' => (int) $hari->sum('kuota_terpakai'),
            'total_sisa' => (int) $hari->sum('sisa_kuota'),
        ];

        return ['hari' => $hari, 'ringkasan' => $ringkasan];
    }
}