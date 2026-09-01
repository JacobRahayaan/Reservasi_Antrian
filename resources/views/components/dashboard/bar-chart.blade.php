@props(['data'])

@php
    $adaData = array_sum($data) > 0;

    $values = array_values($data);

    $max = empty($values)
        ? 1
        : max($values);
@endphp

@if (! $adaData)
    <x-empty-state
        title="Belum ada reservasi hari ini"
        description="Grafik akan tampil setelah ada reservasi dengan jadwal kedatangan hari ini."
    />
@else
    <div class="flex items-end gap-3 overflow-x-auto pb-2 pt-6">
        @foreach ($data as $jam => $jumlah)
            <div class="flex min-w-[40px] flex-1 flex-col items-center gap-2">
                <span class="text-xs font-semibold text-pln-navy-900">{{ $jumlah > 0 ? $jumlah : '' }}</span>
                <div class="flex h-32 w-full items-end">
                    <div
                        class="w-full rounded-t-md bg-pln-navy-600"
                        style="height: {{ $jumlah > 0 ? max(8, ($jumlah / $max) * 100) : 0 }}%"
                    ></div>
                </div>
                <span class="text-xs text-pln-slate-400">{{ $jam }}</span>
            </div>
        @endforeach
    </div>
@endif