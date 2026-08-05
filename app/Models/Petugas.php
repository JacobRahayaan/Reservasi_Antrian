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
     * dibangun (dilarang eksplisit di Sprint 6). Mengembalikan petugas aktif
     * pertama di database. Ganti dengan `auth()->user()->petugas` begitu
     * modul Login dibangun — tidak ada logika lain yang bergantung pada
     * detail implementasi method ini selain nilai kembaliannya.
     */
    public static function aktifSaatIni(): self
    {
        return static::query()
            ->where('is_active', true)
            ->oldest('id')
            ->firstOrFail();
    }
}