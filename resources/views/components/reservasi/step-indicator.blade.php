@props(['current' => 1])

@php
    $steps = [
        1 => 'Formulir',
        2 => 'Konfirmasi',
        3 => 'Selesai',
    ];
@endphp

<ol class="flex items-center gap-2 sm:gap-3">
    @foreach ($steps as $number => $label)
        <li class="flex items-center gap-2 sm:gap-3">
            <div class="flex flex-col items-center gap-1.5">
                <span
                    @class([
                        'flex h-9 w-9 items-center justify-center rounded-full text-sm font-semibold',
                        'bg-pln-navy-800 text-white' => $number <= $current,
                        'border-2 border-pln-slate-300 text-pln-slate-400' => $number > $current,
                    ])
                >
                    {{ $number }}
                </span>
                <span
                    @class([
                        'text-xs font-medium',
                        'text-pln-navy-900' => $number <= $current,
                        'text-pln-slate-400' => $number > $current,
                    ])
                >
                    {{ $label }}
                </span>
            </div>

            @if ($number < count($steps))
                <span class="mb-5 h-px w-6 bg-pln-slate-300 sm:w-10" aria-hidden="true"></span>
            @endif
        </li>
    @endforeach
</ol>