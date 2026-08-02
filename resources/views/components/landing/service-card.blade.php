@props(['variant' => 'blue', 'icon', 'title', 'description', 'href' => '#'])

@php
    $variants = [
        'amber' => 'bg-pln-amber-500',
        'blue' => 'bg-pln-navy-600',
        'green' => 'bg-status-done',
    ];

    $iconBg = $variants[$variant] ?? $variants['blue'];
@endphp

<div class="rounded-2xl border border-pln-slate-200 bg-white p-6 shadow-sm transition hover:shadow-md">
    <div class="flex h-11 w-11 items-center justify-center rounded-full {{ $iconBg }} text-white">
        <x-icon :name="$icon" class="h-5 w-5" />
    </div>

    <h3 class="mt-4 font-display text-base font-semibold text-pln-navy-900">{{ $title }}</h3>
    <p class="mt-2 text-sm leading-relaxed text-pln-slate-600">{{ $description }}</p>

    <a href="{{ $href }}" class="mt-4 inline-flex items-center gap-1.5 text-sm font-semibold text-pln-navy-700 transition hover:text-pln-navy-900">
        Selengkapnya
        <x-icon name="arrow-right" class="h-4 w-4" />
    </a>
</div>