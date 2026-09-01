<?php

namespace App\Enums;

enum StatusSinkronFisik: string
{
    case TidakPerlu = 'tidak_perlu';
    case BelumDisinkronkan = 'belum_disinkronkan';
    case SudahDisinkronkan = 'sudah_disinkronkan';

    public function label(): string
    {
        return match ($this) {
            self::TidakPerlu => 'Tidak Perlu',
            self::BelumDisinkronkan => 'Belum Dicetak di Mesin',
            self::SudahDisinkronkan => 'Sudah Dicetak di Mesin',
        };
    }

    public function badgeVariant(): string
    {
        return match ($this) {
            self::TidakPerlu => 'neutral',
            self::BelumDisinkronkan => 'visit',
            self::SudahDisinkronkan => 'done',
        };
    }
}