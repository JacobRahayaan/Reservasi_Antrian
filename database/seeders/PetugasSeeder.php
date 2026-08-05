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
                'email' => 'amanda@pln.co.id',
                'no_hp' => '081234500001',
                'is_active' => true,
            ],
            [
                'nama_petugas' => 'CS. Budi',
                'email' => 'budi@pln.co.id',
                'no_hp' => '081234500002',
                'is_active' => true,
            ],
            [
                'nama_petugas' => 'CS. Rian',
                'email' => 'rian@pln.co.id',
                'no_hp' => '081234500003',
                'is_active' => true,
            ],
        ];

        foreach ($petugas as $data) {
            Petugas::query()->updateOrCreate(['email' => $data['email']], $data);
        }
    }
}