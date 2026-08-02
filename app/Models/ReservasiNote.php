<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReservasiNote extends Model
{
    protected $table = 'reservasi_notes';

    protected $fillable = [
        'reservasi_id',
        'petugas_id',
        'isi_catatan',
    ];

    public function reservasi(): BelongsTo
    {
        return $this->belongsTo(Reservasi::class);
    }
}