<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class AdminUser extends Authenticatable
{
    use HasFactory;
    use Notifiable;

    protected $table = 'admin_users';

    protected $fillable = [
        'nama',
        'email',
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

    /**
     * Nama tampilan yang seragam lintas guard (admin & petugas), sehingga
     * layout dashboard dan View Composer cukup memanggil satu properti
     * yang sama tanpa perlu tahu model mana yang sedang aktif.
     */
    protected function namaTampilan(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->nama,
        );
    }

    /**
     * Label peran yang seragam lintas guard, dipakai View Composer untuk
     * mengisi @yield('user-role') secara otomatis.
     */
    public function labelPeran(): string
    {
        return 'Administrator';
    }

    /**
     * Inisial 1-2 huruf untuk avatar bulat di topbar, seragam lintas guard.
     */
    public function inisial(): string
    {
        return mb_substr($this->nama, 0, 1);
    }
}