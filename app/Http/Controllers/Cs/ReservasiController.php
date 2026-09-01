<?php

namespace App\Http\Controllers\Cs;

use App\Enums\ReservasiStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCatatanRequest;
use App\Http\Requests\UpdateStatusReservasiRequest;
use App\Models\Layanan;
use App\Models\Reservasi;
use App\Services\PanggilanAntreanService;
use App\Services\ReservasiQueryService;
use App\Services\ReservasiStatusService;
use App\Services\SinkronisasiFisikService;
use App\Enums\StatusSinkronFisik; 
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ReservasiController extends Controller
{
    private const STATUS_AKTIF = ['menunggu_review', 'perlu_datang'];
    private const STATUS_RIWAYAT = ['selesai_online', 'selesai', 'dibatalkan'];

    public function __construct(
        private readonly ReservasiStatusService $statusService,
        private readonly ReservasiQueryService $queryService,
        private readonly PanggilanAntreanService $panggilanService,
        private readonly SinkronisasiFisikService $sinkronisasiFisikService,
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
     * Tampilkan daftar reservasi berstatus Perlu Datang yang nomor
     * antreannya belum tersinkron/dicetak di mesin fisik. Halaman ini
     * terpisah dari Daftar Reservasi biasa agar CS dapat fokus
     * menuntaskan pekerjaan "cetak fisik" tanpa terganggu tab/filter lain.
     */
    public function belumDicetakFisik(Request $request): View
    {
        $filters = $this->ambilFilter($request);

        $reservasis = $this->queryService
            ->queryDasar($filters, [ReservasiStatus::PerluDatang->value], StatusSinkronFisik::BelumDisinkronkan)
            ->paginate(10)
            ->withQueryString();

        $total = $reservasis->total();

        return view('dashboard.cs.reservasi.belum-dicetak', [
            'reservasis' => $reservasis,
            'filters' => $filters,
            'total' => $total,
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
            'panggilanAntreans' => fn ($query) => $query->latest()->limit(5),
        ]);

        return view('dashboard.cs.reservasi.show', compact('reservasi'));
    }

    /**
     * Ubah status reservasi. Petugas yang bertindak diambil dari sesi
     * autentikasi guard `petugas` yang sesungguhnya — tidak lagi memakai
     * simulasi Petugas::aktifSaatIni() sejak modul Login aktif.
     */
    public function updateStatus(UpdateStatusReservasiRequest $request, Reservasi $reservasi): RedirectResponse
    {
        $statusBaru = ReservasiStatus::from($request->validated('status'));

        $this->statusService->ubahStatus(
            $reservasi,
            $statusBaru,
            $request->validated('keterangan'),
            Auth::guard('petugas')->user()
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
            Auth::guard('petugas')->user()
        );

        return redirect()
            ->route('cs.reservasi.show', $reservasi)
            ->with('success', 'Catatan berhasil disimpan.');
    }

    /**
     * Buat job "Panggil ke Loket" untuk reservasi ini. Job disimpan ke
     * tabel panggilan_antreans dan akan diambil oleh laptop jembatan
     * (Node.js, berjalan di jaringan lokal kantor) lewat polling, lalu
     * diteruskan sebagai perintah ke mesin antrean fisik. Proses fisiknya
     * asinkron — tombol ini hanya menitipkan perintah, bukan menunggu
     * hasilnya secara langsung.
     */
	public function panggilKeLoket(Reservasi $reservasi): RedirectResponse
	{
		$panggilan = $this->panggilanService->buatPanggilan($reservasi);

		if ($panggilan->status === 'selesai') {
			return redirect()
				->route('cs.reservasi.show', $reservasi)
				->with('success', "Berhasil! Nomor {$reservasi->nomor_antrean} sudah dipanggil di mesin antrean.");
		}

		if ($panggilan->status === 'gagal') {
			return redirect()
				->route('cs.reservasi.show', $reservasi)
				->with('error', "Gagal memanggil ke mesin antrean: {$panggilan->pesan_error}");
		}

		return redirect()
			->route('cs.reservasi.show', $reservasi)
			->with('success', "Perintah panggil nomor {$reservasi->nomor_antrean} sudah dikirim, menunggu diproses.");
	}

    /**
     * Tandai manual bahwa nomor antrean reservasi ini sudah dikoordinasikan
     * secara fisik (petugas sudah memastikan tiket sudah tercetak di mesin,
     * biasanya lewat komunikasi langsung dengan security di loket).
     */
    public function tandaiSinkronFisik(Reservasi $reservasi): RedirectResponse
    {
        $this->sinkronisasiFisikService->tandaiSudahDicetak($reservasi, Auth::guard('petugas')->user());

        return redirect()
            ->route('cs.reservasi.show', $reservasi)
            ->with('success', "Nomor {$reservasi->nomor_antrean} ditandai sudah tersinkron dengan mesin fisik.");
    }

    /**
     * Coba deteksi otomatis via SinkronisasiCounterMesinService. Hanya
     * berhasil kalau server ini sedang tersambung ke jaringan WiFi mesin
     * antrean (mode testing lokal) — di produksi (server di internet),
     * tombol ini akan selalu gagal dan CS diarahkan memakai "Tandai Sudah
     * Dicetak" secara manual.
     */
    public function cekSinkronOtomatis(Reservasi $reservasi): RedirectResponse
    {
        $berhasil = $this->sinkronisasiFisikService->cobaSinkronOtomatis($reservasi);

        if ($berhasil) {
            return redirect()
                ->route('cs.reservasi.show', $reservasi)
                ->with('success', "Terdeteksi otomatis! Nomor {$reservasi->nomor_antrean} sudah tercetak di mesin fisik.");
        }

        return redirect()
            ->route('cs.reservasi.show', $reservasi)
            ->with('error', 'Belum terdeteksi otomatis (mesin tidak terjangkau, atau nomor belum tercetak fisik). Silakan koordinasi manual dengan security lalu klik "Tandai Sudah Dicetak".');
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