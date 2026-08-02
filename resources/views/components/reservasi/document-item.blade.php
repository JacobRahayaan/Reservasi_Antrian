@props(['dokumen', 'downloadUrl'])

<div class="flex items-center gap-3 rounded-lg border border-pln-slate-200 bg-white px-4 py-3">
    <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-pln-navy-900/5 text-pln-navy-700">
        <x-icon name="document-text" class="h-4 w-4" />
    </span>

    <div class="min-w-0 flex-1">
        <p class="truncate text-sm font-medium text-pln-slate-900">{{ $dokumen->nama_file_asli }}</p>
        <p class="text-xs text-pln-slate-400">{{ $dokumen->ukuran_file_format }}</p>
    </div>

    <a
        href="{{ $downloadUrl }}"
        class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg text-pln-navy-700 transition hover:bg-pln-slate-100 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-pln-amber-500"
        aria-label="Unduh {{ $dokumen->nama_file_asli }}"
    >
        <svg viewBox="0 0 24 24" class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v12m0 0 4-4m-4 4-4-4M4 17v2a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-2" />
        </svg>
    </a>
</div>