@props([
    'title' => 'Belum ada data',
    'description' => null,
])

<div {{ $attributes->merge(['class' => 'flex flex-col items-center justify-center rounded-xl border border-dashed border-pln-slate-300 bg-white px-6 py-12 text-center']) }}>
    <div class="flex h-12 w-12 items-center justify-center rounded-full bg-pln-slate-100 text-pln-slate-400">
        <svg viewBox="0 0 24 24" class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 13h6m-7 7h8a2 2 0 002-2V6a2 2 0 00-2-2H8a2 2 0 00-2 2v12a2 2 0 002 2z" />
        </svg>
    </div>

    <h3 class="mt-4 font-display text-base font-semibold text-pln-slate-900">
        {{ $title }}
    </h3>

    @if ($description)
        <p class="mt-1.5 max-w-sm text-sm text-pln-slate-500">
            {{ $description }}
        </p>
    @endif

    @if (isset($action))
        <div class="mt-5">
            {{ $action }}
        </div>
    @endif
</div>