@props(['pertanyaan'])

<details class="group rounded-lg border border-pln-slate-200 open:border-pln-navy-300 open:bg-pln-navy-900/5">
    <summary class="flex cursor-pointer list-none items-center justify-between gap-3 px-4 py-3 text-sm font-semibold text-pln-navy-900 marker:content-none">
        {{ $pertanyaan }}
        <x-icon name="chevron-down" class="h-4 w-4 shrink-0 text-pln-slate-400 transition-transform group-open:rotate-180" />
    </summary>
    <div class="px-4 pb-4 text-sm leading-relaxed text-pln-slate-600">
        {{ $slot }}
    </div>
</details>