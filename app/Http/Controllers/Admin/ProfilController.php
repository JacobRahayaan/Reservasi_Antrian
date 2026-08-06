<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PengaturanSistem;
use Illuminate\View\View;

class ProfilController extends Controller
{
    /**
     * Tampilkan halaman Profil Saya untuk Admin. Bersifat READ-ONLY karena
     * sistem belum memiliki tabel/model yang merepresentasikan identitas
     * individual Admin (fitur Login dilarang dibangun di sprint manapun
     * sejauh ini). "Admin" hanya label statis yang dipakai di seluruh
     * aplikasi. Halaman ini menampilkan info yang jujur tanpa form edit
     * yang datanya tidak akan pernah benar-benar tersimpan.
     */
    public function index(): View
    {
        $pengaturan = PengaturanSistem::aktif();

        return view('dashboard.admin.profil.index', compact('pengaturan'));
    }
}