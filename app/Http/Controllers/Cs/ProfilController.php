<?php

namespace App\Http\Controllers\Cs;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateProfilCsRequest;
use App\Models\Petugas;
use App\Services\PetugasService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ProfilController extends Controller
{
    public function __construct(private readonly PetugasService $petugasService)
    {
    }

    /**
     * Tampilkan halaman Profil Saya untuk Customer Service, didukung data
     * petugas yang sesungguhnya sedang login (guard `petugas`) — tidak lagi
     * memakai simulasi Petugas::aktifSaatIni().
     */
    public function index(): View
    {
        /** @var Petugas $petugas */
        $petugas = Auth::guard('petugas')->user();

        return view('dashboard.cs.profil.index', compact('petugas'));
    }

    /**
     * Simpan perubahan profil. Memakai PetugasService yang sama dengan
     * modul Kelola Pengguna Admin — satu logika penyimpanan, tanpa duplikasi.
     */
    public function update(UpdateProfilCsRequest $request): RedirectResponse
    {
        /** @var Petugas $petugas */
        $petugas = Auth::guard('petugas')->user();

        $this->petugasService->perbarui($petugas, $request->validated());

        return redirect()
            ->route('cs.profil.index')
            ->with('success', 'Profil berhasil diperbarui.');
    }
}