<?php

namespace App\Http\Requests;

use App\Models\Petugas;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProfilCsRequest extends FormRequest
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
        $petugas = Petugas::aktifSaatIni();

        return [
            'nama_petugas' => ['required', 'string', 'min:3', 'max:100'],
            'email' => ['required', 'email', 'max:150', Rule::unique('petugas', 'email')->ignore($petugas->id)],
            'no_hp' => ['nullable', 'string', 'max:20'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'nama_petugas.required' => 'Nama wajib diisi.',
            'nama_petugas.min' => 'Nama minimal 3 karakter.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email ini sudah digunakan petugas lain.',
        ];
    }
}