<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Layanan extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'kode_layanan',
        'nama_layanan',
        'deskripsi',
        'estimasi_menit_min',
        'estimasi_menit_max',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'estimasi_menit_min' => 'integer',
            'estimasi_menit_max' => 'integer',
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function jadwals(): HasMany
    {
        return $this->hasMany(Jadwal::class);
    }

    public function reservasis(): HasMany
    {
        return $this->hasMany(Reservasi::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Accessors
    |--------------------------------------------------------------------------
    */

    /**
     * Contoh:
     * 60 - 120 menit
     */
    protected function estimasiWaktuLabel(): Attribute
    {
        return Attribute::make(
            get: fn () => (
                $this->estimasi_menit_min !== null &&
                $this->estimasi_menit_max !== null
            )
                ? "{$this->estimasi_menit_min} - {$this->estimasi_menit_max} menit"
                : '-',
        );
    }

    /**
     * Status siap ditampilkan di Blade.
     */
    protected function statusLabel(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->is_active
                ? 'Aktif'
                : 'Nonaktif',
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Helper
    |--------------------------------------------------------------------------
    */

    /**
     * Mengecek apakah layanan pernah digunakan
     * oleh minimal satu reservasi.
     */
    public function pernahDigunakan(): bool
    {
        return $this->reservasis()->exists();
    }
}