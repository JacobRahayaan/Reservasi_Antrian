@props([
    'variant' => 'info',
    'title' => null,
    'dismissible' => false,
])

@php
    $variants = [
        'info' => ['wrap' => 'bg-pln-navy-900/5 border-pln-navy-900/10', 'icon' => 'text-pln-navy-700'],
        'success' => ['wrap' => 'bg-status-done/5 border-status-done/20', 'icon' => 'text-status-done'],
        'warning' => ['wrap' => 'bg-status-visit/5 border-status-visit/20', 'icon' => 'text-status-visit'],
        'danger' => ['wrap' => 'bg-status-cancel/5 border-status-cancel/20', 'icon' => 'text-status-cancel'],
    ];

    $style = $variants[$variant] ?? $variants['info'];
@endphp

<div
    role="alert"
    {{ $attributes->merge(['class' => "flex gap-3 rounded-xl border px-4 py-3.5 {$style['wrap']}"]) }}
>
    <svg viewBox="0 0 24 24" class="mt-0.5 h-5 w-5 shrink-0 {{ $style['icon'] }}" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
        <circle cx="12" cy="12" r="9" />
        <path stroke-linecap="round" d="M12 8v5M12 16h.01" />
    </svg>

    <div class="flex-1 text-sm">
        @if ($title)
            <p class="font-semibold text-pln-slate-900">{{ $title }}</p>
        @endif
        <div class="{{ $title ? 'mt-0.5' : '' }} text-pln-slate-700">
            {{ $slot }}
        </div>
    </div>

    @if ($dismissible)
        <button
            type="button"
            onclick="this.closest('[role=alert]').remove()"
            class="shrink-0 rounded-md p-1 text-pln-slate-400 transition hover:bg-black/5 hover:text-pln-slate-600"
            aria-label="Tutup peringatan"
        >
            <svg viewBox="0 0 24 24" class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                <path stroke-linecap="round" d="M6 6l12 12M18 6L6 18" />
            </svg>
        </button>
    @endif
</div>