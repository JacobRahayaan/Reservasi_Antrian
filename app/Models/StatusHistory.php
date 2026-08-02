<?php

namespace App\Models;

use App\Enums\ReservasiStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StatusHistory extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'reservasi_id',
        'petugas_id',
        'status_sebelum',
        'status_sesudah',
        'keterangan',
        'changed_at',
    ];

    protected function casts(): array
    {
        return [
            'status_sebelum' => ReservasiStatus::class,
            'status_sesudah' => ReservasiStatus::class,
            'changed_at' => 'datetime',
        ];
    }

    public function reservasi(): BelongsTo
    {
        return $this->belongsTo(Reservasi::class);
    }
}