@props(['variant' => 'blue', 'icon', 'title', 'href' => '#'])

@php
    $variants = [
        'amber' => 'bg-pln-amber-500',
        'blue' => 'bg-pln-navy-600',
        'green' => 'bg-status-done',
    ];

    $iconBg = $variants[$variant] ?? $variants['blue'];
@endphp

<a
    href="{{ $href }}"
    class="flex items-center gap-3 rounded-xl border border-pln-slate-200 bg-white px-4 py-3.5 transition hover:border-pln-navy-300 hover:shadow-sm"
>
    <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full {{ $iconBg }} text-white">
        <x-icon :name="$icon" class="h-4 w-4" />
    </span>

    <span class="flex-1 text-sm font-semibold text-pln-navy-900">{{ $title }}</span>

    <x-icon name="chevron-right" class="h-4 w-4 shrink-0 text-pln-slate-400" />
</a>