@props(['icon', 'title', 'number' => null])

<x-card padding="p-6">
    <div class="flex items-start gap-4">
        <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-pln-navy-900/5 text-pln-navy-700">
            @if ($number)
                <span class="font-display text-sm font-bold">{{ $number }}</span>
            @else
                <x-icon :name="$icon" class="h-5 w-5" />
            @endif
        </span>
        <div class="min-w-0 flex-1">
            <h2 class="font-display text-base font-semibold text-pln-navy-900">{{ $title }}</h2>
            <div class="mt-3 space-y-3 text-sm leading-relaxed text-pln-slate-600">
                {{ $slot }}
            </div>
        </div>
    </div>
</x-card>