@props(['tabs', 'active'])

<div class="flex gap-1 overflow-x-auto border-b border-pln-slate-200" role="tablist">
    @foreach ($tabs as $value => $label)
        <a
            href="{{ request()->fullUrlWithQuery(['tab' => $value]) }}"
            role="tab"
            @class([
                'whitespace-nowrap border-b-2 px-4 py-2.5 text-sm font-medium transition',
                'border-pln-navy-700 text-pln-navy-900' => $active === $value,
                'border-transparent text-pln-slate-500 hover:text-pln-navy-900' => $active !== $value,
            ])
            aria-selected="{{ $active === $value ? 'true' : 'false' }}"
        >
            {{ $label }}
        </a>
    @endforeach
</div>