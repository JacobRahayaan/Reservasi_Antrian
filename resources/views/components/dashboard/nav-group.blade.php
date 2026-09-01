@props(['title'])

<div>
    <p class="px-3 text-xs font-semibold uppercase tracking-wider text-pln-slate-400">{{ $title }}</p>
    <div class="mt-2 space-y-1">
        {{ $slot }}
    </div>
</div>