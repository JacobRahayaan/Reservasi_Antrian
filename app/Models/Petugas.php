<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Petugas extends Authenticatable
{
    use HasFactory;
    use Notifiable;

    protected $table = 'petugas';

    protected $fillable = [
        'nama_petugas',
        'email',
        'no_hp',
        'password',
        'is_active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
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
     * Nama tampilan yang seragam lintas guard (admin & petugas). Tabel
     * petugas memakai kolom `nama_petugas` (bukan `nama`), sehingga
     * accessor ini menjembatani perbedaan nama kolom tanpa mengubah skema
     * yang sudah ada sejak Sprint 6.
     */
    protected function namaTampilan(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->nama_petugas,
        );
    }

    /**
     * Label peran yang seragam lintas guard, dipakai View Composer untuk
     * mengisi @yield('user-role') secara otomatis.
     */
    public function labelPeran(): string
    {
        return 'Customer Service';
    }

    /**
     * Inisial 1-2 huruf untuk avatar bulat di topbar, seragam lintas guard.
     */
    public function inisial(): string
    {
        return mb_substr($this->nama_petugas, 0, 2);
    }

    /**
     * Apakah petugas ini pernah bertindak di sistem (menulis catatan atau
     * mengubah status reservasi). Dipakai untuk menentukan apakah petugas
     * boleh dihapus permanen atau harus dinonaktifkan saja.
     */
    public function pernahBertindak(): bool
    {
        return $this->statusHistories()->exists() || $this->notes()->exists();
    }
}