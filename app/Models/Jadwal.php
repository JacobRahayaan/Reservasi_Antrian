<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Jadwal extends Model
{
    use HasFactory;

    protected $fillable = [
        'layanan_id',
        'tanggal',
        'jam_mulai',
        'jam_selesai',
        'kuota_maksimal',
        'kuota_terpakai',
    ];

    protected function casts(): array
    {
        return [
            'tanggal' => 'date',
        ];
    }

    public function layanan(): BelongsTo
    {
        return $this->belongsTo(Layanan::class);
    }

    public function reservasis(): HasMany
    {
        return $this->hasMany(Reservasi::class);
    }

    public function isTersedia(): bool
    {
        return $this->kuota_terpakai < $this->kuota_maksimal;
    }

    public function sisaKuota(): int
    {
        return max(0, $this->kuota_maksimal - $this->kuota_terpakai);
    }
}