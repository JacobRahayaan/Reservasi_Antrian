<?php

namespace App\View\Composers;

use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthUserComposer
{
    /**
     * Suntikkan data user yang sedang login (Admin atau CS, tergantung
     * guard mana yang aktif) ke partial topbar. Ini menggantikan kebutuhan
     * mengedit belasan @section('user-name'/'user-role'/'user-initial') di
     * setiap view dashboard satu per satu — perubahan terpusat di satu
     * tempat sesuai prinsip refactor minimal yang disepakati.
     *
     * Jika tidak ada guard yang aktif (mis. halaman system.error-demo yang
     * tidak diproteksi), variabel authUser bernilai null dan topbar akan
     * jatuh kembali ke @yield('user-name', 'Admin') dkk seperti semula.
     */
    public function compose(View $view): void
    {
        $user = Auth::guard('admin')->user() ?? Auth::guard('petugas')->user();

        $view->with('authUser', $user);
    }
}