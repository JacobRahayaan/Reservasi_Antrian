<?php

namespace App\Services;

use App\Enums\ReservasiStatus;
use App\Models\DokumenReservasi;
use App\Models\Jadwal;
use App\Models\Layanan;
use App\Models\NomorAntreanCounter;
use App\Models\Reservasi;
use App\Models\ReservasiNote;
use App\Models\StatusHistory;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class ReservasiService
{
    /**
     * Buat reservasi baru beserta seluruh proses turunannya: validasi kuota,
     * generate kode reservasi, generate nomor antrean, simpan riwayat status
     * awal, simpan catatan awal, dan simpan dokumen pendukung.
     *
     * @param  array<string, mixed>  $data
     * @param  array<int, UploadedFile>  $dokumen
     */
    public function buat(array $data, array $dokumen = []): Reservasi
    {
        return DB::transaction(function () use ($data, $dokumen) {
            $layanan = Layanan::query()->findOrFail($data['layanan_id']);

            $jadwal = Jadwal::query()
                ->where('id', $data['jadwal_id'])
                ->lockForUpdate()
                ->firstOrFail();

            if ($jadwal->kuota_terpakai >= $jadwal->kuota_maksimal) {
                throw ValidationException::withMessages([
                    'jadwal_id' => 'Slot waktu yang dipilih sudah penuh, silakan pilih jadwal lain.',
                ]);
            }

            $nomorAntrean = $this->generateNomorAntrean($layanan, $jadwal->tanggal->toDateString());

            $reservasi = Reservasi::create([
                'kode_reservasi' => 'TEMP-' . Str::random(12),
                'nomor_antrean' => $nomorAntrean,
                'layanan_id' => $layanan->id,
                'jadwal_id' => $jadwal->id,
                'nama' => $data['nama'],
                'nomor_hp' => $data['nomor_hp'],
                'email' => $data['email'] ?? null,
                'keluhan' => $data['keluhan'],
                'status' => ReservasiStatus::MenungguReview,
            ]);

            $reservasi->update([
                'kode_reservasi' => sprintf('RSV%06d', $reservasi->id),
            ]);

            $jadwal->increment('kuota_terpakai');

            StatusHistory::create([
                'reservasi_id' => $reservasi->id,
                'petugas_id' => null,
                'status_sebelum' => null,
                'status_sesudah' => ReservasiStatus::MenungguReview,
                'keterangan' => 'Reservasi berhasil dibuat oleh pelanggan.',
                'changed_at' => now(),
            ]);

            ReservasiNote::create([
                'reservasi_id' => $reservasi->id,
                'petugas_id' => null,
                'isi_catatan' => 'Reservasi Anda telah diterima dan sedang menunggu review oleh Customer Service.',
            ]);

            foreach ($dokumen as $file) {
                $this->simpanDokumen($reservasi, $file);
            }

            return $reservasi->fresh(['layanan', 'jadwal', 'dokumen', 'statusHistories', 'notes']);
        });
    }

    /**
     * Hasilkan nomor antrean unik dengan format [KODE_LAYANAN][3 digit urutan],
     * mis. A001, B002. Urutan direset setiap hari per jenis layanan.
     */
    private function generateNomorAntrean(Layanan $layanan, string $tanggal): string
    {
        $counter = NomorAntreanCounter::query()
            ->where('layanan_id', $layanan->id)
            ->where('tanggal', $tanggal)
            ->lockForUpdate()
            ->first();

        if (! $counter) {
            $counter = NomorAntreanCounter::create([
                'layanan_id' => $layanan->id,
                'tanggal' => $tanggal,
                'urutan_terakhir' => 0,
            ]);
        }

        $counter->increment('urutan_terakhir');

        return $layanan->kode_layanan . str_pad((string) $counter->urutan_terakhir, 3, '0', STR_PAD_LEFT);
    }

    private function simpanDokumen(Reservasi $reservasi, UploadedFile $file): void
    {
        $namaFileSistem = Str::uuid() . '.' . $file->getClientOriginalExtension();

        $path = $file->storeAs(
            'reservasi/' . $reservasi->kode_reservasi,
            $namaFileSistem,
            'local'
        );

        DokumenReservasi::create([
            'reservasi_id' => $reservasi->id,
            'nama_file_asli' => $file->getClientOriginalName(),
            'nama_file_sistem' => $namaFileSistem,
            'path_file' => $path,
            'mime_type' => $file->getMimeType(),
            'ukuran_file' => $file->getSize(),
        ]);
    }
}