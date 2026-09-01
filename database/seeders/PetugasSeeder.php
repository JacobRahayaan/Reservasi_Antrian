<?php

namespace Database\Seeders;

use App\Models\Petugas;
use Illuminate\Database\Seeder;

class PetugasSeeder extends Seeder
{
    public function run(): void
    {
        $petugas = [
            [
                'nama_petugas' => 'CS. Amanda',
                'email' => 'amanda@pln.test',
                'no_hp' => '081234500001',
                'password' => 'password',
                'is_active' => true,
            ],
            [
                'nama_petugas' => 'CS. Budi',
                'email' => 'budi@pln.test',
                'no_hp' => '081234500002',
                'password' => 'password',
                'is_active' => true,
            ],
            [
                'nama_petugas' => 'CS. Rian',
                'email' => 'rian@pln.test',
                'no_hp' => '081234500003',
                'password' => 'password',
                'is_active' => true,
            ],
        ];

        foreach ($petugas as $data) {
            Petugas::query()->updateOrCreate(
                ['nama_petugas' => $data['nama_petugas']],
                $data
            );
        }
    }
}