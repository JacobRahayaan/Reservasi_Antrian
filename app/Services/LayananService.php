<?php

namespace App\Services;

use App\Models\Layanan;
use Illuminate\Support\Facades\DB;

class LayananService
{
    /**
     * Membuat layanan baru.
     */
    public function buat(array $data): Layanan
    {
        return Layanan::create($data);
    }

    /**
     * Memperbarui data layanan.
     */
    public function perbarui(Layanan $layanan, array $data): Layanan
    {
        $layanan->update($data);

        return $layanan->fresh();
    }

    /**
     * Mengaktifkan / menonaktifkan layanan.
     */
    public function toggleStatus(Layanan $layanan): Layanan
    {
        $layanan->update([
            'is_active' => ! $layanan->is_active,
        ]);

        return $layanan->fresh();
    }

    /**
     * Menghapus layanan.
     *
     * Jika layanan sudah pernah dipakai reservasi,
     * maka dilakukan soft delete agar histori tetap aman.
     *
     * Jika belum pernah dipakai sama sekali,
     * maka dilakukan force delete.
     */
    public function hapus(Layanan $layanan): array
    {
        return DB::transaction(function () use ($layanan) {

            if ($layanan->pernahDigunakan()) {

                $layanan->update([
                    'is_active' => false,
                ]);

                $layanan->delete();

                return [
                    'mode' => 'soft_delete',
                    'message' => "Layanan \"{$layanan->nama_layanan}\" berhasil dinonaktifkan.",
                ];
            }

            $layanan->forceDelete();

            return [
                'mode' => 'hard_delete',
                'message' => "Layanan \"{$layanan->nama_layanan}\" berhasil dihapus permanen.",
            ];
        });
    }
}