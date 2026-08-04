@props([
    'column',
    'label',
    'sortBy',
    'sortDirection'
])

@php
    $isActive = $sortBy === $column;

    $nextDirection = $isActive && $sortDirection === 'asc'
        ? 'desc'
        : 'asc';
@endphp

<a
    href="{{ request()->fullUrlWithQuery([
        'sort' => $column,
        'arah' => $nextDirection,
    ]) }}"
    class="inline-flex items-center gap-1.5 transition hover:text-pln-navy-900"
>

    {{ $label }}

    <x-icon
        :name="$isActive
            ? ($sortDirection === 'asc'
                ? 'arrow-trend-up'
                : 'arrow-trend-down')
            : 'arrows-up-down'"
        @class([
            'h-3.5 w-3.5',
            'text-pln-navy-700' => $isActive,
            'text-pln-slate-300' => ! $isActive,
        ])
    />

</a>