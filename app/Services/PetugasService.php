<?php

namespace App\Services;

use App\Models\Petugas;

class PetugasService
{
    public function buat(array $data): Petugas
    {
        return Petugas::create($data);
    }

    public function perbarui(Petugas $petugas, array $data): Petugas
    {
        $petugas->update($data);

        return $petugas->fresh();
    }

    public function toggleStatus(Petugas $petugas): Petugas
    {
        $petugas->update(['is_active' => ! $petugas->is_active]);

        return $petugas->fresh();
    }

    /**
     * Hapus petugas. Ditolak jika petugas pernah bertindak (menulis catatan
     * atau mengubah status reservasi) — sesuai constraint FK
     * `reservasi_notes.petugas_id` (restrictOnDelete) yang dipasang sejak
     * Sprint 6, sehingga hard delete akan gagal di level database. Admin
     * diarahkan menonaktifkan petugas tersebut sebagai gantinya.
     *
     * @return array{berhasil: bool, message: string}
     */
    public function hapus(Petugas $petugas): array
    {
        if ($petugas->pernahBertindak()) {
            return [
                'berhasil' => false,
                'message' => "Petugas \"{$petugas->nama_petugas}\" tidak dapat dihapus karena memiliki riwayat aktivitas (catatan atau perubahan status). Nonaktifkan petugas ini sebagai gantinya.",
            ];
        }

        $nama = $petugas->nama_petugas;
        $petugas->delete();

        return [
            'berhasil' => true,
            'message' => "Petugas \"{$nama}\" berhasil dihapus.",
        ];
    }
}