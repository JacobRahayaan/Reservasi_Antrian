<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePengaturanSistemRequest extends FormRequest
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
            'nama_aplikasi' => ['required', 'string', 'min:3', 'max:100'],
            'nomor_contact_center' => ['required', 'string', 'max:20'],
            'email_contact_center' => ['nullable', 'email', 'max:150'],
            'alamat_kantor' => ['nullable', 'string', 'max:255'],
            'jam_buka_default' => ['required', 'date_format:H:i'],
            'jam_tutup_default' => ['required', 'date_format:H:i', 'after:jam_buka_default'],
            'maksimal_ukuran_dokumen_mb' => ['required', 'integer', 'min:1', 'max:20'],
            'maksimal_jumlah_dokumen' => ['required', 'integer', 'min:1', 'max:10'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'nama_aplikasi.required' => 'Nama aplikasi wajib diisi.',
            'nomor_contact_center.required' => 'Nomor contact center wajib diisi.',
            'email_contact_center.email' => 'Format email tidak valid.',
            'jam_buka_default.required' => 'Jam buka default wajib diisi.',
            'jam_tutup_default.required' => 'Jam tutup default wajib diisi.',
            'jam_tutup_default.after' => 'Jam tutup harus lebih besar dari jam buka.',
            'maksimal_ukuran_dokumen_mb.required' => 'Maksimal ukuran dokumen wajib diisi.',
            'maksimal_ukuran_dokumen_mb.min' => 'Maksimal ukuran dokumen minimal 1MB.',
            'maksimal_jumlah_dokumen.required' => 'Maksimal jumlah dokumen wajib diisi.',
            'maksimal_jumlah_dokumen.min' => 'Maksimal jumlah dokumen minimal 1.',
        ];
    }
}