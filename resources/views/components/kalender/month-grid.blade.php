@props(['hariData', 'bulan', 'hrefBuilder'])

@php
    $awalBulan = $bulan->startOfMonth();
    $offsetAwal = $awalBulan->dayOfWeekIso - 1;
    $hariLabel = ['Senin', 'Selasa', 'Rabu', 'Kamis', "Jum'at", 'Sabtu', 'Minggu'];
    $hariIni = now()->toDateString();
@endphp

<div>
    <div class="grid grid-cols-7 gap-2 text-center text-xs font-semibold uppercase tracking-wider text-pln-slate-400">
        @foreach ($hariLabel as $label)
            <div class="py-2">{{ $label }}</div>
        @endforeach
    </div>

    <div class="mt-1 grid grid-cols-7 gap-2">
        @for ($i = 0; $i < $offsetAwal; $i++)
            <div></div>
        @endfor

        @foreach ($hariData as $item)
            @php
                $tanggal = $item['tanggal'];
                $isToday = $tanggal->toDateString() === $hariIni;
                $href = $item['ada_jadwal'] ? $hrefBuilder($tanggal) : null;

                $warnaIsi = match (true) {
                    ! $item['ada_jadwal'] => 'bg-pln-slate-100 text-pln-slate-400',
                    $item['persentase_terisi'] >= 90 => 'bg-status-cancel/10 text-status-cancel',
                    $item['persentase_terisi'] >= 60 => 'bg-pln-amber-500/10 text-pln-amber-600',
                    default => 'bg-status-done/10 text-status-done',
                };
            @endphp

            @if ($href)
                <a
                    href="{{ $href }}"
                    @class([
                        'flex min-h-[76px] flex-col items-center justify-center gap-1.5 rounded-xl border p-2 text-center transition hover:border-pln-navy-300 hover:shadow-sm',
                        'border-pln-amber-500 ring-2 ring-pln-amber-500/30' => $isToday,
                        'border-pln-slate-200' => ! $isToday,
                    ])
                >
                    <span @class(['text-sm font-semibold', 'text-pln-navy-900' => $isToday, 'text-pln-slate-700' => ! $isToday])>
                        {{ $tanggal->day }}
                    </span>
                    <span class="rounded-full px-2 py-0.5 text-[11px] font-semibold {{ $warnaIsi }}">
                        {{ $item['kuota_terpakai'] }}/{{ $item['kuota_maksimal'] }}
                    </span>
                </a>
            @else
                <div
                    @class([
                        'flex min-h-[76px] flex-col items-center justify-center gap-1.5 rounded-xl border border-dashed p-2 text-center',
                        'border-pln-amber-500 ring-2 ring-pln-amber-500/30' => $isToday,
                        'border-pln-slate-200' => ! $isToday,
                    ])
                >
                    <span @class(['text-sm font-semibold', 'text-pln-navy-900' => $isToday, 'text-pln-slate-400' => ! $isToday])>
                        {{ $tanggal->day }}
                    </span>
                    <span class="text-[11px] text-pln-slate-300">Tidak ada jadwal</span>
                </div>
            @endif
        @endforeach
    </div>
</div>