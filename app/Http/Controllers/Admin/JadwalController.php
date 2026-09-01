<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreJadwalRequest;
use App\Http\Requests\UpdateJadwalRequest;
use App\Models\Jadwal;
use App\Models\Layanan;
use App\Services\JadwalService;
use App\Http\Requests\StoreJadwalBerulangRequest;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class JadwalController extends Controller
{
    private const SORTABLE_COLUMNS = [
        'tanggal',
        'jam_mulai',
        'kuota_maksimal',
        'kuota_terpakai',
        'is_active',
    ];

    public function __construct(private readonly JadwalService $jadwalService)
    {
    }

    /**
     * Tampilkan daftar jadwal dengan search, filter layanan, filter
     * tanggal (mulai dari tanggal terpilih), sort, dan pagination.
     */
    public function index(Request $request): View
    {
        $pencarian = trim((string) $request->query('cari', ''));
        $layananFilter = $request->query('layanan_id', '');
        $tanggalFilter = $request->filled('tanggal')
            ? Carbon::parse($request->query('tanggal'))->startOfDay()
            : Carbon::today();

        $sortBy = in_array($request->query('sort'), self::SORTABLE_COLUMNS, true)
            ? $request->query('sort')
            : 'tanggal';
        $sortDirection = $request->query('arah') === 'desc' ? 'desc' : 'asc';

        $jadwals = Jadwal::query()
            ->with('layanan:id,nama_layanan,kode_layanan')
            ->whereDate('tanggal', '>=', $tanggalFilter)
            ->when($pencarian !== '', function ($query) use ($pencarian) {
                $query->whereHas('layanan', fn ($q) => $q->where('nama_layanan', 'like', "%{$pencarian}%"));
            })
            ->when($layananFilter !== '', fn ($query) => $query->where('layanan_id', $layananFilter))
            ->orderBy('tanggal')
            ->orderBy($sortBy, $sortDirection)
            ->paginate(15)
            ->withQueryString();

        $statistik = [
            'total_tanggal' => Jadwal::query()->distinct('tanggal')->count('tanggal'),
            'total_slot' => Jadwal::query()->count(),
            'total_kuota' => (int) Jadwal::query()->sum('kuota_maksimal'),
            'kuota_tersedia_hari_ini' => (int) Jadwal::query()
                ->whereDate('tanggal', today())
                ->where('is_active', true)
                ->get()
                ->sum(fn (Jadwal $jadwal) => $jadwal->sisaKuota()),
        ];

        $layanans = Layanan::query()->orderBy('nama_layanan')->get(['id', 'nama_layanan']);

        return view('dashboard.admin.jadwal.index', [
            'jadwals' => $jadwals,
            'statistik' => $statistik,
            'layanans' => $layanans,
            'pencarian' => $pencarian,
            'layananFilter' => $layananFilter,
            'tanggalFilter' => $tanggalFilter,
            'sortBy' => $sortBy,
            'sortDirection' => $sortDirection,
        ]);
    }

    /**
     * Tampilkan form tambah jadwal.
     */
    public function create(): View
    {
        $layanans = Layanan::query()->where('is_active', true)->orderBy('nama_layanan')->get();

        return view('dashboard.admin.jadwal.create', compact('layanans'));
    }

    /**
     * Simpan jadwal baru.
     */
    public function store(StoreJadwalRequest $request): RedirectResponse
    {
        $jadwal = $this->jadwalService->buat($request->validated());

        return redirect()
            ->route('admin.jadwal.index')
            ->with('success', "Jadwal untuk {$jadwal->layanan->nama_layanan} pada {$jadwal->tanggal->translatedFormat('d F Y')} berhasil ditambahkan.");
    }

    /**
     * Simpan banyak jadwal sekaligus (generate otomatis) dari rentang
     * tanggal, hari tertentu, dan jam operasional yang dipecah per
     * interval. Alternatif dari store() untuk kasus jadwal berulang
     * (mis. Senin-Jumat, 08:00-16:00, per 1 jam).
     */
    public function storeBerulang(StoreJadwalBerulangRequest $request): RedirectResponse
    {
        $hasil = $this->jadwalService->buatBerulang($request->validated());

        $pesan = "{$hasil['dibuat']} jadwal berhasil dibuat.";
        if ($hasil['dilewati'] > 0) {
            $pesan .= " {$hasil['dilewati']} slot dilewati karena sudah ada jadwal yang sama.";
        }

        return redirect()
            ->route('admin.jadwal.index')
            ->with('success', $pesan);
    }

    /**
     * Tampilkan detail jadwal beserta reservasi yang memakai slot ini.
     */
    public function show(Jadwal $jadwal): View
    {
        $jadwal->load('layanan:id,nama_layanan,kode_layanan');

        $reservasiTerkait = $jadwal->reservasis()
            ->latest()
            ->limit(10)
            ->get(['id', 'kode_reservasi', 'nomor_antrean', 'nama', 'status']);

        return view('dashboard.admin.jadwal.show', compact('jadwal', 'reservasiTerkait'));
    }

    /**
     * Tampilkan form ubah jadwal.
     */
    public function edit(Jadwal $jadwal): View
    {
        $layanans = Layanan::query()->where('is_active', true)->orderBy('nama_layanan')->get();

        return view('dashboard.admin.jadwal.edit', compact('jadwal', 'layanans'));
    }

    /**
     * Perbarui jadwal.
     */
    public function update(UpdateJadwalRequest $request, Jadwal $jadwal): RedirectResponse
    {
        $this->jadwalService->perbarui($jadwal, $request->validated());

        return redirect()
            ->route('admin.jadwal.index')
            ->with('success', 'Jadwal berhasil diperbarui.');
    }

    /**
     * Hapus jadwal permanen. Ditolak jika sudah memiliki reservasi.
     */
    public function destroy(Jadwal $jadwal): RedirectResponse
    {
        try {
            $this->jadwalService->hapus($jadwal);
        } catch (ValidationException $exception) {
            return Redirect::route('admin.jadwal.index')
                ->with('error', collect($exception->errors())->flatten()->first());
        }

        return redirect()
            ->route('admin.jadwal.index')
            ->with('success', 'Jadwal berhasil dihapus.');
    }

    /**
     * Aktifkan/nonaktifkan jadwal langsung dari daftar.
     */
    public function toggleStatus(Jadwal $jadwal): RedirectResponse
    {
        $jadwal = $this->jadwalService->toggleStatus($jadwal);

        $status = $jadwal->is_active ? 'diaktifkan' : 'dinonaktifkan';

        return redirect()
            ->route('admin.jadwal.index')
            ->with('success', "Jadwal berhasil {$status}.");
    }

    /**
     * Ekspor daftar jadwal (mengikuti filter yang sedang aktif) ke CSV.
     */
    public function export(Request $request): StreamedResponse
    {
        $pencarian = trim((string) $request->query('cari', ''));
        $layananFilter = $request->query('layanan_id', '');
        $tanggalFilter = $request->filled('tanggal')
            ? Carbon::parse($request->query('tanggal'))->startOfDay()
            : Carbon::today();

        $jadwals = Jadwal::query()
            ->with('layanan:id,nama_layanan')
            ->whereDate('tanggal', '>=', $tanggalFilter)
            ->when($pencarian !== '', function ($query) use ($pencarian) {
                $query->whereHas('layanan', fn ($q) => $q->where('nama_layanan', 'like', "%{$pencarian}%"));
            })
            ->when($layananFilter !== '', fn ($query) => $query->where('layanan_id', $layananFilter))
            ->orderBy('tanggal')
            ->orderBy('jam_mulai')
            ->get();

        $namaFile = 'jadwal-kuota-' . now()->format('Y-m-d-His') . '.csv';

        return response()->streamDownload(function () use ($jadwals) {
            $output = fopen('php://output', 'w');

            fputcsv($output, ['Tanggal', 'Layanan', 'Jam Mulai', 'Jam Selesai', 'Kuota Maksimal', 'Kuota Terisi', 'Sisa Kuota', 'Status']);

            foreach ($jadwals as $jadwal) {
                fputcsv($output, [
                    $jadwal->tanggal->toDateString(),
                    $jadwal->layanan->nama_layanan,
                    $jadwal->jam_mulai,
                    $jadwal->jam_selesai,
                    $jadwal->kuota_maksimal,
                    $jadwal->kuota_terpakai,
                    $jadwal->sisaKuota(),
                    $jadwal->is_active ? 'Aktif' : 'Nonaktif',
                ]);
            }

            fclose($output);
        }, $namaFile, ['Content-Type' => 'text/csv']);
    }
}