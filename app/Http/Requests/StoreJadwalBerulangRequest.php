<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreJadwalBerulangRequest extends FormRequest
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
            'tanggal_mulai' => ['required', 'date_format:Y-m-d'],
            'tanggal_selesai' => ['required', 'date_format:Y-m-d', 'after_or_equal:tanggal_mulai'],
            'hari' => ['required', 'array', 'min:1'],
            'hari.*' => ['integer', 'between:0,6'],
            'jam_awal' => ['required', 'date_format:H:i'],
            'jam_akhir' => ['required', 'date_format:H:i', 'after:jam_awal'],
            'interval_menit' => ['required', 'integer', 'min:15'],
            'kuota_maksimal_berulang' => ['required', 'integer', 'min:1'],
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
            'tanggal_mulai.required' => 'Tanggal mulai wajib diisi.',
            'tanggal_selesai.required' => 'Tanggal selesai wajib diisi.',
            'tanggal_selesai.after_or_equal' => 'Tanggal selesai tidak boleh sebelum tanggal mulai.',
            'hari.required' => 'Pilih minimal satu hari.',
            'hari.min' => 'Pilih minimal satu hari.',
            'jam_awal.required' => 'Jam mulai operasional wajib diisi.',
            'jam_akhir.required' => 'Jam selesai operasional wajib diisi.',
            'jam_akhir.after' => 'Jam selesai operasional harus lebih besar dari jam mulai.',
            'interval_menit.required' => 'Interval per slot wajib diisi.',
            'interval_menit.min' => 'Interval minimal 15 menit.',
            'kuota_maksimal_berulang.required' => 'Kuota per slot wajib diisi.',
            'kuota_maksimal_berulang.min' => 'Kuota per slot minimal 1.',
            'is_active.required' => 'Status jadwal wajib dipilih.',
        ];
    }
}