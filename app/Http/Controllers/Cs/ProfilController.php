<?php

namespace App\Http\Controllers\Cs;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateProfilCsRequest;
use App\Models\Petugas;
use App\Services\PetugasService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ProfilController extends Controller
{
    public function __construct(private readonly PetugasService $petugasService)
    {
    }

    /**
     * Tampilkan halaman Profil Saya untuk Customer Service, didukung data
     * nyata dari Petugas::aktifSaatIni() — berbeda dari Profil Admin yang
     * read-only, halaman ini benar-benar dapat diubah dan tersimpan.
     */
    public function index(): View
    {
        $petugas = Petugas::aktifSaatIni();

        return view('dashboard.cs.profil.index', compact('petugas'));
    }

    /**
     * Simpan perubahan profil. Memakai PetugasService yang sama dengan
     * modul Kelola Pengguna Admin — satu logika penyimpanan, tanpa duplikasi.
     */
    public function update(UpdateProfilCsRequest $request): RedirectResponse
    {
        $petugas = Petugas::aktifSaatIni();

        $this->petugasService->perbarui($petugas, $request->validated());

        return redirect()
            ->route('cs.profil.index')
            ->with('success', 'Profil berhasil diperbarui.');
    }
}