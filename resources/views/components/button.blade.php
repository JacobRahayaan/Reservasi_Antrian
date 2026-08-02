@props([
    'href' => null,
    'variant' => 'primary',
    'size' => 'md',
    'type' => 'button',
])

@php
    $base = 'inline-flex items-center justify-center gap-2 rounded-lg font-medium transition focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-pln-amber-500 disabled:cursor-not-allowed disabled:opacity-50';

    $variants = [
        'primary' => 'bg-pln-navy-900 text-white hover:bg-pln-navy-800 active:bg-pln-navy-950',
        'secondary' => 'bg-pln-amber-500 text-pln-navy-950 hover:bg-pln-amber-400 active:bg-pln-amber-600',
        'ghost' => 'bg-transparent text-pln-navy-900 hover:bg-pln-slate-100 active:bg-pln-slate-200',
        'danger' => 'bg-status-cancel text-white hover:opacity-90 active:opacity-100',
    ];

    $sizes = [
        'sm' => 'px-3 py-1.5 text-sm',
        'md' => 'px-4 py-2 text-sm',
        'lg' => 'px-6 py-3 text-base',
    ];

    $classes = $base . ' ' . ($variants[$variant] ?? $variants['primary']) . ' ' . ($sizes[$size] ?? $sizes['md']);
@endphp

@if ($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>
        {{ $slot }}
    </a>
@else
    <button type="{{ $type }}" {{ $attributes->merge(['class' => $classes]) }}>
        {{ $slot }}
    </button>
@endif