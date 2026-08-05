<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCatatanRequest extends FormRequest
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
            'isi_catatan' => ['required', 'string', 'min:3', 'max:1000'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'isi_catatan.required' => 'Catatan tidak boleh kosong.',
            'isi_catatan.min' => 'Catatan minimal 3 karakter.',
            'isi_catatan.max' => 'Catatan maksimal 1000 karakter.',
        ];
    }
}