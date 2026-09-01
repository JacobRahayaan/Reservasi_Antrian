<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            AdminUserSeeder::class,
            LayananSeeder::class,
            PetugasSeeder::class,
            JadwalSeeder::class,
            PengaturanSistemSeeder::class,
        ]);
    }
}