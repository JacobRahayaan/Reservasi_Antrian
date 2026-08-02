<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Number;

class DokumenReservasi extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'reservasi_id',
        'nama_file_asli',
        'nama_file_sistem',
        'path_file',
        'mime_type',
        'ukuran_file',
    ];

    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
        ];
    }

    public function reservasi(): BelongsTo
    {
        return $this->belongsTo(Reservasi::class);
    }

    /**
     * Ukuran file dalam format terbaca manusia, mis. "1.2 MB".
     */
    protected function ukuranFileFormat(): Attribute
    {
        return Attribute::make(
            get: fn () => Number::fileSize($this->ukuran_file, precision: 1),
        );
    }
}