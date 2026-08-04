<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
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
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'tanggal' => 'date',
            'is_active' => 'boolean',
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

    /**
     * Jadwal yang boleh dipilih pelanggan di Form Reservasi: aktif
     * DAN masih memiliki sisa kuota. Dipakai oleh ReservasiController
     * Sprint 2 agar perubahan Admin di modul ini langsung berdampak.
     */
    public function scopeTersediaUntukPelanggan(Builder $query): Builder
    {
        return $query
            ->where('is_active', true)
            ->whereColumn('kuota_terpakai', '<', 'kuota_maksimal');
    }

    public function isTersedia(): bool
    {
        return $this->is_active && $this->kuota_terpakai < $this->kuota_maksimal;
    }

    public function sisaKuota(): int
    {
        return max(0, $this->kuota_maksimal - $this->kuota_terpakai);
    }

    /**
     * Status tampilan untuk badge pada halaman Kelola Jadwal:
     * "nonaktif" jika di-nonaktifkan Admin, "penuh" jika kuota habis,
     * "aktif" jika aktif dan masih ada sisa kuota.
     */
    protected function statusTampilan(): Attribute
    {
        return Attribute::make(
            get: function () {
                if (! $this->is_active) {
                    return 'nonaktif';
                }

                if ($this->kuota_terpakai >= $this->kuota_maksimal) {
                    return 'penuh';
                }

                return 'aktif';
            },
        );
    }

    /**
     * Apakah jadwal ini boleh dihapus permanen — hanya jika belum pernah
     * dipakai reservasi sama sekali (kuota_terpakai adalah indikator cukup,
     * namun dicek ulang lewat relasi agar konsisten dengan constraint FK).
     */
    public function bolehDihapusPermanen(): bool
    {
        return ! $this->reservasis()->exists();
    }
}