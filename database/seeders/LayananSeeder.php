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
                'deskripsi' => 'Pengajuan pasang baru listrik atau tambah daya sesuai kebutuhan.',
                'estimasi_menit_min' => 60,
                'estimasi_menit_max' => 120,
                'is_active' => true,
            ],
            [
                'kode_layanan' => 'B',
                'nama_layanan' => 'Tagihan Bulanan',
                'deskripsi' => 'Informasi dan layanan terkait tagihan listrik bulanan.',
                'estimasi_menit_min' => 15,
                'estimasi_menit_max' => 30,
                'is_active' => true,
            ],
            [
                'kode_layanan' => 'C',
                'nama_layanan' => 'Gangguan',
                'deskripsi' => 'Laporan gangguan kelistrikan di area Anda.',
                'estimasi_menit_min' => 30,
                'estimasi_menit_max' => 60,
                'is_active' => true,
            ],
        ];

        foreach ($layanans as $layanan) {

            $data = Layanan::withTrashed()->updateOrCreate(
                [
                    'kode_layanan' => $layanan['kode_layanan'],
                ],
                $layanan
            );

            // Jika sebelumnya pernah di-soft delete,
            // aktifkan kembali saat seeding.
            if ($data->trashed()) {
                $data->restore();
            }
        }
    }
}