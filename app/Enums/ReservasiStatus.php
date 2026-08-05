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

    /**
     * Apakah pelanggan (bukan CS) masih diizinkan mengubah jadwal reservasi
     * pada status ini. Sesuai FR-06 PRD: hanya Menunggu Review & Perlu Datang.
     */
    public function bisaDiubahJadwalOlehPelanggan(): bool
    {
        return in_array($this, [self::MenungguReview, self::PerluDatang], true);
    }

    /**
     * Apakah pelanggan masih diizinkan membatalkan reservasi pada status
     * ini. Sesuai BR-07 PRD: diizinkan selama belum Selesai atau Dibatalkan.
     */
    public function bisaDibatalkanOlehPelanggan(): bool
    {
        return ! in_array($this, [self::Selesai, self::Dibatalkan], true);
    }
}