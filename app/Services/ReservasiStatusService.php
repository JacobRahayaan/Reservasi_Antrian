<?php
namespace App\Services;
use App\Enums\ReservasiStatus;
use App\Enums\StatusSinkronFisik;
use App\Models\Petugas;
use App\Models\Reservasi;
use App\Models\ReservasiNote;
use App\Models\StatusHistory;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
class ReservasiStatusService
{
    /**
     * Ubah status reservasi. Memvalidasi ulang transisi di dalam transaction
     * dengan row lock (defense-in-depth selain validasi di Form Request),
     * memperbarui kolom status pada reservasis, dan mencatat satu baris baru
     * ke status_histories — tidak pernah menimpa baris riwayat yang sudah ada.
     */
    public function ubahStatus(Reservasi $reservasi, ReservasiStatus $statusBaru, ?string $keterangan, Petugas $petugas): Reservasi
    {
        return DB::transaction(function () use ($reservasi, $statusBaru, $keterangan, $petugas) {
            $reservasiTerkunci = Reservasi::query()->lockForUpdate()->findOrFail($reservasi->id);
            $statusSebelum = $reservasiTerkunci->status;
            if (! $statusSebelum->bisaBertransisiKe($statusBaru)) {
                throw ValidationException::withMessages([
                    'status' => "Status tidak dapat diubah dari \"{$statusSebelum->label()}\" ke \"{$statusBaru->label()}\".",
                ]);
            }

            $dataUpdate = ['status' => $statusBaru];

            // Setiap kali reservasi ditandai "Perlu Datang", pelanggan akan
            // datang fisik ke kantor dan butuh nomor antrean yang sesuai di
            // mesin fisik. Tandai otomatis "belum disinkronkan" supaya muncul
            // di widget notifikasi Dashboard CS sampai petugas mengonfirmasi
            // tiket fisiknya sudah tersedia.
            if ($statusBaru === ReservasiStatus::PerluDatang) {
                $dataUpdate['status_sinkron_fisik'] = StatusSinkronFisik::BelumDisinkronkan;
            }

            $reservasiTerkunci->update($dataUpdate);

            StatusHistory::create([
                'reservasi_id' => $reservasiTerkunci->id,
                'petugas_id' => $petugas->id,
                'status_sebelum' => $statusSebelum,
                'status_sesudah' => $statusBaru,
                'keterangan' => $keterangan,
                'changed_at' => now(),
            ]);
            return $reservasiTerkunci->fresh();
        });
    }
    /**
     * Tambahkan catatan Customer Service baru pada sebuah reservasi.
     * Tidak mengubah status maupun kuota — murni operasi tulis tunggal,
     * namun tetap dibungkus transaction untuk konsistensi pola di seluruh
     * aplikasi dan memudahkan penambahan side-effect di masa depan.
     */
    public function tambahCatatan(Reservasi $reservasi, string $isiCatatan, Petugas $petugas): ReservasiNote
    {
        return DB::transaction(function () use ($reservasi, $isiCatatan, $petugas) {
            return ReservasiNote::create([
                'reservasi_id' => $reservasi->id,
                'petugas_id' => $petugas->id,
                'isi_catatan' => $isiCatatan,
            ]);
        });
    }
}