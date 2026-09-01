<?php

namespace App\Http\Controllers\Admin;

use App\Enums\ReservasiStatus;
use App\Http\Controllers\Controller;
use App\Models\Jadwal;
use App\Models\Layanan;
use App\Models\Reservasi;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Tampilkan halaman ringkasan Dashboard Admin.
     */
    public function index(Request $request): View
    {
        $tanggal = Carbon::today();

        if ($request->filled('tanggal')) {
            try {
                $tanggal = Carbon::parse($request->query('tanggal'))->startOfDay();
            } catch (\Throwable $e) {
                $tanggal = Carbon::today();
            }
        }

        $tanggalSebelumnya = $tanggal->copy()->subDay();

        $statistikHariIni = $this->hitungStatistikHarian($tanggal);
        $statistikKemarin = $this->hitungStatistikHarian($tanggalSebelumnya);

        $totalReservasi = Reservasi::count();

        $kartuStatistik = $this->susunKartuStatistik(
            $statistikHariIni,
            $statistikKemarin,
            $totalReservasi
        );

        $grafikMingguan = $this->grafikReservasi7HariTerakhir($tanggal);
        $totalGrafik = array_sum($grafikMingguan);

        $distribusiLayanan = $this->distribusiReservasiPerLayanan($tanggal);

        $totalDistribusiLayanan = array_sum(
            array_column($distribusiLayanan, 'jumlah')
        );

        $reservasiPerJam = $this->reservasiPerJamKedatangan($tanggal);

        $reservasiTerbaru = Reservasi::query()
            ->with([
                'layanan:id,nama_layanan,kode_layanan',
                'jadwal:id,tanggal,jam_mulai,jam_selesai',
            ])
            ->latest('created_at')
            ->limit(5)
            ->get([
                'id',
                'kode_reservasi',
                'nomor_antrean',
                'nama',
                'layanan_id',
                'jadwal_id',
                'status',
            ]);

        $ringkasanSistem = [
            'total_layanan' => Layanan::count(),
            'total_jadwal' => Jadwal::whereDate('tanggal', $tanggal)->count(),
            'total_pengguna' => '—',
            'pengumuman_aktif' => '—',
        ];

        $jumlahNotifikasi = Reservasi::query()
            ->where('status', ReservasiStatus::MenungguReview)
            ->whereHas('jadwal', function ($query) use ($tanggal) {
                $query->whereDate('tanggal', $tanggal);
            })
            ->count();

        return view('dashboard.admin.index', [
            'tanggal' => $tanggal,
            'kartuStatistik' => $kartuStatistik,
            'grafikMingguan' => $grafikMingguan,
            'totalGrafik' => $totalGrafik,
            'distribusiLayanan' => $distribusiLayanan,
            'totalDistribusiLayanan' => $totalDistribusiLayanan,
            'reservasiPerJam' => $reservasiPerJam,
            'reservasiTerbaru' => $reservasiTerbaru,
            'ringkasanSistem' => $ringkasanSistem,
            'jumlahNotifikasi' => $jumlahNotifikasi,
        ]);
    }

    /**
     * Hitung statistik harian.
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
     * Susun kartu statistik.
     */
    private function susunKartuStatistik(array $hariIni, array $kemarin, int $totalKeseluruhan): array
    {
        $buatKartu = function (
            string $label,
            string $key,
            string $icon,
            string $warna
        ) use ($hariIni, $kemarin) {

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
            [
                'label' => 'Total Reservasi',
                'nilai' => $totalKeseluruhan,
                'persentase' => null,
                'arah' => null,
                'icon' => 'ticket',
                'warna' => 'slate',
                'keterangan' => 'Sejak sistem berjalan',
            ],
            $buatKartu('Reservasi Hari Ini', 'total', 'calendar', 'blue'),
            $buatKartu('Menunggu Review', 'menunggu_review', 'clock', 'amber'),
            $buatKartu('Perlu Datang', 'perlu_datang', 'walking', 'orange'),
            $buatKartu('Selesai Online', 'selesai_online', 'check', 'green'),
            $buatKartu('Selesai', 'selesai', 'check-circle', 'purple'),
            $buatKartu('Dibatalkan', 'dibatalkan', 'x-mark', 'gray'),
        ];
    }

    /**
     * Grafik reservasi 7 hari terakhir.
     */
    private function grafikReservasi7HariTerakhir(Carbon $tanggal): array
    {
        $mulai = $tanggal->copy()->subDays(6)->startOfDay();
        $akhir = $tanggal->copy()->endOfDay();

        $counts = Reservasi::query()
            ->whereBetween('created_at', [$mulai, $akhir])
            ->selectRaw('DATE(created_at) as tanggal, COUNT(*) as total')
            ->groupBy('tanggal')
            ->pluck('total', 'tanggal');

        $data = [];

        for ($i = 0; $i < 7; $i++) {
            $hari = $mulai->copy()->addDays($i);

            $data[$hari->translatedFormat('d M')] =
                (int) ($counts[$hari->toDateString()] ?? 0);
        }

        return $data;
    }

    /**
     * Distribusi layanan.
     */
    private function distribusiReservasiPerLayanan(Carbon $tanggal): array
    {
        return Layanan::query()
            ->withCount([
                'reservasis as jumlah_reservasi' => function ($query) use ($tanggal) {
                    $query->whereHas('jadwal', function ($q) use ($tanggal) {
                        $q->whereDate('tanggal', $tanggal);
                    });
                },
            ])
            ->orderByDesc('jumlah_reservasi')
            ->get()
            ->map(function (Layanan $layanan) {
                return [
                    'label' => $layanan->nama_layanan,
                    'jumlah' => (int) $layanan->jumlah_reservasi,
                ];
            })
            ->all();
    }

    /**
     * Reservasi per jam.
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
            $label = Carbon::parse($jamMulai)->format('H:i');

            $data[$label] = (int) $total;
        }

        return $data;
    }
}