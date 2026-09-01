<?php

namespace App\Services;

use App\Models\PanggilanAntrean;
use App\Models\Reservasi;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\ValidationException;

class PanggilanAntreanService
{
    /**
     * Buat job panggilan baru untuk sebuah reservasi. Nomor urut dipecah
     * otomatis dari kolom nomor_antrean (format [kode_layanan][3 digit],
     * mis. "A012") yang sudah konsisten dipakai sejak Sprint 2.
     *
     * Perilakunya bercabang sesuai config `services.mesin_antrean.mode`:
     * - "langsung": Laravel mengirim HTTP request langsung ke mesin
     *   antrean (dipakai saat testing lokal — server & mesin antrean
     *   berada di jaringan WiFi yang sama).
     * - "jembatan" (default, untuk produksi setelah deploy ke internet):
     *   job disimpan sebagai pending, menunggu diambil laptop jembatan
     *   lewat polling API.
     */
    public function buatPanggilan(Reservasi $reservasi): PanggilanAntrean
    {
        ['kode_layanan' => $kodeLayanan, 'nomor_urut' => $nomorUrut] = $this->pecahNomorAntrean($reservasi->nomor_antrean);

        $panggilan = PanggilanAntrean::create([
            'reservasi_id' => $reservasi->id,
            'kode_layanan' => $kodeLayanan,
            'nomor_urut' => $nomorUrut,
            'status' => 'pending',
        ]);

        if (config('services.mesin_antrean.mode') === 'langsung') {
            $this->prosesLangsung($panggilan);
        }

        return $panggilan->fresh();
    }

/**
 * Kirim perintah panggil ke mesin antrean fisik, dengan cabang logika
 * berdasarkan hasil pengujian sistematis:
 * - Jika nomor tujuan == Total Antrian saat ini: panggil /call langsung,
 *   TANPA /update (nomor terbaru tercetak sudah otomatis jadi "current").
 * - Jika nomor tujuan < Total Antrian: WAJIB /update dulu untuk memundurkan
 *   "current number" mesin, baru /call.
 * - Jika nomor tujuan > Total Antrian: ditolak, karena tiket fisiknya
 *   belum pernah tercetak sama sekali.
 */
private function prosesLangsung(PanggilanAntrean $panggilan): void
{
    $config = config('services.mesin_antrean');
    $sinkronisasi = app(SinkronisasiCounterMesinService::class);

    $totalAntreanSaatIni = $sinkronisasi->ambilTotalAntreanSaatIni($panggilan->kode_layanan);

    if ($totalAntreanSaatIni === null) {
        $this->tandaiGagal(
            $panggilan,
            'Tidak dapat membaca Total Antrian dari mesin. Pastikan laptop ini sudah tersambung ke WiFi mesin antrean (192.168.4.x).'
        );
        return;
    }

    if ($panggilan->nomor_urut > $totalAntreanSaatIni) {
        $this->tandaiGagal(
            $panggilan,
            "Nomor {$panggilan->kode_layanan}{$panggilan->nomor_urut} belum tercetak secara fisik (Total Antrian {$panggilan->kode_layanan} baru {$totalAntreanSaatIni}). Pelanggan perlu mengambil tiket fisik terlebih dahulu."
        );
        return;
    }

    $endpointCall = match ($panggilan->kode_layanan) {
        'A' => ['path' => '/call', 'field' => 'call', 'nilai' => 'Call A'],
        'B' => ['path' => '/call2', 'field' => 'call2', 'nilai' => 'Call B'],
        'C' => ['path' => '/call3', 'field' => 'call3', 'nilai' => 'Call C'],
        default => throw new \InvalidArgumentException("Kode layanan tidak dikenali: {$panggilan->kode_layanan}"),
    };

    try {
        $clientMesin = Http::withBasicAuth($config['username'], $config['password'])
            ->asForm()
            ->timeout(8);

        // Hanya kirim /update kalau nomor tujuan BUKAN nomor terbaru yang
        // tercetak — sesuai temuan: kalau nomor tujuan == Total Antrian,
        // /update justru tidak diperlukan (dan pada beberapa kondisi
        // pengujian, tidak berfungsi untuk kasus itu).
        if ($panggilan->nomor_urut < $totalAntreanSaatIni) {
            $responseUpdate = $clientMesin->post($config['url'] . '/update', [
                $panggilan->namaFieldMesin() => $panggilan->nomor_urut,
            ]);

            if (! $responseUpdate->successful() && $responseUpdate->status() !== 303) {
                $this->tandaiGagal($panggilan, "Gagal pada langkah /update, status HTTP {$responseUpdate->status()}.");
                return;
            }
        }

        $responseCall = $clientMesin->post($config['url'] . $endpointCall['path'], [
            $endpointCall['field'] => $endpointCall['nilai'],
        ]);

        if ($responseCall->successful() || $responseCall->status() === 303) {
            $this->tandaiSelesai($panggilan);
        } else {
            $this->tandaiGagal($panggilan, "Gagal pada langkah {$endpointCall['path']}, status HTTP {$responseCall->status()}.");
        }
    } catch (\Illuminate\Http\Client\ConnectionException $e) {
        $this->tandaiGagal(
            $panggilan,
            'Tidak dapat terhubung ke mesin antrean. Pastikan laptop ini sudah tersambung ke WiFi mesin antrean (192.168.4.x).'
        );
    } catch (\Throwable $e) {
        $this->tandaiGagal($panggilan, 'Kesalahan tak terduga: ' . $e->getMessage());
    }
}

