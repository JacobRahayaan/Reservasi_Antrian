<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PanggilanAntrean extends Model
{
    protected $fillable = [
        'reservasi_id',
        'kode_layanan',
        'nomor_urut',
        'status',
        'pesan_error',
        'diproses_pada',
        'selesai_pada',
    ];

    protected function casts(): array
    {
        return [
            'diproses_pada' => 'datetime',
            'selesai_pada' => 'datetime',
        ];
    }

    public function reservasi(): BelongsTo
    {
        return $this->belongsTo(Reservasi::class);
    }

    /**
     * Nama field yang dipakai mesin antrean fisik untuk endpoint /update,
     * mengikuti urutan A=var1, B=var2, C=var3 yang sudah dikonfirmasi
     * lewat inspeksi jaringan (DevTools) pada web control panel mesin.
     */
    public function namaFieldMesin(): string
    {
        return match ($this->kode_layanan) {
            'A' => 'var1',
            'B' => 'var2',
            'C' => 'var3',
            default => 'var1',
        };
    }
}