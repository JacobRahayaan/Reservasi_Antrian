<?php

namespace App\Http\Requests;

use App\Models\Reservasi;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class BatalkanReservasiRequest extends FormRequest
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
            'alasan' => ['nullable', 'string', 'max:255'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'nomor_hp_konfirmasi.required' => 'Konfirmasi nomor HP wajib diisi.',
            'alasan.max' => 'Alasan pembatalan maksimal 255 karakter.',
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator) {
            /** @var Reservasi $reservasi */
            $reservasi = $this->route('reservasi');

            if (! $reservasi->status->bisaDibatalkanOlehPelanggan()) {
                $validator->errors()->add(
                    'nomor_hp_konfirmasi',
                    "Reservasi ini sudah tidak dapat dibatalkan karena statusnya sudah \"{$reservasi->status->label()}\"."
                );

                return;
            }

            $nomorHpInput = preg_replace('/\D/', '', (string) $this->input('nomor_hp_konfirmasi'));
            $nomorHpTersimpan = preg_replace('/\D/', '', (string) $reservasi->nomor_hp);

            if ($nomorHpInput === '' || $nomorHpInput !== $nomorHpTersimpan) {
                $validator->errors()->add('nomor_hp_konfirmasi', 'Nomor HP tidak sesuai dengan data reservasi ini.');
            }
        });
    }
}