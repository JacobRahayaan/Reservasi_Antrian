<?php

namespace App\Http\Controllers\Cs;

use App\Enums\ReservasiStatus;
use App\Http\Controllers\Controller;
use App\Models\Reservasi;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class NotifikasiController extends Controller
{
    /**
     * Dipanggil berkala (polling) oleh browser CS untuk mendeteksi
     * reservasi baru yang masuk sejak ID terakhir yang sudah dilihat.
     * Kalau parameter `sejak_id` tidak dikirim (permintaan pertama saat
     * halaman dimuat), tidak ada notifikasi yang dikirim — hanya
     * menetapkan baseline ID terbaru, supaya reservasi lama yang memang
     * sudah ada tidak ikut "dibunyikan" seolah baru masuk.
     */
    public function cekReservasiBaru(Request $request): JsonResponse
    {
        $sejakId = $request->query('sejak_id');

        $idTerbaruSaatIni = Reservasi::query()->max('id') ?? 0;

        $reservasiBaru = collect();

        if ($sejakId !== null) {
            $reservasiBaru = Reservasi::query()
                ->where('id', '>', (int) $sejakId)
                ->with(['layanan:id,nama_layanan,kode_layanan'])
                ->latest('id')
                ->limit(10)
                ->get(['id', 'kode_reservasi', 'nomor_antrean', 'nama', 'layanan_id', 'created_at']);
        }

        $totalMenungguReview = Reservasi::query()
            ->where('status', ReservasiStatus::MenungguReview)
            ->count();

        return response()->json([
            'success' => true,
            'message' => $reservasiBaru->isEmpty() ? 'Tidak ada reservasi baru.' : "{$reservasiBaru->count()} reservasi baru ditemukan.",
            'data' => [
                'id_terakhir' => $idTerbaruSaatIni,
                'total_menunggu_review' => $totalMenungguReview,
                'reservasi_baru' => $reservasiBaru->map(fn (Reservasi $r) => [
                    'id' => $r->id,
                    'kode_reservasi' => $r->kode_reservasi,
                    'nomor_antrean' => $r->nomor_antrean,
                    'nama' => $r->nama,
                    'layanan' => $r->layanan->nama_layanan,
                    'dibuat_pada' => $r->created_at->translatedFormat('H:i'),
                    'url_detail' => route('cs.reservasi.show', $r),
                ]),
            ],
        ]);
    }
}