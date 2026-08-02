@props([
    'icon',
    'iconBg' => 'bg-pln-navy-900/5',
    'iconColor' => 'text-pln-navy-700',
    'title',
])

<div class="flex gap-3">
    <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full {{ $iconBg }} {{ $iconColor }}">
        <x-icon :name="$icon" class="h-4 w-4" />
    </span>
    <div>
        <p class="text-sm font-semibold text-pln-navy-900">{{ $title }}</p>
        <p class="mt-0.5 text-xs leading-relaxed text-pln-slate-500">{{ $slot }}</p>
    </div>
</div>