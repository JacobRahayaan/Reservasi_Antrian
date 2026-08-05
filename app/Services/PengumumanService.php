<?php

namespace App\Services;

use App\Models\Pengumuman;

class PengumumanService
{
    public function buat(array $data): Pengumuman
    {
        return Pengumuman::create($data);
    }

    public function perbarui(Pengumuman $pengumuman, array $data): Pengumuman
    {
        $pengumuman->update($data);

        return $pengumuman->fresh();
    }

    public function toggleStatus(Pengumuman $pengumuman): Pengumuman
    {
        $pengumuman->update(['is_active' => ! $pengumuman->is_active]);

        return $pengumuman->fresh();
    }

    public function hapus(Pengumuman $pengumuman): void
    {
        $pengumuman->delete();
    }
}