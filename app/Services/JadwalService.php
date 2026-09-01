<?php
namespace App\Services;
use App\Models\Jadwal;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
class JadwalService
{
    private const BATAS_MAKSIMAL_SLOT = 1000;

    public function buat(array $data): Jadwal
    {
        return DB::transaction(function () use ($data) {
            return Jadwal::create([
                'layanan_id' => $data['layanan_id'],
                'tanggal' => $data['tanggal'],
                'jam_mulai' => $data['jam_mulai'],
                'jam_selesai' => $data['jam_selesai'],
                'kuota_maksimal' => $data['kuota_maksimal'],
                'kuota_terpakai' => 0,
                'is_active' => $data['is_active'],
            ]);
        });
    }

    /**
     * Generate banyak slot jadwal sekaligus dari rentang tanggal, hari
     * tertentu dalam seminggu, dan jam operasional yang dipecah per
     * interval. Kombinasi tanggal+jam_mulai yang sudah punya jadwal
     * identik akan dilewati (bukan gagal total), supaya Admin bisa
     * menjalankan generate berulang kali dengan aman tanpa duplikat.
     *
     * @return array{dibuat: int, dilewati: int}
     */
    public function buatBerulang(array $data): array
    {
        return DB::transaction(function () use ($data) {
            $tanggalMulai = Carbon::parse($data['tanggal_mulai']);
            $tanggalSelesai = Carbon::parse($data['tanggal_selesai']);
            $hariDipilih = array_map('intval', $data['hari']);

            $jamAwal = Carbon::createFromFormat('H:i', $data['jam_awal']);
            $jamAkhir = Carbon::createFromFormat('H:i', $data['jam_akhir']);
            $intervalMenit = (int) $data['interval_menit'];

            $this->pastikanTidakTerlaluBanyak($tanggalMulai, $tanggalSelesai, $hariDipilih, $jamAwal, $jamAkhir, $intervalMenit);

            $dibuat = 0;
            $dilewati = 0;

            for ($tanggal = $tanggalMulai->copy(); $tanggal->lte($tanggalSelesai); $tanggal->addDay()) {
                if (! in_array((int) $tanggal->dayOfWeek, $hariDipilih, true)) {
                    continue;
                }

                for ($slotMulai = $jamAwal->copy(); $slotMulai->lt($jamAkhir); $slotMulai->addMinutes($intervalMenit)) {
                    $slotSelesai = $slotMulai->copy()->addMinutes($intervalMenit);

                    if ($slotSelesai->gt($jamAkhir)) {
                        break;
                    }

                    $sudahAda = Jadwal::query()
                        ->where('layanan_id', $data['layanan_id'])
                        ->whereDate('tanggal', $tanggal->toDateString())
                        ->where('jam_mulai', $slotMulai->format('H:i'))
                        ->exists();

                    if ($sudahAda) {
                        $dilewati++;
                        continue;
                    }

                    Jadwal::create([
                        'layanan_id' => $data['layanan_id'],
                        'tanggal' => $tanggal->toDateString(),
                        'jam_mulai' => $slotMulai->format('H:i'),
                        'jam_selesai' => $slotSelesai->format('H:i'),
                        'kuota_maksimal' => $data['kuota_maksimal_berulang'],
                        'kuota_terpakai' => 0,
                        'is_active' => $data['is_active'],
                    ]);

                    $dibuat++;
                }
            }

            if ($dibuat === 0 && $dilewati === 0) {
                throw ValidationException::withMessages([
                    'hari' => 'Tidak ada tanggal yang cocok dengan hari yang dipilih dalam rentang tersebut.',
                ]);
            }

            return ['dibuat' => $dibuat, 'dilewati' => $dilewati];
        });
    }

    /**
     * Pengaman agar Admin tidak tidak sengaja men-generate ribuan baris
     * (misal salah isi interval 5 menit untuk rentang 1 tahun).
     */
    private function pastikanTidakTerlaluBanyak(Carbon $tanggalMulai, Carbon $tanggalSelesai, array $hariDipilih, Carbon $jamAwal, Carbon $jamAkhir, int $intervalMenit): void
    {
        $jumlahHariCocok = 0;
        for ($tanggal = $tanggalMulai->copy(); $tanggal->lte($tanggalSelesai); $tanggal->addDay()) {
            if (in_array((int) $tanggal->dayOfWeek, $hariDipilih, true)) {
                $jumlahHariCocok++;
            }
        }

        $slotPerHari = (int) floor($jamAwal->diffInMinutes($jamAkhir) / $intervalMenit);
        $estimasiTotal = $jumlahHariCocok * max($slotPerHari, 0);

        if ($estimasiTotal > self::BATAS_MAKSIMAL_SLOT) {
            throw ValidationException::withMessages([
                'tanggal_selesai' => "Kombinasi ini akan membuat sekitar {$estimasiTotal} jadwal sekaligus (maksimal " . self::BATAS_MAKSIMAL_SLOT . '). Persempit rentang tanggal atau perbesar interval.',
            ]);
        }
    }

    /**
     * Perbarui jadwal. Menolak penurunan kuota di bawah jumlah reservasi
     * yang sudah terisi, sebagai lapisan pengaman kedua selain validasi
     * pada Form Request (mencegah race condition antar-request bersamaan).
     */
    public function perbarui(Jadwal $jadwal, array $data): Jadwal
    {
        return DB::transaction(function () use ($jadwal, $data) {
            $jadwalTerkunci = Jadwal::query()->lockForUpdate()->findOrFail($jadwal->id);
            if ($data['kuota_maksimal'] < $jadwalTerkunci->kuota_terpakai) {
                throw ValidationException::withMessages([
                    'kuota_maksimal' => "Kuota tidak boleh dikurangi hingga di bawah jumlah reservasi yang sudah ada ({$jadwalTerkunci->kuota_terpakai}).",
                ]);
            }
            $jadwalTerkunci->update([
                'layanan_id' => $data['layanan_id'],
                'tanggal' => $data['tanggal'],
                'jam_mulai' => $data['jam_mulai'],
                'jam_selesai' => $data['jam_selesai'],
                'kuota_maksimal' => $data['kuota_maksimal'],
                'is_active' => $data['is_active'],
            ]);
            return $jadwalTerkunci->fresh();
        });
    }
    public function toggleStatus(Jadwal $jadwal): Jadwal
    {
        $jadwal->update(['is_active' => ! $jadwal->is_active]);
        return $jadwal->fresh();
    }
    /**
     * Hapus jadwal permanen. Hanya diizinkan jika belum pernah dipakai
     * reservasi sama sekali — jika sudah dipakai, method ini melempar
     * ValidationException agar Controller dapat menampilkan pesan yang
     * mengarahkan Admin untuk menonaktifkan saja.
     */
    public function hapus(Jadwal $jadwal): void
    {
        if (! $jadwal->bolehDihapusPermanen()) {
            throw ValidationException::withMessages([
                'jadwal' => 'Jadwal ini sudah memiliki reservasi dan tidak dapat dihapus. Gunakan tombol nonaktifkan sebagai gantinya.',
            ]);
        }
        $jadwal->delete();
    }
}