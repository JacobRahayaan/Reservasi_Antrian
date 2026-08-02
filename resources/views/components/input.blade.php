@props([
    'label' => null,
    'name' => null,
    'type' => 'text',
    'error' => null,
    'hint' => null,
])

@php
    $id = $attributes->get('id', $name);

    $borderClass = $error
        ? 'border-status-cancel focus:ring-status-cancel/40'
        : 'border-pln-slate-200 focus:border-pln-navy-700 focus:ring-pln-navy-700/20';
@endphp

<div class="w-full">
    @if ($label)
        <label for="{{ $id }}" class="mb-1.5 block text-sm font-medium text-pln-slate-900">
            {{ $label }}
        </label>
    @endif

    <input
        type="{{ $type }}"
        name="{{ $name }}"
        id="{{ $id }}"
        {{ $attributes->except('id')->merge([
            'class' => "block w-full rounded-lg border px-3.5 py-2.5 text-sm text-pln-slate-900 placeholder:text-pln-slate-400 focus:outline-none focus:ring-2 $borderClass",
        ]) }}
    />

    @if ($error)
        <p class="mt-1.5 text-sm text-status-cancel">{{ $error }}</p>
    @elseif ($hint)
        <p class="mt-1.5 text-sm text-pln-slate-400">{{ $hint }}</p>
    @endif
</div>