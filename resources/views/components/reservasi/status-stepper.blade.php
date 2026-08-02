@props(['status'])

@php
    $isCancelled = $status === \App\Enums\ReservasiStatus::Dibatalkan;

    $stages = [
        'menunggu_review' => 'Menunggu Review',
        'keputusan' => $status === \App\Enums\ReservasiStatus::SelesaiOnline ? 'Selesai Online' : 'Perlu Datang',
        'selesai' => 'Selesai',
    ];

    $order = [
        'menunggu_review' => 1,
        'perlu_datang' => 2,
        'selesai_online' => 2,
        'selesai' => 3,
        'dibatalkan' => 0,
    ];

    $currentOrder = $order[$status->value] ?? 1;
@endphp

@if ($isCancelled)
    <x-badge variant="cancel" class="text-sm">Dibatalkan</x-badge>
@else
    <ol class="flex items-center gap-2 sm:gap-4">
        @foreach ($stages as $key => $label)
            @php $stepOrder = $loop->iteration; @endphp
            <li class="flex items-center gap-2 sm:gap-4">
                <div class="flex flex-col items-center gap-1.5">
                    <span
                        @class([
                            'flex h-8 w-8 items-center justify-center rounded-full text-xs font-semibold',
                            'bg-status-done text-white' => $stepOrder < $currentOrder,
                            'bg-pln-navy-800 text-white' => $stepOrder === $currentOrder,
                            'border-2 border-pln-slate-300 text-pln-slate-400' => $stepOrder > $currentOrder,
                        ])
                    >
                        {{ $stepOrder }}
                    </span>
                    <span
                        @class([
                            'text-center text-xs font-medium',
                            'text-pln-navy-900' => $stepOrder <= $currentOrder,
                            'text-pln-slate-400' => $stepOrder > $currentOrder,
                        ])
                    >
                        {{ $label }}
                    </span>
                </div>

                @if (! $loop->last)
                    <span class="mb-5 h-px w-6 bg-pln-slate-300 sm:w-12" aria-hidden="true"></span>
                @endif
            </li>
        @endforeach
    </ol>
@endif