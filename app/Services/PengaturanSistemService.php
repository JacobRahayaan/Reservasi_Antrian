<?php

namespace App\Services;

use App\Models\PengaturanSistem;

class PengaturanSistemService
{
    public function perbarui(array $data): PengaturanSistem
    {
        $pengaturan = PengaturanSistem::aktif();

        $pengaturan->update($data);

        return $pengaturan->fresh();
    }
}