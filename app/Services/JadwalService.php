<?php

namespace App\Services;

use App\Models\Jadwal;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class JadwalService
{
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