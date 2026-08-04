<?php

namespace App\Http\Requests;

use App\Models\Jadwal;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class UpdateJadwalRequest extends FormRequest
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
            'layanan_id' => ['required', 'integer', 'exists:layanans,id'],
            'tanggal' => ['required', 'date_format:Y-m-d'],
            'jam_mulai' => ['required', 'date_format:H:i'],
            'jam_selesai' => ['required', 'date_format:H:i', 'after:jam_mulai'],
            'kuota_maksimal' => ['required', 'integer', 'min:1'],
            'is_active' => ['required', 'boolean'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'layanan_id.required' => 'Jenis layanan wajib dipilih.',
            'layanan_id.exists' => 'Jenis layanan yang dipilih tidak valid.',
            'tanggal.required' => 'Tanggal wajib diisi.',
            'jam_mulai.required' => 'Jam mulai wajib diisi.',
            'jam_selesai.required' => 'Jam selesai wajib diisi.',
            'jam_selesai.after' => 'Jam selesai harus lebih besar dari jam mulai.',
            'kuota_maksimal.required' => 'Kuota maksimum wajib diisi.',
            'kuota_maksimal.min' => 'Kuota maksimum minimal 1.',
            'is_active.required' => 'Status jadwal wajib dipilih.',
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator) {
            /** @var Jadwal $jadwal */
            $jadwal = $this->route('jadwal');

            $layananId = $this->input('layanan_id');
            $tanggal = $this->input('tanggal');
            $jamMulai = $this->input('jam_mulai');
            $kuotaMaksimal = $this->input('kuota_maksimal');

            if ($layananId && $tanggal && $jamMulai) {
                $duplikat = Jadwal::query()
                    ->where('layanan_id', $layananId)
                    ->whereDate('tanggal', $tanggal)
                    ->where('jam_mulai', $jamMulai)
                    ->where('id', '!=', $jadwal->id)
                    ->exists();

                if ($duplikat) {
                    $validator->errors()->add(
                        'jam_mulai',
                        'Sudah ada jadwal untuk layanan, tanggal, dan jam mulai yang sama.'
                    );
                }
            }

            if ($kuotaMaksimal !== null && (int) $kuotaMaksimal < $jadwal->kuota_terpakai) {
                $validator->errors()->add(
                    'kuota_maksimal',
                    "Kuota tidak boleh dikurangi hingga di bawah jumlah reservasi yang sudah ada ({$jadwal->kuota_terpakai})."
                );
            }
        });
    }
}