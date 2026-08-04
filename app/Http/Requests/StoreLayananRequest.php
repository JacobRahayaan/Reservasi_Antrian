<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreLayananRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string,mixed>
     */
    public function rules(): array
    {
        return [

            'nama_layanan' => [
                'required',
                'string',
                'min:3',
                'max:100',
                Rule::unique('layanans', 'nama_layanan'),
            ],

            'kode_layanan' => [
                'required',
                'string',
                'max:10',
                'alpha',
                Rule::unique('layanans', 'kode_layanan'),
            ],

            'deskripsi' => [
                'nullable',
                'string',
                'max:255',
            ],

            'estimasi_menit_min' => [
                'nullable',
                'integer',
                'min:1',
                'max:1440',
            ],

            'estimasi_menit_max' => [
                'nullable',
                'integer',
                'min:1',
                'max:1440',
                'gte:estimasi_menit_min',
            ],

            'is_active' => [
                'required',
                'boolean',
            ],

        ];
    }

    public function messages(): array
    {
        return [

            'nama_layanan.required' => 'Nama layanan wajib diisi.',
            'nama_layanan.min' => 'Nama layanan minimal 3 karakter.',
            'nama_layanan.unique' => 'Nama layanan sudah digunakan.',

            'kode_layanan.required' => 'Prefix nomor antrean wajib diisi.',
            'kode_layanan.alpha' => 'Prefix hanya boleh huruf.',
            'kode_layanan.unique' => 'Prefix sudah digunakan.',

            'estimasi_menit_max.gte' => 'Estimasi maksimal harus lebih besar atau sama dengan estimasi minimal.',

            'is_active.required' => 'Status wajib dipilih.',

        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'kode_layanan' => strtoupper(
                (string) $this->input('kode_layanan')
            ),
        ]);
    }
}