<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdatePengaturanSistemRequest;
use App\Models\PengaturanSistem;
use App\Services\PengaturanSistemService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class PengaturanSistemController extends Controller
{
    public function __construct(private readonly PengaturanSistemService $pengaturanSistemService)
    {
    }

    /**
     * Tampilkan form Pengaturan Sistem. Halaman ini bukan resource CRUD —
     * hanya satu baris data yang selalu ada (dijamin oleh PengaturanSistem::aktif()).
     */
    public function index(): View
    {
        $pengaturan = PengaturanSistem::aktif();

        return view('dashboard.admin.pengaturan.index', compact('pengaturan'));
    }

    /**
     * Simpan perubahan Pengaturan Sistem.
     */
    public function update(UpdatePengaturanSistemRequest $request): RedirectResponse
    {
        $this->pengaturanSistemService->perbarui($request->validated());

        return redirect()
            ->route('admin.pengaturan.index')
            ->with('success', 'Pengaturan sistem berhasil disimpan.');
    }
}