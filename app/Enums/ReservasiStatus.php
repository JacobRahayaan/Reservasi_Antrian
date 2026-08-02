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
}