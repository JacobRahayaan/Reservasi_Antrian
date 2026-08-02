<?php

namespace Database\Seeders;

use App\Models\Layanan;
use Illuminate\Database\Seeder;

class LayananSeeder extends Seeder
{
    public function run(): void
    {
        $layanans = [
            [
                'kode_layanan' => 'A',
                'nama_layanan' => 'Pasang Baru / Tambah Daya',
                'deskripsi' => 'Layanan pengajuan pasang baru listrik atau permohonan penambahan daya.',
                'is_active' => true,
            ],
            [
                'kode_layanan' => 'B',
                'nama_layanan' => 'Tagihan Bulanan',
                'deskripsi' => 'Informasi dan layanan terkait tagihan listrik bulanan Anda.',
                'is_active' => true,
            ],
            [
                'kode_layanan' => 'C',
                'nama_layanan' => 'Gangguan',
                'deskripsi' => 'Laporkan gangguan kelistrikan di area Anda.',
                'is_active' => true,
            ],
        ];

        foreach ($layanans as $layanan) {
            Layanan::query()->updateOrCreate(
                ['kode_layanan' => $layanan['kode_layanan']],
                $layanan
            );
        }
    }
}