    /**
     * Pecah format nomor_antrean ("A012") menjadi kode layanan ("A") dan
     * nomor urut murni tanpa leading zero (12) — sesuai contoh payload
     * asli mesin antrean (var1=12), bukan var1=012.
     *
     * @return array{kode_layanan: string, nomor_urut: int}
     */
    private function pecahNomorAntrean(string $nomorAntrean): array
    {
        if (! preg_match('/^([A-Za-z]+)(\d+)$/', $nomorAntrean, $cocok)) {
            throw ValidationException::withMessages([
                'nomor_antrean' => "Format nomor antrean \"{$nomorAntrean}\" tidak dikenali untuk dipanggil ke mesin antrean fisik.",
            ]);
        }

        return [
            'kode_layanan' => strtoupper($cocok[1]),
            'nomor_urut' => (int) $cocok[2],
        ];
    }

    /**
     * Ambil job yang masih pending (dipanggil laptop jembatan lewat
     * polling), lalu langsung tandai sebagai 'diproses' dalam operasi yang
     * sama agar tidak ada job yang terambil dua kali oleh polling yang
     * tumpang tindih. Hanya relevan pada mode "jembatan".
     */
    public function ambilJobPendingDanKunci(int $batas = 5): \Illuminate\Support\Collection
    {
        return \Illuminate\Support\Facades\DB::transaction(function () use ($batas) {
            $jobs = PanggilanAntrean::query()
                ->where('status', 'pending')
                ->oldest('created_at')
                ->limit($batas)
                ->lockForUpdate()
                ->get();

            if ($jobs->isEmpty()) {
                return $jobs;
            }

            PanggilanAntrean::query()
                ->whereIn('id', $jobs->pluck('id'))
                ->update([
                    'status' => 'diproses',
                    'diproses_pada' => now(),
                ]);

            return $jobs->fresh();
        });
    }

    public function tandaiSelesai(PanggilanAntrean $panggilan): void
    {
        $panggilan->update([
            'status' => 'selesai',
            'selesai_pada' => now(),
        ]);
    }

    public function tandaiGagal(PanggilanAntrean $panggilan, string $pesanError): void
    {
        $panggilan->update([
            'status' => 'gagal',
            'pesan_error' => $pesanError,
            'selesai_pada' => now(),
        ]);
    }
}