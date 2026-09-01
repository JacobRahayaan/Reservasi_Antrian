<?php

namespace App\Http\Controllers\Cs;

use App\Enums\ReservasiStatus;
use App\Http\Controllers\Controller;
use App\Models\Layanan;
use App\Models\Reservasi;
use App\Services\SinkronisasiFisikService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    private const TAB_VALID = ['semua', 'menunggu_review', 'perlu_datang', 'selesai_online'];

    public function __construct(private readonly SinkronisasiFisikService $sinkronisasiFisikService)
    {
    }

    /**
     * Tampilkan halaman ringkasan Dashboard Customer Service.
     *
     * Seluruh statistik dihitung berdasarkan tanggal jadwal kedatangan
     * (bukan tanggal pembuatan reservasi), konsisten dengan makna
     * "hari ini" pada konteks operasional kantor pelayanan.
     */
    public function index(Request $request): View
    {
        $tanggal = $request->filled('tanggal')
            ? Carbon::parse($request->query('tanggal'))->startOfDay()
            : Carbon::today();

        $tanggalSebelumnya = $tanggal->copy()->subDay();

        $statistikHariIni = $this->hitungStatistikHarian($tanggal);
        $statistikKemarin = $this->hitungStatistikHarian($tanggalSebelumnya);

        $kartuStatistik = $this->susunKartuStatistik($statistikHariIni, $statistikKemarin);

        $distribusiLayanan = $this->distribusiReservasiPerLayanan($tanggal);
        $reservasiPerJam = $this->reservasiPerJamKedatangan($tanggal);

        $tab = in_array($request->query('tab'), self::TAB_VALID, true)
            ? $request->query('tab')
            : 'semua';

        $reservasiTerbaru = Reservasi::query()
            ->with([
                'layanan:id,nama_layanan,kode_layanan',
                'jadwal:id,tanggal,jam_mulai,jam_selesai',
            ])
            ->whereHas('jadwal', fn ($query) => $query->whereDate('tanggal', $tanggal))
            ->when($tab !== 'semua', fn ($query) => $query->where('status', $tab))
            ->latest()
            ->limit(5)
            ->get(['id', 'kode_reservasi', 'nomor_antrean', 'nama', 'layanan_id', 'jadwal_id', 'status']);

        $jumlahNotifikasi = Reservasi::query()
            ->whereHas('jadwal', fn ($query) => $query->whereDate('tanggal', $tanggal))
            ->where('status', ReservasiStatus::MenungguReview)
            ->count();

        $daftarBelumSinkron = $this->sinkronisasiFisikService->daftarBelumSinkron();
        $jumlahBelumSinkron = $this->sinkronisasiFisikService->hitungBelumSinkron();

        return view('dashboard.cs.index', [
            'tanggal' => $tanggal,
            'kartuStatistik' => $kartuStatistik,
            'distribusiLayanan' => $distribusiLayanan,
            'totalDistribusiLayanan' => array_sum(array_column($distribusiLayanan, 'jumlah')),
            'reservasiPerJam' => $reservasiPerJam,
            'reservasiTerbaru' => $reservasiTerbaru,
            'tab' => $tab,
            'jumlahNotifikasi' => $jumlahNotifikasi,
            'statistikHariIni' => $statistikHariIni,
            'daftarBelumSinkron' => $daftarBelumSinkron,
            'jumlahBelumSinkron' => $jumlahBelumSinkron,
        ]);
    }

    /**
     * Hitung jumlah reservasi per status untuk satu tanggal jadwal
     * kedatangan. Memakai JOIN (bukan whereHas berulang) agar seluruh
     * agregasi status selesai dalam satu query saja.
     *
     * @return array<string, int>
     */
    private function hitungStatistikHarian(Carbon $tanggal): array
    {
        $counts = Reservasi::query()
            ->join('jadwals', 'jadwals.id', '=', 'reservasis.jadwal_id')
            ->whereDate('jadwals.tanggal', $tanggal)
            ->selectRaw('reservasis.status as status, COUNT(*) as total')
            ->groupBy('reservasis.status')
            ->pluck('total', 'status');

        return [
            'total' => (int) $counts->sum(),
            'menunggu_review' => (int) ($counts[ReservasiStatus::MenungguReview->value] ?? 0),
            'perlu_datang' => (int) ($counts[ReservasiStatus::PerluDatang->value] ?? 0),
            'selesai_online' => (int) ($counts[ReservasiStatus::SelesaiOnline->value] ?? 0),
            'selesai' => (int) ($counts[ReservasiStatus::Selesai->value] ?? 0),
            'dibatalkan' => (int) ($counts[ReservasiStatus::Dibatalkan->value] ?? 0),
        ];
    }

    /**
     * Susun 6 kartu statistik beserta tren persentase dibanding hari
     * sebelumnya, sesuai desain Dashboard Customer Service.
     *
     * @param  array<string, int>  $hariIni
     * @param  array<string, int>  $kemarin
     * @return array<int, array<string, mixed>>
     */
    private function susunKartuStatistik(array $hariIni, array $kemarin): array
    {
        $buatKartu = function (string $label, string $key, string $icon, string $warna) use ($hariIni, $kemarin) {
            $nilaiSekarang = $hariIni[$key];
            $nilaiSebelumnya = $kemarin[$key];

            $persentase = $nilaiSebelumnya > 0
                ? round((($nilaiSekarang - $nilaiSebelumnya) / $nilaiSebelumnya) * 100)
                : ($nilaiSekarang > 0 ? 100 : 0);

            return [
                'label' => $label,
                'nilai' => $nilaiSekarang,
                'persentase' => abs((int) $persentase),
                'arah' => $persentase >= 0 ? 'naik' : 'turun',
                'icon' => $icon,
                'warna' => $warna,
                'keterangan' => 'dari kemarin',
            ];
        };

        return [
            $buatKartu('Total Reservasi Hari Ini', 'total', 'calendar', 'blue'),
            $buatKartu('Menunggu Review', 'menunggu_review', 'clock', 'amber'),
            $buatKartu('Perlu Datang', 'perlu_datang', 'walking', 'orange'),
            $buatKartu('Selesai Online', 'selesai_online', 'check', 'green'),
            $buatKartu('Selesai', 'selesai', 'check-circle', 'purple'),
            $buatKartu('Dibatalkan', 'dibatalkan', 'x-mark', 'gray'),
        ];
    }

    /**
     * Distribusi jumlah reservasi per jenis layanan untuk satu tanggal
     * jadwal kedatangan, memakai satu query withCount tanpa N+1.
     *
     * @return array<int, array{label: string, jumlah: int}>
     */
    private function distribusiReservasiPerLayanan(Carbon $tanggal): array
    {
        return Layanan::query()
            ->withCount(['reservasis as jumlah_reservasi' => function ($query) use ($tanggal) {
                $query->whereHas('jadwal', fn ($q) => $q->whereDate('tanggal', $tanggal));
            }])
            ->orderByDesc('jumlah_reservasi')
            ->get()
            ->map(fn (Layanan $layanan) => [
                'label' => $layanan->nama_layanan,
                'jumlah' => (int) $layanan->jumlah_reservasi,
            ])
            ->all();
    }

    /**
     * Jumlah reservasi per jam kedatangan untuk satu tanggal, memakai JOIN
     * langsung ke tabel jadwals agar agregasi terjadi di database.
     *
     * @return array<string, int>
     */
    private function reservasiPerJamKedatangan(Carbon $tanggal): array
    {
        $counts = Reservasi::query()
            ->join('jadwals', 'jadwals.id', '=', 'reservasis.jadwal_id')
            ->whereDate('jadwals.tanggal', $tanggal)
            ->selectRaw('jadwals.jam_mulai as jam_mulai, COUNT(*) as total')
            ->groupBy('jadwals.jam_mulai')
            ->orderBy('jadwals.jam_mulai')
            ->pluck('total', 'jam_mulai');

        $data = [];

        foreach ($counts as $jamMulai => $total) {
            $data[substr($jamMulai, 0, 5)] = (int) $total;
        }

        return $data;
    }
}