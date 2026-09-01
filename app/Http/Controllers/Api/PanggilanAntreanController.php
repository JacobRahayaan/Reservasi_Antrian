<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PanggilanAntrean;
use App\Services\PanggilanAntreanService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PanggilanAntreanController extends Controller
{
    public function __construct(private readonly PanggilanAntreanService $panggilanService)
    {
    }

    /**
     * Dipanggil laptop jembatan lewat polling berkala. Mengembalikan job
     * yang masih pending, sekaligus langsung menguncinya (status jadi
     * "diproses") agar tidak diambil dua kali oleh polling yang tumpang
     * tindih.
     */
    public function pending(): JsonResponse
    {
        $jobs = $this->panggilanService->ambilJobPendingDanKunci();

        return response()->json([
            'success' => true,
            'message' => $jobs->isEmpty() ? 'Tidak ada job baru.' : "{$jobs->count()} job siap diproses.",
            'data' => $jobs->map(fn (PanggilanAntrean $job) => [
                'id' => $job->id,
                'kode_layanan' => $job->kode_layanan,
                'nomor_urut' => $job->nomor_urut,
                'field_mesin' => $job->namaFieldMesin(),
            ]),
        ]);
    }

    /**
     * Dipanggil laptop jembatan setelah berhasil mengirim perintah ke
     * mesin antrean fisik.
     */
    public function tandaiSelesai(PanggilanAntrean $panggilan): JsonResponse
    {
        $this->panggilanService->tandaiSelesai($panggilan);

        return response()->json([
            'success' => true,
            'message' => 'Job ditandai selesai.',
        ]);
    }

    /**
     * Dipanggil laptop jembatan kalau gagal mengirim perintah (mis. mesin
     * antrean tidak merespons, WiFi jembatan putus dari sisi mesin).
     */
    public function tandaiGagal(PanggilanAntrean $panggilan, Request $request): JsonResponse
    {
        $pesan = $request->input('pesan', 'Gagal diproses oleh laptop jembatan.');

        $this->panggilanService->tandaiGagal($panggilan, $pesan);

        return response()->json([
            'success' => true,
            'message' => 'Job ditandai gagal.',
        ]);
    }
}