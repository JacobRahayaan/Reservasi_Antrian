@props(['data', 'total'])

@php
    $colors = ['#15395a', '#f2a93c', '#1f8b3f', '#2563a8', '#b23b3b'];

    $currentAngle = 0;
    $segments = [];

    foreach ($data as $index => $item) {
        if ($item['jumlah'] <= 0) {
            continue;
        }

        $percentage = $total > 0 ? ($item['jumlah'] / $total) * 100 : 0;
        $startAngle = $currentAngle;
        $endAngle = $currentAngle + ($percentage * 3.6);
        $color = $colors[$index % count($colors)];
        $segments[] = "{$color} {$startAngle}deg {$endAngle}deg";
        $currentAngle = $endAngle;
    }

    $gradient = count($segments) > 0
        ? 'conic-gradient(' . implode(', ', $segments) . ')'
        : null;
@endphp

@if ($total <= 0)
    <x-empty-state
        title="Belum ada reservasi"
        description="Distribusi layanan akan tampil setelah ada reservasi pada tanggal ini."
    />
@else
    <div class="flex flex-col items-center gap-6 sm:flex-row sm:items-center">
        <div
            class="relative flex h-40 w-40 shrink-0 items-center justify-center rounded-full"
            style="background: {{ $gradient }}"
        >
            <div class="flex h-28 w-28 flex-col items-center justify-center rounded-full bg-white">
                <span class="font-display text-2xl font-bold text-pln-navy-950">{{ $total }}</span>
                <span class="text-xs text-pln-slate-400">Total</span>
            </div>
        </div>

        <ul class="w-full space-y-3">
            @foreach ($data as $index => $item)
                <li class="flex items-center justify-between gap-3 text-sm">
                    <span class="flex items-center gap-2">
                        <span class="h-2.5 w-2.5 shrink-0 rounded-full" style="background-color: {{ $colors[$index % count($colors)] }}"></span>
                        <span class="text-pln-slate-700">{{ $item['label'] }}</span>
                    </span>
                    <span class="font-semibold text-pln-navy-900">
                        {{ $item['jumlah'] }} ({{ $total > 0 ? round(($item['jumlah'] / $total) * 100, 1) : 0 }}%)
                    </span>
                </li>
            @endforeach
        </ul>
    </div>
@endif