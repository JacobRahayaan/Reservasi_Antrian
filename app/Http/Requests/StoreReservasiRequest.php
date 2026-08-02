<?php

namespace App\Http\Requests;

use App\Models\Jadwal;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class StoreReservasiRequest extends FormRequest
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
            'nama' => ['required', 'string', 'min:3', 'max:100'],
            'nomor_hp' => ['required', 'string', 'regex:/^(\+62|62|0)8[1-9][0-9]{6,10}$/'],
            'email' => ['nullable', 'email', 'max:150'],
            'layanan_id' => ['required', 'integer', 'exists:layanans,id'],
            'tanggal' => ['required', 'date_format:Y-m-d', 'after_or_equal:today'],
            'jadwal_id' => ['required', 'integer', 'exists:jadwals,id'],
            'keluhan' => ['required', 'string', 'min:10', 'max:1000'],
            'dokumen' => ['nullable', 'array', 'max:3'],
            'dokumen.*' => ['file', 'mimes:jpg,jpeg,png,pdf', 'max:2048'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'nama.required' => 'Nama lengkap wajib diisi.',
            'nama.min' => 'Nama lengkap minimal 3 karakter.',
            'nomor_hp.required' => 'Nomor HP wajib diisi.',
            'nomor_hp.regex' => 'Format nomor HP tidak valid.',
            'email.email' => 'Format email tidak valid.',
            'layanan_id.required' => 'Jenis layanan wajib dipilih.',
            'layanan_id.exists' => 'Jenis layanan yang dipilih tidak valid.',
            'tanggal.required' => 'Tanggal reservasi wajib diisi.',
            'tanggal.after_or_equal' => 'Tanggal reservasi tidak boleh sebelum hari ini.',
            'jadwal_id.required' => 'Jam kedatangan wajib dipilih.',
            'jadwal_id.exists' => 'Jam kedatangan yang dipilih tidak valid.',
            'keluhan.required' => 'Keluhan/keterangan wajib diisi.',
            'keluhan.min' => 'Keluhan/keterangan minimal 10 karakter.',
            'dokumen.max' => 'Maksimal 3 dokumen dapat diunggah.',
            'dokumen.*.mimes' => 'Dokumen harus berformat JPG, PNG, atau PDF.',
            'dokumen.*.max' => 'Ukuran setiap dokumen maksimal 2MB.',
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator) {
            $jadwalId = $this->input('jadwal_id');
            $layananId = $this->input('layanan_id');
            $tanggal = $this->input('tanggal');

            if (! $jadwalId || ! $layananId || ! $tanggal) {
                return;
            }

            $jadwal = Jadwal::query()->find($jadwalId);

            if (! $jadwal) {
                return;
            }

            if ((int) $jadwal->layanan_id !== (int) $layananId) {
                $validator->errors()->add('jadwal_id', 'Jadwal tidak sesuai dengan jenis layanan yang dipilih.');
            }

            if ($jadwal->tanggal->toDateString() !== $tanggal) {
                $validator->errors()->add('jadwal_id', 'Jadwal tidak sesuai dengan tanggal yang dipilih.');
            }

            if ($jadwal->kuota_terpakai >= $jadwal->kuota_maksimal) {
                $validator->errors()->add('jadwal_id', 'Slot waktu yang dipilih sudah penuh, silakan pilih jadwal lain.');
            }
        });
    }
}