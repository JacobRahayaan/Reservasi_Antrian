@props([
    'icon',
    'iconBg' => 'bg-pln-navy-900/5',
    'iconColor' => 'text-pln-navy-700',
    'label',
    'value',
    'actionLabel' => 'Kelola',
    'href' => null,
])

<div class="rounded-xl border border-pln-slate-200 bg-white p-4">
    <div class="flex h-9 w-9 items-center justify-center rounded-lg {{ $iconBg }} {{ $iconColor }}">
        <x-icon :name="$icon" class="h-4 w-4" />
    </div>

    <p class="mt-3 text-xs font-medium text-pln-slate-500">{{ $label }}</p>
    <p class="mt-1 font-display text-xl font-bold text-pln-navy-950">{{ $value }}</p>

    @if ($href)
        <a href="{{ $href }}" class="mt-2 inline-block text-xs font-semibold text-pln-navy-700 hover:text-pln-navy-900">
            {{ $actionLabel }}
        </a>
    @else
        <span
            class="mt-2 inline-block cursor-not-allowed text-xs font-semibold text-pln-slate-300"
            title="Modul akan tersedia pada sprint berikutnya"
        >
            {{ $actionLabel }}
        </span>
    @endif
</div>