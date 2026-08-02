<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
}