@props(['reservasi'])
@php
    use App\Enums\ReservasiStatus;

    $isCancelled = $reservasi->status === ReservasiStatus::Dibatalkan;
    $statusSaatIni = $reservasi->status;

    // "Perlu Datang" dan "Selesai Online" adalah dua CABANG alternatif
    // (lihat state diagram PRD 30.2) — satu reservasi hanya melalui
    // SALAH SATU dari keduanya. Stepper harus menampilkan hanya cabang
    // yang benar-benar dilalui, bukan menganggap keduanya berurutan.
    $cabangDilalui = match (true) {
        in_array($statusSaatIni, [ReservasiStatus::PerluDatang, ReservasiStatus::SelesaiOnline], true)
            => $statusSaatIni,
        $statusSaatIni === ReservasiStatus::Selesai => (function () use ($reservasi) {
            $ditemukan = $reservasi->statusHistories->first(function ($riwayat) {
                $nilai = $riwayat->status_sesudah instanceof ReservasiStatus
                    ? $riwayat->status_sesudah->value
                    : $riwayat->status_sesudah;

                return in_array($nilai, ['perlu_datang', 'selesai_online'], true);
            });

            if (! $ditemukan) {
                return null;
            }

            return $ditemukan->status_sesudah instanceof ReservasiStatus
                ? $ditemukan->status_sesudah
                : ReservasiStatus::from($ditemukan->status_sesudah);
        })(),
        default => null, // masih Menunggu Review, cabang belum ditentukan
    };

    // Selama cabang belum ditentukan, tampilkan "Perlu Datang" sebagai
    // placeholder step ke-2 (perilaku default sebelumnya).
    $cabangEnum = $cabangDilalui ?? ReservasiStatus::PerluDatang;

    $tahapan = [
        ReservasiStatus::MenungguReview,
        $cabangEnum,
        ReservasiStatus::Selesai,
    ];

    $urutanStatus = [
        'menunggu_review' => 1,
        'perlu_datang' => 2,
        'selesai_online' => 2,
        'selesai' => 3,
    ];
    $urutanSaatIni = $urutanStatus[$statusSaatIni->value] ?? 0;
    $totalStep = count($tahapan);
@endphp
<div>
    <h2 class="font-display text-base font-semibold text-pln-navy-900">Status Reservasi</h2>
    @if ($isCancelled)
        <div class="mt-4">
            <x-badge variant="cancel" class="text-sm">Dibatalkan</x-badge>
            <p class="mt-2 text-sm text-pln-slate-500">Reservasi ini telah dibatalkan dan tidak dapat diproses lebih lanjut.</p>
        </div>
    @else
        <div class="mt-5 overflow-x-auto pb-2">
            <ol class="flex min-w-max items-start gap-2 sm:gap-4">
                @foreach ($tahapan as $index => $status)
                    @php
                        $stepOrder = $index + 1;
                        $isLastStep = $stepOrder === $totalStep;
                        $labelTahap = $status === ReservasiStatus::SelesaiOnline ? 'Selesai Online' : $status->label();

                        // Step terakhir (Selesai) bersifat terminal — begitu
                        // reservasi sampai di sana, dianggap TUNTAS (hijau),
                        // bukan "sedang berjalan" (kuning).
                        $sudahTercapai = $stepOrder < $urutanSaatIni
                            || ($isLastStep && $stepOrder === $urutanSaatIni);
                        $sedangAktif = ! $sudahTercapai && $stepOrder === $urutanSaatIni;
                    @endphp
                    <li class="flex flex-col items-center gap-2 text-center" style="width: 110px">
                        <span
                            @class([
                                'flex h-9 w-9 items-center justify-center rounded-full text-xs font-semibold',
                                'bg-status-done text-white' => $sudahTercapai,
                                'bg-pln-amber-500 text-white' => $sedangAktif,
                                'border-2 border-pln-slate-300 text-pln-slate-400' => ! $sudahTercapai && ! $sedangAktif,
                            ])
                        >
                            @if ($sudahTercapai)
                                <x-icon name="check" class="h-4 w-4" />
                            @else
                                <x-icon name="clock" class="h-4 w-4" />
                            @endif
                        </span>
                        <p
                            @class([
                                'text-sm font-semibold',
                                'text-pln-navy-900' => $sudahTercapai || $sedangAktif,
                                'text-pln-slate-400' => ! $sudahTercapai && ! $sedangAktif,
                            ])
                        >
                            {{ $labelTahap }}
                        </p>
                        <p class="text-xs leading-snug text-pln-slate-400">
                            {{ $status->hintCs() }}
                        </p>
                    </li>
                    @if (! $loop->last)
                        <li class="mt-4 hidden h-px w-8 shrink-0 bg-pln-slate-200 sm:block" aria-hidden="true"></li>
                    @endif
                @endforeach
            </ol>
        </div>
    @endif
</div>