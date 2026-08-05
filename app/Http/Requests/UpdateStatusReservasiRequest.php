<?php

namespace App\Http\Requests;

use App\Enums\ReservasiStatus;
use App\Models\Reservasi;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class UpdateStatusReservasiRequest extends FormRequest
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
            'status' => ['required', Rule::enum(ReservasiStatus::class)],
            'keterangan' => ['nullable', 'string', 'max:255'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'status.required' => 'Status baru wajib dipilih.',
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator) {
            /** @var Reservasi $reservasi */
            $reservasi = $this->route('reservasi');
            $statusInput = $this->input('status');

            if (! $statusInput) {
                return;
            }

            $statusBaru = ReservasiStatus::from($statusInput);

            if (! $reservasi->status->bisaBertransisiKe($statusBaru)) {
                $validator->errors()->add(
                    'status',
                    "Status tidak dapat diubah dari \"{$reservasi->status->label()}\" ke \"{$statusBaru->label()}\"."
                );
            }
        });
    }
}