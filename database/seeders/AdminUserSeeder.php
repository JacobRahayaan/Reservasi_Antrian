<?php

namespace Database\Seeders;

use App\Models\AdminUser;
use Illuminate\Database\Seeder;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        AdminUser::query()->updateOrCreate(
            ['email' => 'admin@pln.test'],
            [
                'nama' => 'Admin PLN',
                'password' => 'password',
                'is_active' => true,
            ]
        );
    }
}