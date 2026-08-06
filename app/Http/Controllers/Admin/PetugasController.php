<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePetugasRequest;
use App\Http\Requests\UpdatePetugasRequest;
use App\Models\Petugas;
use App\Models\ReservasiNote;
use App\Models\StatusHistory;
use App\Services\PetugasService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PetugasController extends Controller
{
    private const SORTABLE_COLUMNS = [
        'nama_petugas',
        'email',
        'is_active',
        'created_at',
    ];

    public function __construct(private readonly PetugasService $petugasService)
    {
    }

    /**
     * Tampilkan daftar petugas dengan search, filter status, sort, dan pagination.
     */
    public function index(Request $request): View
    {
        $pencarian = trim((string) $request->query('cari', ''));
        $statusFilter = $request->query('status', 'semua');
        $sortBy = in_array($request->query('sort'), self::SORTABLE_COLUMNS, true)
            ? $request->query('sort')
            : 'created_at';
        $sortDirection = $request->query('arah') === 'asc' ? 'asc' : 'desc';

        $petugas = Petugas::query()
            ->when($pencarian !== '', function ($query) use ($pencarian) {
                $query->where(function ($q) use ($pencarian) {
                    $q->where('nama_petugas', 'like', "%{$pencarian}%")
                        ->orWhere('email', 'like', "%{$pencarian}%");
                });
            })
            ->when($statusFilter === 'aktif', fn ($query) => $query->where('is_active', true))
            ->when($statusFilter === 'nonaktif', fn ($query) => $query->where('is_active', false))
            ->orderBy($sortBy, $sortDirection)
            ->paginate(10)
            ->withQueryString();

        $statistik = [
            'total' => Petugas::query()->count(),
            'aktif' => Petugas::query()->where('is_active', true)->count(),
            'nonaktif' => Petugas::query()->where('is_active', false)->count(),
            'total_aktivitas' => StatusHistory::query()->whereNotNull('petugas_id')->count()
                + ReservasiNote::query()->whereNotNull('petugas_id')->count(),
        ];

        return view('dashboard.admin.petugas.index', [
            'petugas' => $petugas,
            'statistik' => $statistik,
            'pencarian' => $pencarian,
            'statusFilter' => $statusFilter,
            'sortBy' => $sortBy,
            'sortDirection' => $sortDirection,
        ]);
    }

    public function create(): View
    {
        return view('dashboard.admin.petugas.create');
    }

    public function store(StorePetugasRequest $request): RedirectResponse
    {
        $petugas = $this->petugasService->buat($request->validated());

        return redirect()
            ->route('admin.pengguna.index')
            ->with('success', "Petugas \"{$petugas->nama_petugas}\" berhasil ditambahkan.");
    }

    /**
     * Tampilkan detail petugas beserta ringkasan aktivitas terbaru.
     */
    public function show(Petugas $pengguna): View
    {
        $pengguna->loadCount(['notes', 'statusHistories']);

        $catatanTerbaru = $pengguna->notes()
            ->with('reservasi:id,kode_reservasi,nomor_antrean')
            ->latest()
            ->limit(5)
            ->get();

        return view('dashboard.admin.petugas.show', [
            'petugas' => $pengguna,
            'catatanTerbaru' => $catatanTerbaru,
        ]);
    }

    public function edit(Petugas $pengguna): View
    {
        return view('dashboard.admin.petugas.edit', ['petugas' => $pengguna]);
    }

    public function update(UpdatePetugasRequest $request, Petugas $pengguna): RedirectResponse
    {
        $this->petugasService->perbarui($pengguna, $request->validated());

        return redirect()
            ->route('admin.pengguna.index')
            ->with('success', "Petugas \"{$pengguna->nama_petugas}\" berhasil diperbarui.");
    }

    public function destroy(Petugas $pengguna): RedirectResponse
    {
        $hasil = $this->petugasService->hapus($pengguna);

        return redirect()
            ->route('admin.pengguna.index')
            ->with($hasil['berhasil'] ? 'success' : 'error', $hasil['message']);
    }

    public function toggleStatus(Petugas $pengguna): RedirectResponse
    {
        $pengguna = $this->petugasService->toggleStatus($pengguna);

        $status = $pengguna->is_active ? 'diaktifkan' : 'dinonaktifkan';

        return redirect()
            ->route('admin.pengguna.index')
            ->with('success', "Petugas berhasil {$status}.");
    }
}