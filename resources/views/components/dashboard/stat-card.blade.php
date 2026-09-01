@props([
    'label',
    'nilai',
    'icon',
    'warna' => 'blue',
    'persentase' => null,
    'arah' => null,
    'keterangan' => 'dari kemarin',
])

@php
    $warnaMap = [
        'blue' => 'bg-pln-navy-600',
        'amber' => 'bg-pln-amber-500',
        'orange' => 'bg-orange-500',
        'green' => 'bg-status-done',
        'purple' => 'bg-violet-500',
        'gray' => 'bg-pln-slate-400',
        'slate' => 'bg-pln-slate-700',
    ];

    $iconBg = $warnaMap[$warna] ?? $warnaMap['blue'];
@endphp

<x-card padding="p-5">
    <div class="flex h-10 w-10 items-center justify-center rounded-lg {{ $iconBg }} text-white">
        <x-icon :name="$icon" class="h-5 w-5" />
    </div>

    <p class="mt-4 text-sm font-medium text-pln-slate-500">{{ $label }}</p>
    <p class="mt-1 font-display text-3xl font-bold text-pln-navy-950">{{ $nilai }}</p>

    @if ($persentase !== null)
        <p
            @class([
                'mt-2 flex items-center gap-1 text-xs font-semibold',
                'text-status-done' => $arah === 'naik',
                'text-status-cancel' => $arah === 'turun',
            ])
        >
            <x-icon :name="$arah === 'turun' ? 'arrow-trend-down' : 'arrow-trend-up'" class="h-3.5 w-3.5" />
            {{ $persentase }}% {{ $keterangan }}
        </p>
    @else
        <p class="mt-2 text-xs text-pln-slate-400">{{ $keterangan }}</p>
    @endif
</x-card>