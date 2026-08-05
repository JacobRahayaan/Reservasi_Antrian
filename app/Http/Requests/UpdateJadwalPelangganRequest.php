<?php

namespace App\Http\Requests;

use App\Models\Jadwal;
use App\Models\Reservasi;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class UpdateJadwalPelangganRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'nomor_hp_konfirmasi' => ['required', 'string'],
            'tanggal' => ['required', 'date_format:Y-m-d', 'after_or_equal:today'],
            'jadwal_id' => ['required', 'integer', 'exists:jadwals,id'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'nomor_hp_konfirmasi.required' => 'Konfirmasi nomor HP wajib diisi.',
            'tanggal.required' => 'Tanggal baru wajib dipilih.',
            'tanggal.after_or_equal' => 'Tanggal baru tidak boleh sebelum hari ini.',
            'jadwal_id.required' => 'Jam baru wajib dipilih.',
            'jadwal_id.exists' => 'Jadwal yang dipilih tidak valid.',
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator) {
            /** @var Reservasi $reservasi */
            $reservasi = $this->route('reservasi');

            if (! $reservasi->status->bisaDiubahJadwalOlehPelanggan()) {
                $validator->errors()->add(
                    'jadwal_id',
                    "Reservasi ini sudah tidak dapat diubah jadwalnya karena statusnya sudah \"{$reservasi->status->label()}\"."
                );

                return;
            }

            $nomorHpInput = preg_replace('/\D/', '', (string) $this->input('nomor_hp_konfirmasi'));
            $nomorHpTersimpan = preg_replace('/\D/', '', (string) $reservasi->nomor_hp);

            if ($nomorHpInput === '' || $nomorHpInput !== $nomorHpTersimpan) {
                $validator->errors()->add('nomor_hp_konfirmasi', 'Nomor HP tidak sesuai dengan data reservasi ini.');

                return;
            }

            $jadwalId = $this->input('jadwal_id');
            $tanggal = $this->input('tanggal');

            if (! $jadwalId || ! $tanggal) {
                return;
            }

            $jadwal = Jadwal::query()->find($jadwalId);

            if (! $jadwal) {
                return;
            }

            if ((int) $jadwal->id === (int) $reservasi->jadwal_id) {
                $validator->errors()->add('jadwal_id', 'Jadwal baru harus berbeda dari jadwal saat ini.');
            }

            if ((int) $jadwal->layanan_id !== (int) $reservasi->layanan_id) {
                $validator->errors()->add('jadwal_id', 'Jadwal tidak sesuai dengan jenis layanan reservasi ini.');
            }

            if ($jadwal->tanggal->toDateString() !== $tanggal) {
                $validator->errors()->add('jadwal_id', 'Jadwal tidak sesuai dengan tanggal yang dipilih.');
            }

            if (! $jadwal->is_active || $jadwal->kuota_terpakai >= $jadwal->kuota_maksimal) {
                $validator->errors()->add('jadwal_id', 'Slot waktu yang dipilih sudah tidak tersedia, silakan pilih jadwal lain.');
            }
        });
    }
}