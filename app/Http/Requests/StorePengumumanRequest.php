<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePengumumanRequest extends FormRequest
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
            'judul' => ['required', 'string', 'min:5', 'max:150'],
            'isi' => ['required', 'string', 'min:10', 'max:2000'],
            'tanggal_mulai' => ['required', 'date_format:Y-m-d'],
            'tanggal_selesai' => ['nullable', 'date_format:Y-m-d', 'after_or_equal:tanggal_mulai'],
            'is_active' => ['required', 'boolean'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'judul.required' => 'Judul pengumuman wajib diisi.',
            'judul.min' => 'Judul pengumuman minimal 5 karakter.',
            'isi.required' => 'Isi pengumuman wajib diisi.',
            'isi.min' => 'Isi pengumuman minimal 10 karakter.',
            'tanggal_mulai.required' => 'Tanggal mulai tayang wajib diisi.',
            'tanggal_selesai.after_or_equal' => 'Tanggal selesai tidak boleh sebelum tanggal mulai.',
            'is_active.required' => 'Status pengumuman wajib dipilih.',
        ];
    }
}