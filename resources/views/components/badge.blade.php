@props([
    'variant' => 'neutral',
])

@php
    $variants = [
        'neutral' => 'bg-pln-slate-100 text-pln-slate-600',
        'review' => 'bg-status-review/10 text-status-review',
        'visit' => 'bg-status-visit/10 text-status-visit',
        'online' => 'bg-status-online/10 text-status-online',
        'done' => 'bg-status-done/10 text-status-done',
        'cancel' => 'bg-status-cancel/10 text-status-cancel',
    ];

    $classes = $variants[$variant] ?? $variants['neutral'];
@endphp

<span {{ $attributes->merge(['class' => "inline-flex items-center gap-1.5 rounded-full px-2.5 py-1 text-xs font-medium $classes"]) }}>
    <span class="h-1.5 w-1.5 rounded-full bg-current"></span>
    {{ $slot }}
</span>