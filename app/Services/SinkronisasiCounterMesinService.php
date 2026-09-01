<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SinkronisasiCounterMesinService
{
    /**
     * Baca "Total Antrian" saat ini langsung dari halaman status mesin
     * antrean fisik (http://192.168.4.1:8081), untuk dipakai sebagai basis
     * penomoran reservasi online — supaya nomor online tidak pernah lebih
     * kecil dari nomor yang sudah dicetak fisik hari itu.
     *
     * Mengembalikan null kalau mesin tidak terjangkau (mis. laptop belum
     * konek ke WiFi mesin, atau mode produksi belum pakai jembatan aktif)
     * — pemanggil wajib menangani null dengan fallback ke counter internal
     * (NomorAntreanCounter, Sprint 2) supaya sistem tetap jalan meski
     * mesin fisik sedang tidak terjangkau.
     */
    public function ambilTotalAntreanSaatIni(string $kodeLayanan): ?int
    {
        if (config('services.mesin_antrean.mode') !== 'langsung') {
            return null;
        }

        $config = config('services.mesin_antrean');

        try {
            $response = Http::withBasicAuth($config['username'], $config['password'])
                ->timeout(5)
                ->get($config['url'] . '/');

            if (! $response->successful()) {
                return null;
            }

            return $this->parseTotalAntreanDariHtml($response->body(), $kodeLayanan);
        } catch (\Throwable $e) {
            Log::warning('Gagal membaca counter mesin antrean: ' . $e->getMessage());

            return null;
        }
    }

    /**
     * Ekstrak angka "Total Antrian X : N" dari HTML halaman status mesin.
     * Pola pencarian mengikuti tampilan yang terlihat di web control panel
     * ("Total Antrian A : 14", dst untuk B dan C).
     */
    private function parseTotalAntreanDariHtml(string $html, string $kodeLayanan): ?int
    {
        $polaLayanan = match (strtoupper($kodeLayanan)) {
            'A' => 'A',
            'B' => 'B',
            'C' => 'C',
            default => null,
        };

        if (! $polaLayanan) {
            return null;
        }

        if (preg_match('/Total\s+Antrian\s+' . $polaLayanan . '\s*:\s*(\d+)/i', $html, $cocok)) {
            return (int) $cocok[1];
        }

        return null;
    }
}