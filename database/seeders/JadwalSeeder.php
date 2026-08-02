<?php

namespace Database\Seeders;

use App\Models\Jadwal;
use App\Models\Layanan;
use Carbon\CarbonImmutable;
use Illuminate\Database\Seeder;

class JadwalSeeder extends Seeder
{
    private const KUOTA_PER_SLOT = 3;
    private const HARI_KE_DEPAN = 14;

    public function run(): void
    {
        $layanans = Layanan::query()->where('is_active', true)->get();

        if ($layanans->isEmpty()) {
            return;
        }

        $tanggalMulai = CarbonImmutable::today();

        for ($i = 0; $i < self::HARI_KE_DEPAN; $i++) {
            $tanggal = $tanggalMulai->addDays($i);
            $jamSlot = $this->jamSlotUntukHari($tanggal->dayOfWeekIso);

            if (empty($jamSlot)) {
                continue;
            }

            foreach ($layanans as $layanan) {
                foreach ($jamSlot as [$jamMulai, $jamSelesai]) {
                    Jadwal::query()->updateOrCreate(
                        [
                            'layanan_id' => $layanan->id,
                            'tanggal' => $tanggal->toDateString(),
                            'jam_mulai' => $jamMulai,
                        ],
                        [
                            'jam_selesai' => $jamSelesai,
                            'kuota_maksimal' => self::KUOTA_PER_SLOT,
                        ]
                    );
                }
            }
        }
    }

    /**
     * @return array<int, array{0: string, 1: string}>
     */
    private function jamSlotUntukHari(int $isoDayOfWeek): array
    {
        // 1 = Senin ... 6 = Sabtu, 7 = Minggu
        if ($isoDayOfWeek === 7) {
            return [];
        }

        if ($isoDayOfWeek === 6) {
            return [];
        }

        return [
            ['08:00', '09:00'],
            ['09:00', '10:00'],
            ['10:00', '11:00'],
            ['11:00', '12:00'],
            ['13:00', '14:00'],
            ['14:00', '15:00'],
			['15:00', '16:00'],
        ];
    }
}