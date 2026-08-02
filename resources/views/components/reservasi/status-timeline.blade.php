@props(['reservasi'])

@php
    $stages = [
        \App\Enums\ReservasiStatus::MenungguReview,
        \App\Enums\ReservasiStatus::PerluDatang,
        \App\Enums\ReservasiStatus::SelesaiOnline,
        \App\Enums\ReservasiStatus::Selesai,
        \App\Enums\ReservasiStatus::Dibatalkan,
    ];

    $currentStatus = $reservasi->status;
    $histories = $reservasi->statusHistories;
@endphp

<ol class="space-y-5">
    @foreach ($stages as $stage)
        @php
            $history = $histories->firstWhere('status_sesudah', $stage);
            $isReached = (bool) $history;
            $isCurrent = $currentStatus === $stage;
        @endphp

        <li class="relative flex gap-4">
            @if (! $loop->last)
                <span
                    @class([
                        'absolute left-[6px] top-5 h-full w-px',
                        'bg-status-done' => $isReached && ! $isCurrent,
                        'bg-pln-slate-200' => ! ($isReached && ! $isCurrent),
                    ])
                    aria-hidden="true"
                ></span>
            @endif

            <span
                @class([
                    'relative z-10 mt-1 h-3.5 w-3.5 shrink-0 rounded-full ring-4 ring-white',
                    'bg-pln-amber-500' => $isCurrent,
                    'bg-status-done' => $isReached && ! $isCurrent,
                    'bg-pln-slate-300' => ! $isReached && ! $isCurrent,
                ])
                aria-hidden="true"
            ></span>

            <div class="flex-1 pb-1">
                <div @class(['rounded-xl px-4 py-3', 'bg-pln-amber-500/10' => $isCurrent])>
                    <p
                        @class([
                            'text-sm font-semibold',
                            'text-pln-navy-900' => $isCurrent || $isReached,
                            'text-pln-slate-400' => ! $isCurrent && ! $isReached,
                        ])
                    >
                        {{ $stage->label() }}
                    </p>

                    <p class="mt-1 text-xs leading-relaxed text-pln-slate-500">
                        {{ ($isReached && $history->keterangan) ? $history->keterangan : $stage->hint() }}
                    </p>

                    @if ($isReached)
                        <p class="mt-1.5 text-xs font-medium text-pln-slate-400">
                            {{ $history->changed_at->translatedFormat('d M Y - H:i') }}
                        </p>
                    @endif
                </div>
            </div>
        </li>
    @endforeach
</ol>