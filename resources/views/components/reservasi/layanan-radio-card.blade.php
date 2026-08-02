@props([
    'name',
    'value',
    'icon',
    'iconBg' => 'bg-pln-navy-600',
    'title',
    'description',
    'checked' => false,
])

<label class="flex cursor-pointer items-start gap-3 rounded-xl border-2 border-pln-slate-200 bg-white p-4 transition hover:border-pln-slate-300 has-[:checked]:border-pln-navy-700 has-[:checked]:bg-pln-navy-900/5">
    <input
        type="radio"
        name="{{ $name }}"
        value="{{ $value }}"
        @checked($checked)
        class="peer sr-only"
        {{ $attributes }}
    >

    <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full {{ $iconBg }} text-white">
        <x-icon :name="$icon" class="h-5 w-5" />
    </span>

    <span class="flex-1">
        <span class="block text-sm font-semibold text-pln-navy-900">{{ $title }}</span>
        <span class="mt-1 block text-xs leading-relaxed text-pln-slate-500">{{ $description }}</span>
    </span>

    <span class="mt-1 h-5 w-5 shrink-0 rounded-full border-2 border-pln-slate-300 bg-white transition-all peer-checked:border-[6px] peer-checked:border-pln-navy-700"></span>
</label>