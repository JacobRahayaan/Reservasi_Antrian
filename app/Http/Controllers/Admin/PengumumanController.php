<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePengumumanRequest;
use App\Http\Requests\UpdatePengumumanRequest;
use App\Models\Pengumuman;
use App\Services\PengumumanService;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PengumumanController extends Controller
{
    private const SORTABLE_COLUMNS = [
        'judul',
        'tanggal_mulai',
        'is_active',
        'created_at',
    ];

    public function __construct(private readonly PengumumanService $pengumumanService)
    {
    }

    /**
     * Tampilkan daftar pengumuman dengan search, filter status (dihitung
     * langsung di query, bukan difilter setelah fetch), sort, dan pagination.
     */
    public function index(Request $request): View
    {
        $pencarian = trim((string) $request->query('cari', ''));
        $statusFilter = $request->query('status', 'semua');
        $sortBy = in_array($request->query('sort'), self::SORTABLE_COLUMNS, true)
            ? $request->query('sort')
            : 'tanggal_mulai';
        $sortDirection = $request->query('arah') === 'asc' ? 'asc' : 'desc';

        $pengumumans = Pengumuman::query()
            ->when($pencarian !== '', fn (Builder $query) => $query->where('judul', 'like', "%{$pencarian}%"))
            ->when($statusFilter !== 'semua', fn (Builder $query) => $this->terapkanFilterStatus($query, $statusFilter))
            ->orderBy($sortBy, $sortDirection)
            ->paginate(10)
            ->withQueryString();

        $statistik = [
            'total' => Pengumuman::query()->count(),
            'aktif' => $this->terapkanFilterStatus(Pengumuman::query(), 'aktif')->count(),
            'terjadwal' => $this->terapkanFilterStatus(Pengumuman::query(), 'terjadwal')->count(),
            'nonaktif' => Pengumuman::query()->where('is_active', false)->count(),
        ];

        return view('dashboard.admin.pengumuman.index', [
            'pengumumans' => $pengumumans,
            'statistik' => $statistik,
            'pencarian' => $pencarian,
            'statusFilter' => $statusFilter,
            'sortBy' => $sortBy,
            'sortDirection' => $sortDirection,
        ]);
    }

    public function create(): View
    {
        return view('dashboard.admin.pengumuman.create');
    }

    public function store(StorePengumumanRequest $request): RedirectResponse
    {
        $pengumuman = $this->pengumumanService->buat($request->validated());

        return redirect()
            ->route('admin.pengumuman.index')
            ->with('success', "Pengumuman \"{$pengumuman->judul}\" berhasil ditambahkan.");
    }

    public function show(Pengumuman $pengumuman): View
    {
        return view('dashboard.admin.pengumuman.show', compact('pengumuman'));
    }

    public function edit(Pengumuman $pengumuman): View
    {
        return view('dashboard.admin.pengumuman.edit', compact('pengumuman'));
    }

    public function update(UpdatePengumumanRequest $request, Pengumuman $pengumuman): RedirectResponse
    {
        $this->pengumumanService->perbarui($pengumuman, $request->validated());

        return redirect()
            ->route('admin.pengumuman.index')
            ->with('success', "Pengumuman \"{$pengumuman->judul}\" berhasil diperbarui.");
    }

    public function destroy(Pengumuman $pengumuman): RedirectResponse
    {
        $judul = $pengumuman->judul;

        $this->pengumumanService->hapus($pengumuman);

        return redirect()
            ->route('admin.pengumuman.index')
            ->with('success', "Pengumuman \"{$judul}\" berhasil dihapus.");
    }

    public function toggleStatus(Pengumuman $pengumuman): RedirectResponse
    {
        $pengumuman = $this->pengumumanService->toggleStatus($pengumuman);

        $status = $pengumuman->is_active ? 'diaktifkan' : 'dinonaktifkan';

        return redirect()
            ->route('admin.pengumuman.index')
            ->with('success', "Pengumuman berhasil {$status}.");
    }

    /**
     * Terapkan kondisi SQL yang merepresentasikan status tampilan komputasi
     * (lihat Pengumuman::statusTampilan()) langsung pada query, agar filter
     * dan pagination tetap akurat tanpa perlu memuat seluruh data ke memori.
     */
    private function terapkanFilterStatus(Builder $query, string $status): Builder
    {
        $hariIni = Carbon::today()->toDateString();

        return match ($status) {
            'aktif' => $query
                ->where('is_active', true)
                ->whereDate('tanggal_mulai', '<=', $hariIni)
                ->where(fn (Builder $q) => $q->whereNull('tanggal_selesai')->orWhereDate('tanggal_selesai', '>=', $hariIni)),
            'terjadwal' => $query
                ->where('is_active', true)
                ->whereDate('tanggal_mulai', '>', $hariIni),
            'berakhir' => $query
                ->where('is_active', true)
                ->whereNotNull('tanggal_selesai')
                ->whereDate('tanggal_selesai', '<', $hariIni),
            'nonaktif' => $query->where('is_active', false),
            default => $query,
        };
    }
}