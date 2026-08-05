<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengumuman extends Model
{
    use HasFactory;

    protected $table = 'pengumuman';

    protected $fillable = [
        'judul',
        'isi',
        'tanggal_mulai',
        'tanggal_selesai',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_mulai' => 'date',
            'tanggal_selesai' => 'date',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Pengumuman yang layak ditampilkan ke publik saat ini: aktif, sudah
     * mulai tayang, dan belum melewati tanggal selesai (jika ada). Siap
     * dipakai untuk menampilkan pengumuman di Landing Page pada iterasi
     * berikutnya tanpa perlu query tambahan.
     */
    public function scopeAktifSaatIni(Builder $query): Builder
    {
        return $query
            ->where('is_active', true)
            ->whereDate('tanggal_mulai', '<=', now())
            ->where(function (Builder $q) {
                $q->whereNull('tanggal_selesai')
                    ->orWhereDate('tanggal_selesai', '>=', now());
            });
    }

    /**
     * Status tampilan untuk badge pada halaman Kelola Pengumuman:
     * "nonaktif" (dinonaktifkan Admin), "terjadwal" (belum mulai),
     * "berakhir" (sudah lewat tanggal selesai), atau "aktif".
     */
    protected function statusTampilan(): Attribute
    {
        return Attribute::make(
            get: function () {
                if (! $this->is_active) {
                    return 'nonaktif';
                }
                if ($this->tanggal_mulai->isFuture()) {
                    return 'terjadwal';
                }
                if ($this->tanggal_selesai && $this->tanggal_selesai->isPast()) {
                    return 'berakhir';
                }
                return 'aktif';
            },
        );
    }
}