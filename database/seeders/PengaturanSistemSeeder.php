<?php

namespace Database\Seeders;

use App\Models\PengaturanSistem;
use Illuminate\Database\Seeder;

class PengaturanSistemSeeder extends Seeder
{
    public function run(): void
    {
        PengaturanSistem::query()->updateOrCreate(
            ['id' => 1],
            [
                'nama_aplikasi' => 'SIRA-PLN',
                'nomor_contact_center' => '123',
                'email_contact_center' => 'info@pln.co.id',
                'alamat_kantor' => 'Jl. Contoh No. 123, Kota Contoh, 12345',
                'jam_buka_default' => '08:00',
                'jam_tutup_default' => '15:00',
                'maksimal_ukuran_dokumen_mb' => 2,
                'maksimal_jumlah_dokumen' => 3,
            ]
        );
    }
}