@props(['icon', 'iconBg' => 'bg-pln-navy-600/10', 'iconColor' => 'text-pln-navy-700', 'label', 'persentase', 'keterangan'])

<x-card padding="p-5">
    <div class="flex items-center gap-3">
        <span class="flex h-10 w-10 items-center justify-center rounded-lg {{ $iconBg }} {{ $iconColor }}">
            <x-icon :name="$icon" class="h-5 w-5" />
        </span>
        <div>
            <p class="font-display text-2xl font-bold text-pln-navy-950">{{ $persentase }}%</p>
            <p class="text-xs text-pln-slate-500">{{ $keterangan }}</p>
        </div>
    </div>
    <p class="mt-3 text-sm font-medium text-pln-slate-700">{{ $label }}</p>
</x-card>