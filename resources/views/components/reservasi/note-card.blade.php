@props(['note' => null])

@if ($note)
    <div class="rounded-xl bg-pln-amber-500/10 p-4">
        <p class="text-sm leading-relaxed text-pln-navy-900">{{ $note->isi_catatan }}</p>
    </div>

    <div class="mt-3 flex items-center gap-2.5">
        <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-pln-navy-900 text-xs font-semibold text-white">
            CS
        </span>
        <div>
            <p class="text-xs font-semibold text-pln-navy-900">Customer Service</p>
            <p class="text-xs text-pln-slate-400">{{ $note->created_at->translatedFormat('d M Y - H:i') }} WIB</p>
        </div>
    </div>
@else
    <x-empty-state
        title="Reservasi sedang ditinjau"
        description="Belum ada catatan dari Customer Service. Catatan akan tampil di sini setelah reservasi Anda direview."
    />
@endif