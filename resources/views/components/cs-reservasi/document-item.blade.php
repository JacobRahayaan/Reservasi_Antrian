@props(['dokumen', 'reservasi'])

@php
    $isGambar = str_starts_with($dokumen->mime_type, 'image/');
    $ukuranFormat = \Illuminate\Support\Number::fileSize($dokumen->ukuran_file, precision: 1);
@endphp

<div class="flex items-center gap-3 rounded-lg border border-pln-slate-200 bg-white px-4 py-3">
    <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-pln-navy-900/5 text-pln-navy-700">
        <x-icon :name="$isGambar ? 'document-text' : 'document-text'" class="h-4 w-4" />
    </span>

    <div class="min-w-0 flex-1">
        <p class="truncate text-sm font-medium text-pln-slate-900">{{ $dokumen->nama_file_asli }}</p>
        <p class="text-xs text-pln-slate-400">{{ $ukuranFormat }}</p>
    </div>

    <a
        href="{{ route('reservasi.dokumen.preview', ['reservasi' => $reservasi, 'dokumen' => $dokumen]) }}"
        target="_blank"
        rel="noopener"
        class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg text-pln-slate-500 transition hover:bg-pln-slate-100 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-pln-amber-500"
        aria-label="Preview {{ $dokumen->nama_file_asli }}"
    >
        <x-icon name="eye" class="h-4 w-4" />
    </a>

    <a
        href="{{ route('reservasi.dokumen.download', ['reservasi' => $reservasi, 'dokumen' => $dokumen]) }}"
        class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg text-pln-navy-700 transition hover:bg-pln-slate-100 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-pln-amber-500"
        aria-label="Unduh {{ $dokumen->nama_file_asli }}"
    >
        <x-icon name="download" class="h-4 w-4" />
    </a>
</div>