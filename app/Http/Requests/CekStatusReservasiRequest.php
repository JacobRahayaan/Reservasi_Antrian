<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CekStatusReservasiRequest extends FormRequest
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
            'nomor_antrean' => ['required', 'string', 'max:20'],
            'nomor_hp' => ['required', 'string', 'regex:/^(\+62|62|0)8[1-9][0-9]{6,10}$/'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'nomor_antrean.required' => 'Nomor antrean wajib diisi.',
            'nomor_hp.required' => 'Nomor HP wajib diisi.',
            'nomor_hp.regex' => 'Format nomor HP tidak valid.',
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'nomor_antrean' => strtoupper(trim((string) $this->input('nomor_antrean'))),
        ]);
    }
}