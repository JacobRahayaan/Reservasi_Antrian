<?php

namespace App\Models;

use App\Enums\ReservasiStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Reservasi extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode_reservasi',
        'token_akses',
        'nomor_antrean',
        'layanan_id',
        'jadwal_id',
        'nama',
        'nomor_hp',
        'email',
        'keluhan',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'status' => ReservasiStatus::class,
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (self $reservasi) {
            if (empty($reservasi->token_akses)) {
                $reservasi->token_akses = Str::random(40);
            }
        });
    }

    public function layanan(): BelongsTo
    {
        return $this->belongsTo(Layanan::class);
    }

    public function jadwal(): BelongsTo
    {
        return $this->belongsTo(Jadwal::class);
    }

    public function dokumen(): HasMany
    {
        return $this->hasMany(DokumenReservasi::class);
    }

    public function statusHistories(): HasMany
    {
        return $this->hasMany(StatusHistory::class)->latest('changed_at');
    }

    public function notes(): HasMany
    {
        return $this->hasMany(ReservasiNote::class)->latest();
    }

    public function getRouteKeyName(): string
    {
        return 'kode_reservasi';
    }
}