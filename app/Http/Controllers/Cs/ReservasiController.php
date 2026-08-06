<?php

namespace App\Http\Controllers\Cs;

use App\Enums\ReservasiStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCatatanRequest;
use App\Http\Requests\UpdateStatusReservasiRequest;
use App\Models\Layanan;
use App\Models\Petugas;
use App\Models\Reservasi;
use App\Services\ReservasiQueryService;
use App\Services\ReservasiStatusService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ReservasiController extends Controller
{
    private const STATUS_AKTIF = ['menunggu_review', 'perlu_datang'];
    private const STATUS_RIWAYAT = ['selesai_online', 'selesai', 'dibatalkan'];

    public function __construct(
        private readonly ReservasiStatusService $statusService,
        private readonly ReservasiQueryService $queryService,
    ) {
    }

    /**
     * Tampilkan halaman Reservasi Customer Service dengan dua tab:
     * Reservasi Aktif (Menunggu Review, Perlu Datang) dan Riwayat Reservasi
     * (Selesai Online, Selesai, Dibatalkan).
     */
    public function index(Request $request): View
    {
        $tab = $request->query('tab') === 'riwayat' ? 'riwayat' : 'aktif';
        $statusGrup = $tab === 'riwayat' ? self::STATUS_RIWAYAT : self::STATUS_AKTIF;

        $filters = $this->ambilFilter($request);

        $reservasis = $this->queryService->queryDasar($filters, $statusGrup)
            ->paginate(10)
            ->withQueryString();

        $statistik = $this->queryService->hitungStatistik($statusGrup, $filters, $statusGrup);

        $layanans = Layanan::query()->orderBy('nama_layanan')->get(['id', 'nama_layanan']);

        $opsiStatus = collect($statusGrup)
            ->mapWithKeys(fn (string $value) => [$value => ReservasiStatus::from($value)->label()]);

        return view('dashboard.cs.reservasi.index', [
            'reservasis' => $reservasis,
            'statistik' => $statistik,
            'layanans' => $layanans,
            'opsiStatus' => $opsiStatus,
            'tab' => $tab,
            'filters' => $filters,
        ]);
    }

    /**
     * Tampilkan halaman Detail Reservasi Customer Service.
     */
    public function show(Reservasi $reservasi): View
    {
        $reservasi->load([
            'layanan:id,nama_layanan,kode_layanan',
            'jadwal:id,tanggal,jam_mulai,jam_selesai',
            'dokumen:id,reservasi_id,nama_file_asli,mime_type,ukuran_file,created_at',
            'statusHistories' => fn ($query) => $query->oldest('changed_at')->with('petugas:id,nama_petugas'),
            'notes' => fn ($query) => $query->latest()->with('petugas:id,nama_petugas'),
        ]);

        return view('dashboard.cs.reservasi.show', compact('reservasi'));
    }

    /**
     * Ubah status reservasi. Petugas yang bertindak disimulasikan lewat
     * Petugas::aktifSaatIni() karena modul Login belum dibangun.
     */
    public function updateStatus(UpdateStatusReservasiRequest $request, Reservasi $reservasi): RedirectResponse
    {
        $statusBaru = ReservasiStatus::from($request->validated('status'));

        $this->statusService->ubahStatus(
            $reservasi,
            $statusBaru,
            $request->validated('keterangan'),
            Petugas::aktifSaatIni()
        );

        return redirect()
            ->route('cs.reservasi.show', $reservasi)
            ->with('success', "Status reservasi berhasil diubah menjadi \"{$statusBaru->label()}\".");
    }

    /**
     * Tambahkan catatan Customer Service baru pada reservasi.
     */
    public function storeCatatan(StoreCatatanRequest $request, Reservasi $reservasi): RedirectResponse
    {
        $this->statusService->tambahCatatan(
            $reservasi,
            $request->validated('isi_catatan'),
            Petugas::aktifSaatIni()
        );

        return redirect()
            ->route('cs.reservasi.show', $reservasi)
            ->with('success', 'Catatan berhasil disimpan.');
    }

    /**
     * Ekspor daftar reservasi (mengikuti tab & filter yang sedang aktif) ke CSV.
     */
    public function export(Request $request): StreamedResponse
    {
        $tab = $request->query('tab') === 'riwayat' ? 'riwayat' : 'aktif';
        $statusGrup = $tab === 'riwayat' ? self::STATUS_RIWAYAT : self::STATUS_AKTIF;

        $filters = $this->ambilFilter($request);

        $reservasis = $this->queryService->queryDasar($filters, $statusGrup)->get();

        $namaFile = 'reservasi-' . $tab . '-' . now()->format('Y-m-d-His') . '.csv';

        return response()->streamDownload(function () use ($reservasis) {
            $output = fopen('php://output', 'w');

            fputcsv($output, ['No. Antrean', 'Kode Reservasi', 'Nama', 'Nomor HP', 'Layanan', 'Tanggal', 'Jam', 'Status', 'Dibuat Pada']);

            foreach ($reservasis as $reservasi) {
                fputcsv($output, [
                    $reservasi->nomor_antrean,
                    $reservasi->kode_reservasi,
                    $reservasi->nama,
                    $reservasi->nomor_hp,
                    $reservasi->layanan->nama_layanan,
                    $reservasi->jadwal->tanggal->toDateString(),
                    substr($reservasi->jadwal->jam_mulai, 0, 5) . ' - ' . substr($reservasi->jadwal->jam_selesai, 0, 5),
                    $reservasi->status->label(),
                    $reservasi->created_at->format('Y-m-d H:i'),
                ]);
            }

            fclose($output);
        }, $namaFile, ['Content-Type' => 'text/csv']);
    }

    /**
     * @return array<string, string>
     */
    private function ambilFilter(Request $request): array
    {
        return [
            'cari' => trim((string) $request->query('cari', '')),
            'layanan_id' => $request->query('layanan_id', ''),
            'status' => $request->query('status', ''),
            'tanggal_mulai' => $request->query('tanggal_mulai', ''),
            'tanggal_akhir' => $request->query('tanggal_akhir', ''),
            'urutan' => $request->query('urutan') === 'terlama' ? 'terlama' : 'terbaru',
        ];
    }
}