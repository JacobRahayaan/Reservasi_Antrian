@props(['data'])

@php
    $values = array_values($data);
    $labels = array_keys($data);
	$max = empty($values)
		? 1
		: max($values);

	$max = max($max, 1);

    $width = 600;
    $height = 220;
    $paddingLeft = 40;
    $paddingBottom = 30;
    $paddingTop = 15;
    $chartWidth = $width - $paddingLeft - 10;
    $chartHeight = $height - $paddingBottom - $paddingTop;

    $count = count($values);
    $stepX = $count > 1 ? $chartWidth / ($count - 1) : 0;

    $points = collect($values)
        ->map(function ($value, $index) use ($paddingLeft, $stepX, $chartHeight, $paddingTop, $max) {
            $x = $paddingLeft + ($stepX * $index);
            $y = $paddingTop + $chartHeight - (($value / $max) * $chartHeight);
            return "{$x},{$y}";
        })
        ->implode(' ');

    $yTicks = 4;
    $yStep = $max > 0 ? (int) ceil($max / $yTicks) : 1;
    $adaData = array_sum($values) > 0;
@endphp

@if (! $adaData)
    <x-empty-state
        title="Belum ada aktivitas reservasi"
        description="Grafik akan tampil setelah ada reservasi yang dibuat dalam 7 hari terakhir."
    />
@else
    <div class="w-full overflow-x-auto">
        <svg viewBox="0 0 {{ $width }} {{ $height }}" class="h-56 w-full min-w-[500px]" role="img" aria-label="Grafik reservasi 7 hari terakhir">
            @for ($i = 0; $i <= $yTicks; $i++)
                @php
                    $value = $yStep * $i;
                    $y = $paddingTop + $chartHeight - (($value / $max) * $chartHeight);
                @endphp
                <line x1="{{ $paddingLeft }}" y1="{{ $y }}" x2="{{ $width - 10 }}" y2="{{ $y }}" class="stroke-pln-slate-100" stroke-width="1" />
                <text x="{{ $paddingLeft - 8 }}" y="{{ $y + 4 }}" text-anchor="end" class="fill-pln-slate-400 text-[10px]">{{ $value }}</text>
            @endfor

            <polyline
                fill="none"
                points="{{ $points }}"
                class="stroke-pln-navy-700"
                stroke-width="2.5"
                stroke-linecap="round"
                stroke-linejoin="round"
            />

            @foreach ($values as $index => $value)
                @php
                    $x = $paddingLeft + ($stepX * $index);
                    $y = $paddingTop + $chartHeight - (($value / $max) * $chartHeight);
                @endphp
                <circle cx="{{ $x }}" cy="{{ $y }}" r="4" class="fill-white stroke-pln-navy-700" stroke-width="2.5" />
                <text x="{{ $x }}" y="{{ $height - 6 }}" text-anchor="middle" class="fill-pln-slate-400 text-[10px]">{{ $labels[$index] }}</text>
            @endforeach
        </svg>
    </div>
@endif