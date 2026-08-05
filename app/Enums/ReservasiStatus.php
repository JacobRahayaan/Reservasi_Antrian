<?php

namespace App\Enums;

enum ReservasiStatus: string
{
    case MenungguReview = 'menunggu_review';
    case PerluDatang = 'perlu_datang';
    case SelesaiOnline = 'selesai_online';
    case Selesai = 'selesai';
    case Dibatalkan = 'dibatalkan';

    public function label(): string
    {
        return match ($this) {
            self::MenungguReview => 'Menunggu Review',
            self::PerluDatang => 'Perlu Datang',
            self::SelesaiOnline => 'Selesai Online',
            self::Selesai => 'Selesai',
            self::Dibatalkan => 'Dibatalkan',
        };
    }

    public function badgeVariant(): string
    {
        return match ($this) {
            self::MenungguReview => 'review',
            self::PerluDatang => 'visit',
            self::SelesaiOnline => 'online',
            self::Selesai => 'done',
            self::Dibatalkan => 'cancel',
        };
    }

    /**
     * Deskripsi singkat tahap status, dipakai sebagai teks default pada
     * timeline riwayat status ketika belum ada catatan khusus untuk tahap
     * tersebut (mis. tahap yang belum tercapai).
     */
    public function hint(): string
    {
        return match ($this) {
            self::MenungguReview => 'Reservasi Anda sedang di-review oleh Customer Service.',
            self::PerluDatang => 'Akan diinformasikan jika perlu datang ke kantor PLN.',
            self::SelesaiOnline => 'Jika keluhan dapat diselesaikan secara online.',
            self::Selesai => 'Reservasi telah selesai.',
            self::Dibatalkan => 'Reservasi dibatalkan.',
        };
    }

    /**
     * Deskripsi singkat khusus untuk konteks kerja Customer Service
     * (dipakai pada halaman Detail Reservasi CS), berbeda nuansa dari
     * hint() yang ditujukan untuk pelanggan.
     */
    public function hintCs(): string
    {
        return match ($this) {
            self::MenungguReview => 'Sedang di-review oleh Customer Service',
            self::PerluDatang => 'Menunggu konfirmasi kehadiran',
            self::SelesaiOnline => 'Selesai tanpa perlu datang ke kantor',
            self::Selesai => 'Reservasi telah selesai',
            self::Dibatalkan => 'Reservasi dibatalkan',
        };
    }

    /**
     * Daftar status yang valid sebagai tujuan transisi dari status saat ini,
     * sesuai state diagram bisnis (PRD BR-05). Status final (Selesai,
     * Dibatalkan) mengembalikan array kosong — tidak ada transisi keluar.
     *
     * @return array<int, self>
     */
    public function transisiValidBerikutnya(): array
    {
        return match ($this) {
            self::MenungguReview => [self::PerluDatang, self::SelesaiOnline, self::Dibatalkan],
            self::PerluDatang => [self::Selesai, self::Dibatalkan],
            self::SelesaiOnline => [self::Selesai],
            self::Selesai, self::Dibatalkan => [],
        };
    }

    public function bisaBertransisiKe(self $tujuan): bool
    {
        return in_array($tujuan, $this->transisiValidBerikutnya(), true);
    }
}