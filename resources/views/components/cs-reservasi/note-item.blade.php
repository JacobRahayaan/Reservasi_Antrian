@props(['note'])

<div class="rounded-lg border border-pln-slate-200 p-4">
    <div class="flex items-start gap-3">
        <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-pln-navy-900 text-xs font-semibold text-white">
            {{ $note->petugas ? mb_substr($note->petugas->nama_petugas, 0, 2) : 'CS' }}
        </span>
        <div class="min-w-0 flex-1">
            <div class="flex flex-wrap items-baseline justify-between gap-x-3 gap-y-1">
                <p class="text-sm font-semibold text-pln-navy-900">{{ $note->petugas?->nama_petugas ?? 'Sistem' }}</p>
                <p class="text-xs text-pln-slate-400">{{ $note->created_at->translatedFormat('d M Y - H:i') }} WIB</p>
            </div>
            <p class="text-xs text-pln-slate-400">Customer Service</p>
            <p class="mt-1.5 text-sm leading-relaxed text-pln-slate-700">{{ $note->isi_catatan }}</p>
        </div>
    </div>
</div>