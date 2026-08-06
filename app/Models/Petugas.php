<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Petugas extends Model
{
    use HasFactory;

    protected $table = 'petugas';

    protected $fillable = [
        'nama_petugas',
        'email',
        'no_hp',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function statusHistories(): HasMany
    {
        return $this->hasMany(StatusHistory::class);
    }

    public function notes(): HasMany
    {
        return $this->hasMany(ReservasiNote::class);
    }

    /**
     * Simulasi "petugas yang sedang login", karena fitur autentikasi belum
     * dibangun. Mengembalikan petugas aktif pertama di database. Ganti
     * dengan `auth()->user()->petugas` begitu modul Login dibangun.
     */
    public static function aktifSaatIni(): self
    {
        return static::query()
            ->where('is_active', true)
            ->oldest('id')
            ->firstOrFail();
    }

    /**
     * Apakah petugas ini pernah bertindak di sistem (menulis catatan atau
     * mengubah status reservasi). Dipakai untuk menentukan apakah petugas
     * boleh dihapus permanen atau harus dinonaktifkan saja — mencegah
     * kehilangan jejak audit pada riwayat status/catatan yang sudah tercatat.
     */
    public function pernahBertindak(): bool
    {
        return $this->statusHistories()->exists() || $this->notes()->exists();
    }
}