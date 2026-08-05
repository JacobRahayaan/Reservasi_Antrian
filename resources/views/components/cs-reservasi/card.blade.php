@props(['reservasi'])

@php
    $ikonLayanan = match ($reservasi->layanan->kode_layanan) {
        'A' => 'bolt',
        'B' => 'document-text',
        default => 'wrench-screwdriver',
    };

    $adaRouteDetail = Route::has('cs.reservasi.show');
    $petugas = $reservasi->statusHistories->first()?->petugas?->nama_petugas;
@endphp

<div class="rounded-xl border border-pln-slate-200 bg-white p-4">
    <div class="flex items-start justify-between gap-3">
        <div>
            <p class="font-mono text-sm font-bold text-pln-navy-900">{{ $reservasi->nomor_antrean }}</p>
            <p class="font-mono text-xs text-pln-slate-400">{{ $reservasi->kode_reservasi }}</p>
        </div>
        <x-badge :variant="$reservasi->status->badgeVariant()">{{ $reservasi->status->label() }}</x-badge>
    </div>

    <div class="mt-3">
        <p class="text-sm font-semibold text-pln-slate-900">{{ $reservasi->nama }}</p>
        <p class="text-xs text-pln-slate-400">{{ $reservasi->nomor_hp }}</p>
    </div>

    <div class="mt-3 space-y-1.5 text-xs text-pln-slate-500">
        <p class="flex items-center gap-1.5">
            <x-icon :name="$ikonLayanan" class="h-3.5 w-3.5" />
            {{ $reservasi->layanan->nama_layanan }}
        </p>
        <p class="flex items-center gap-1.5">
            <x-icon name="clock" class="h-3.5 w-3.5" />
            {{ $reservasi->jadwal->tanggal->translatedFormat('d M Y') }} &middot;
            {{ substr($reservasi->jadwal->jam_mulai, 0, 5) }} - {{ substr($reservasi->jadwal->jam_selesai, 0, 5) }}
        </p>
        @if ($petugas)
            <p class="flex items-center gap-1.5">
                <x-icon name="user" class="h-3.5 w-3.5" />
                Ditangani {{ $petugas }}
            </p>
        @endif
    </div>

    <div class="mt-3 border-t border-pln-slate-100 pt-3">
        @if ($adaRouteDetail)
            <a href="{{ route('cs.reservasi.show', $reservasi) }}" class="text-sm font-semibold text-pln-navy-700">
                Lihat Detail
            </a>
        @else
            <span class="text-sm font-semibold text-pln-slate-300" title="Halaman Detail Reservasi CS akan tersedia pada sprint berikutnya">
                Lihat Detail
            </span>
        @endif
    </div>
</div>