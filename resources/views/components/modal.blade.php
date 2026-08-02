@props([
    'id' => 'modal',
    'title' => null,
    'size' => 'md',
])

@php
    $sizes = [
        'sm' => 'max-w-sm',
        'md' => 'max-w-lg',
        'lg' => 'max-w-2xl',
    ];

    $width = $sizes[$size] ?? $sizes['md'];
@endphp

<dialog
    id="{{ $id }}"
    class="w-full {{ $width }} rounded-2xl border border-pln-slate-200 bg-white p-0 shadow-xl backdrop:bg-pln-navy-950/50"
>
    <div class="flex items-start justify-between gap-4 border-b border-pln-slate-100 px-6 py-4">
        <h2 class="font-display text-base font-semibold text-pln-navy-900">
            {{ $title }}
        </h2>
        <button
            type="button"
            data-modal-close
            class="rounded-md p-1 text-pln-slate-400 transition hover:bg-pln-slate-100 hover:text-pln-slate-600"
            aria-label="Tutup dialog"
        >
            <svg viewBox="0 0 24 24" class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                <path stroke-linecap="round" d="M6 6l12 12M18 6L6 18" />
            </svg>
        </button>
    </div>

    <div class="px-6 py-5">
        {{ $slot }}
    </div>

    @if (isset($footer))
        <div class="flex justify-end gap-3 border-t border-pln-slate-100 px-6 py-4">
            {{ $footer }}
        </div>
    @endif
</dialog>