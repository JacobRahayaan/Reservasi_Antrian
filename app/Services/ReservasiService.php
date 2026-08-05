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
     * Buat reservasi baru beserta seluruh proses turunannya: validasi kuota
     * dan status aktif jadwal, generate kode reservasi, generate nomor
     * antrean, simpan riwayat status awal, simpan catatan awal, dan simpan
     * dokumen pendukung.
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

            if (! $jadwal->is_active) {
                throw ValidationException::withMessages([
                    'jadwal_id' => 'Jadwal yang dipilih sudah tidak tersedia, silakan pilih jadwal lain.',
                ]);
            }

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
     * Ubah jadwal (tanggal/jam) reservasi milik pelanggan. Kuota slot lama
     * dikembalikan, kuota slot baru dikurangi, dan nomor antrean diterbitkan
     * ulang jika tanggal berubah (nomor antrean terikat ke tanggal+layanan).
     * Dibungkus transaction + row lock untuk mencegah race condition dan
     * menjaga konsistensi kuota antara slot lama dan slot baru.
     */
    public function ubahJadwal(Reservasi $reservasi, Jadwal $jadwalBaru): Reservasi
    {
        return DB::transaction(function () use ($reservasi, $jadwalBaru) {
            $reservasiTerkunci = Reservasi::query()->lockForUpdate()->findOrFail($reservasi->id);

            if (! $reservasiTerkunci->status->bisaDiubahJadwalOlehPelanggan()) {
                throw ValidationException::withMessages([
                    'jadwal_id' => "Reservasi ini sudah tidak dapat diubah jadwalnya karena statusnya sudah \"{$reservasiTerkunci->status->label()}\".",
                ]);
            }

            $jadwalLama = Jadwal::query()->lockForUpdate()->findOrFail($reservasiTerkunci->jadwal_id);
            $jadwalBaruTerkunci = Jadwal::query()->lockForUpdate()->findOrFail($jadwalBaru->id);

            if ((int) $jadwalBaruTerkunci->id === (int) $jadwalLama->id) {
                throw ValidationException::withMessages([
                    'jadwal_id' => 'Jadwal baru harus berbeda dari jadwal saat ini.',
                ]);
            }

            if ((int) $jadwalBaruTerkunci->layanan_id !== (int) $reservasiTerkunci->layanan_id) {
                throw ValidationException::withMessages([
                    'jadwal_id' => 'Jadwal yang dipilih tidak sesuai dengan jenis layanan reservasi ini.',
                ]);
            }

            if (! $jadwalBaruTerkunci->is_active || $jadwalBaruTerkunci->kuota_terpakai >= $jadwalBaruTerkunci->kuota_maksimal) {
                throw ValidationException::withMessages([
                    'jadwal_id' => 'Slot waktu yang dipilih sudah tidak tersedia, silakan pilih jadwal lain.',
                ]);
            }

            $tanggalBerubah = ! $jadwalLama->tanggal->isSameDay($jadwalBaruTerkunci->tanggal);

            $jadwalLama->decrement('kuota_terpakai');
            $jadwalBaruTerkunci->increment('kuota_terpakai');

            $nomorAntreanLama = $reservasiTerkunci->nomor_antrean;
            $dataUpdate = ['jadwal_id' => $jadwalBaruTerkunci->id];

            if ($tanggalBerubah) {
                $layanan = Layanan::query()->findOrFail($reservasiTerkunci->layanan_id);

                $dataUpdate['nomor_antrean'] = $this->generateNomorAntrean(
                    $layanan,
                    $jadwalBaruTerkunci->tanggal->toDateString()
                );
            }

            $reservasiTerkunci->update($dataUpdate);

            $keteranganNomor = $tanggalBerubah
                ? " Nomor antrean berubah dari {$nomorAntreanLama} menjadi {$dataUpdate['nomor_antrean']}."
                : '';

            ReservasiNote::create([
                'reservasi_id' => $reservasiTerkunci->id,
                'petugas_id' => null,
                'isi_catatan' => sprintf(
                    'Pelanggan mengubah jadwal dari %s (%s-%s) menjadi %s (%s-%s).%s',
                    $jadwalLama->tanggal->translatedFormat('d F Y'),
                    substr($jadwalLama->jam_mulai, 0, 5),
                    substr($jadwalLama->jam_selesai, 0, 5),
                    $jadwalBaruTerkunci->tanggal->translatedFormat('d F Y'),
                    substr($jadwalBaruTerkunci->jam_mulai, 0, 5),
                    substr($jadwalBaruTerkunci->jam_selesai, 0, 5),
                    $keteranganNomor
                ),
            ]);

            return $reservasiTerkunci->fresh(['layanan', 'jadwal']);
        });
    }

    /**
     * Batalkan reservasi milik pelanggan. Mengembalikan kuota slot terkait
     * (BR-09), mencatat riwayat status, dan menambahkan catatan otomatis.
     * Dibungkus transaction + row lock untuk konsistensi kuota.
     */
    public function batalkan(Reservasi $reservasi, ?string $alasan): Reservasi
    {
        return DB::transaction(function () use ($reservasi, $alasan) {
            $reservasiTerkunci = Reservasi::query()->lockForUpdate()->findOrFail($reservasi->id);

            if (! $reservasiTerkunci->status->bisaDibatalkanOlehPelanggan()) {
                throw ValidationException::withMessages([
                    'nomor_hp_konfirmasi' => "Reservasi ini sudah tidak dapat dibatalkan karena statusnya sudah \"{$reservasiTerkunci->status->label()}\".",
                ]);
            }

            $statusSebelum = $reservasiTerkunci->status;

            $jadwal = Jadwal::query()->lockForUpdate()->findOrFail($reservasiTerkunci->jadwal_id);
            $jadwal->decrement('kuota_terpakai');

            $reservasiTerkunci->update(['status' => ReservasiStatus::Dibatalkan]);

            $keteranganAlasan = $alasan ? " Alasan: {$alasan}" : '';

            StatusHistory::create([
                'reservasi_id' => $reservasiTerkunci->id,
                'petugas_id' => null,
                'status_sebelum' => $statusSebelum,
                'status_sesudah' => ReservasiStatus::Dibatalkan,
                'keterangan' => "Dibatalkan oleh pelanggan.{$keteranganAlasan}",
                'changed_at' => now(),
            ]);

            ReservasiNote::create([
                'reservasi_id' => $reservasiTerkunci->id,
                'petugas_id' => null,
                'isi_catatan' => "Reservasi ini telah dibatalkan oleh pelanggan.{$keteranganAlasan}",
            ]);

            return $reservasiTerkunci->fresh();
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