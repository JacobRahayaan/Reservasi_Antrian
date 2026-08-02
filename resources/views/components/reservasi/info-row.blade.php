@props(['icon', 'label', 'value'])

<div class="flex items-start gap-3">
    <span class="mt-0.5 flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-pln-navy-900/5 text-pln-navy-700">
        <x-icon :name="$icon" class="h-4 w-4" />
    </span>
    <div class="min-w-0">
        <p class="text-xs font-medium uppercase tracking-wider text-pln-slate-400">{{ $label }}</p>
        <p class="mt-0.5 break-words text-sm font-semibold text-pln-navy-900">{{ $value }}</p>
    </div>
</div>