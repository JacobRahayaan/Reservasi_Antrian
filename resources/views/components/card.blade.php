@props([
    'padding' => 'p-6',
])

<div {{ $attributes->merge(['class' => "rounded-xl border border-pln-slate-200 bg-white $padding shadow-sm"]) }}>
    @if (isset($header))
        <div class="mb-4 flex items-center justify-between border-b border-pln-slate-100 pb-4">
            {{ $header }}
        </div>
    @endif

    {{ $slot }}

    @if (isset($footer))
        <div class="mt-4 border-t border-pln-slate-100 pt-4">
            {{ $footer }}
        </div>
    @endif
</div>