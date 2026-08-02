@props(['icon', 'text'])

<div class="flex items-start gap-3">
    <span class="mt-0.5 flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-pln-navy-900/5 text-pln-navy-700">
        <x-icon :name="$icon" class="h-4 w-4" />
    </span>
    <p class="text-sm font-medium leading-relaxed text-pln-slate-700">{{ $text }}</p>
</div>