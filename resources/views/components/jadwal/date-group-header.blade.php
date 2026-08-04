@props(['tanggal'])

<tr>
    <td colspan="8" class="bg-pln-slate-50 px-4 py-2.5">
        <button
            type="button"
            data-toggle-target="grup-tanggal-{{ $tanggal->format('Ymd') }}"
            class="flex w-full items-center gap-2 text-left"
            aria-expanded="true"
            aria-controls="grup-tanggal-{{ $tanggal->format('Ymd') }}"
        >
            <x-icon name="calendar" class="h-4 w-4 text-pln-navy-700" />
            <span class="text-sm font-semibold text-pln-navy-900">{{ $tanggal->translatedFormat('d F Y') }}</span>
            <x-icon data-toggle-icon name="chevron-down" class="ml-auto h-4 w-4 text-pln-slate-400 transition-transform" />
        </button>
    </td>
</tr>