<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreLayananRequest;
use App\Http\Requests\UpdateLayananRequest;
use App\Models\Layanan;
use App\Models\Reservasi;
use App\Services\LayananService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LayananController extends Controller
{
    /**
     * Kolom yang boleh di-sort.
     */
    private const SORTABLE_COLUMNS = [
        'nama_layanan',
        'kode_layanan',
        'is_active',
        'created_at',
    ];

    public function __construct(
        private readonly LayananService $layananService
    ) {
    }

    /**
     * Halaman daftar layanan.
     */
    public function index(Request $request): View
    {
        $pencarian = trim((string) $request->query('cari'));

        $statusFilter = $request->query('status', 'semua');

        $sortBy = in_array(
            $request->query('sort'),
            self::SORTABLE_COLUMNS,
            true
        )
            ? $request->query('sort')
            : 'created_at';

        $sortDirection = $request->query('arah') === 'asc'
            ? 'asc'
            : 'desc';

        $layanans = Layanan::query()

            ->withCount([
                'reservasis as reservasis_hari_ini_count' => function ($query) {
                    $query->whereHas('jadwal', function ($q) {
                        $q->whereDate('tanggal', today());
                    });
                },
            ])

            ->when(
                $pencarian !== '',
                function ($query) use ($pencarian) {

                    $query->where(function ($q) use ($pencarian) {

                        $q->where(
                            'nama_layanan',
                            'like',
                            "%{$pencarian}%"
                        )

                        ->orWhere(
                            'kode_layanan',
                            'like',
                            "%{$pencarian}%"
                        );
                    });
                }
            )

            ->when(
                $statusFilter === 'aktif',
                fn ($q) => $q->where('is_active', true)
            )

            ->when(
                $statusFilter === 'nonaktif',
                fn ($q) => $q->where('is_active', false)
            )

            ->orderBy($sortBy, $sortDirection)

            ->paginate(10)

            ->withQueryString();

        $statistik = [

            'total' => Layanan::count(),

            'aktif' => Layanan::where(
                'is_active',
                true
            )->count(),

            'nonaktif' => Layanan::where(
                'is_active',
                false
            )->count(),

            'digunakan_hari_ini' => Reservasi::whereHas(
                'jadwal',
                fn ($q) => $q->whereDate(
                    'tanggal',
                    today()
                )
            )->count(),
        ];

        return view(
            'dashboard.admin.layanan.index',
            compact(
                'layanans',
                'statistik',
                'pencarian',
                'statusFilter',
                'sortBy',
                'sortDirection'
            )
        );
    }

    /**
     * Form tambah layanan.
     */
    public function create(): View
    {
        return view('dashboard.admin.layanan.create');
    }

    /**
     * Simpan layanan baru.
     */
    public function store(
        StoreLayananRequest $request
    ): RedirectResponse
    {
        $layanan = $this->layananService->buat(
            $request->validated()
        );

        return redirect()

            ->route('admin.layanan.index')

            ->with(
                'success',
                "Layanan \"{$layanan->nama_layanan}\" berhasil ditambahkan."
            );
    }

    /**
     * Detail layanan.
     */
    public function show(
        Layanan $layanan
    ): View
    {
        $layanan->loadCount('reservasis');

        $reservasiTerbaru = $layanan
            ->reservasis()

            ->with([
                'jadwal:id,tanggal,jam_mulai,jam_selesai',
            ])

            ->latest('created_at')

            ->limit(5)

            ->get([
                'id',
                'kode_reservasi',
                'nomor_antrean',
                'nama',
                'jadwal_id',
                'status',
            ]);

        return view(
            'dashboard.admin.layanan.show',
            compact(
                'layanan',
                'reservasiTerbaru'
            )
        );
    }
	    /**
     * Form edit layanan.
     */
    public function edit(Layanan $layanan): View
    {
        return view(
            'dashboard.admin.layanan.edit',
            compact('layanan')
        );
    }

    /**
     * Update layanan.
     */
    public function update(
        UpdateLayananRequest $request,
        Layanan $layanan
    ): RedirectResponse
    {
        $layanan = $this->layananService->perbarui(
            $layanan,
            $request->validated()
        );

        return redirect()
            ->route('admin.layanan.index')
            ->with(
                'success',
                "Layanan \"{$layanan->nama_layanan}\" berhasil diperbarui."
            );
    }

    /**
     * Hapus layanan.
     *
     * Jika layanan pernah dipakai reservasi,
     * maka dilakukan soft delete.
     *
     * Jika belum pernah dipakai,
     * maka force delete.
     */
    public function destroy(
        Layanan $layanan
    ): RedirectResponse
    {
        $hasil = $this->layananService->hapus($layanan);

        return redirect()
            ->route('admin.layanan.index')
            ->with(
                'success',
                $hasil['message']
            );
    }

    /**
     * Toggle status aktif/nonaktif.
     */
    public function toggleStatus(
        Layanan $layanan
    ): RedirectResponse
    {
        $layanan = $this->layananService->toggleStatus($layanan);

        return redirect()
            ->route('admin.layanan.index')
            ->with(
                'success',
                sprintf(
                    'Layanan "%s" berhasil %s.',
                    $layanan->nama_layanan,
                    $layanan->is_active
                        ? 'diaktifkan'
                        : 'dinonaktifkan'
                )
            );
    }
}