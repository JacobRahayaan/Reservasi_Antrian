<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PengaturanSistem extends Model
{
    protected $fillable = [
        'nama_aplikasi',
        'nomor_contact_center',
        'email_contact_center',
        'alamat_kantor',
        'jam_buka_default',
        'jam_tutup_default',
        'maksimal_ukuran_dokumen_mb',
        'maksimal_jumlah_dokumen',
    ];

    /**
     * Ambil satu-satunya baris pengaturan sistem yang aktif. Jika belum ada
     * (mis. seeder belum dijalankan), baris dengan nilai default dibuat
     * otomatis agar halaman Pengaturan Sistem tidak pernah menampilkan
     * form kosong atau error "data tidak ditemukan".
     */
    public static function aktif(): self
    {
        return static::query()->firstOrCreate(
            ['id' => 1],
            [
                'nama_aplikasi' => 'SIRA-PLN',
                'nomor_contact_center' => '123',
                'email_contact_center' => null,
                'alamat_kantor' => null,
                'jam_buka_default' => '08:00',
                'jam_tutup_default' => '15:00',
                'maksimal_ukuran_dokumen_mb' => 2,
                'maksimal_jumlah_dokumen' => 3,
            ]
        );
    }
}