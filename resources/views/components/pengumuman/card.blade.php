@props(['pengumuman'])

@php
    $badgeMap = [
        'aktif' => ['variant' => 'done', 'label' => 'Aktif'],
        'terjadwal' => ['variant' => 'review', 'label' => 'Terjadwal'],
        'berakhir' => ['variant' => 'cancel', 'label' => 'Berakhir'],
        'nonaktif' => ['variant' => 'neutral', 'label' => 'Nonaktif'],
    ];

    $badge = $badgeMap[$pengumuman->status_tampilan] ?? $badgeMap['nonaktif'];
@endphp

<div class="rounded-xl border border-pln-slate-200 bg-white p-4">
    <div class="flex items-start justify-between gap-3">
        <div class="min-w-0">
            <p class="text-sm font-semibold text-pln-navy-900">{{ $pengumuman->judul }}</p>
            <p class="mt-0.5 line-clamp-2 text-xs text-pln-slate-500">{{ $pengumuman->isi }}</p>
        </div>

        <div class="flex shrink-0 items-center gap-1.5">
            <a href="{{ route('admin.pengumuman.show', $pengumuman) }}" class="flex h-8 w-8 items-center justify-center rounded-lg border border-pln-slate-200 text-pln-slate-500" aria-label="Detail">
                <x-icon name="eye" class="h-4 w-4" />
            </a>
            <a href="{{ route('admin.pengumuman.edit', $pengumuman) }}" class="flex h-8 w-8 items-center justify-center rounded-lg border border-pln-navy-200 text-pln-navy-700" aria-label="Ubah">
                <x-icon name="pencil-square" class="h-4 w-4" />
            </a>
            <button type="button" data-modal-target="modal-hapus-pengumuman-mobile-{{ $pengumuman->id }}" class="flex h-8 w-8 items-center justify-center rounded-lg border border-status-cancel/30 text-status-cancel" aria-label="Hapus">
                <x-icon name="trash" class="h-4 w-4" />
            </button>
        </div>
    </div>

    <div class="mt-3 flex items-center justify-between">
        <span class="text-xs text-pln-slate-400">
            {{ $pengumuman->tanggal_mulai->translatedFormat('d M Y') }} -
            {{ $pengumuman->tanggal_selesai?->translatedFormat('d M Y') ?? 'Tanpa batas' }}
        </span>
        <button type="button" data-modal-target="modal-toggle-pengumuman-mobile-{{ $pengumuman->id }}">
            <x-badge :variant="$badge['variant']">{{ $badge['label'] }}</x-badge>
        </button>
    </div>
</div>

<x-modal id="modal-toggle-pengumuman-mobile-{{ $pengumuman->id }}" :title="($pengumuman->is_active ? 'Nonaktifkan' : 'Aktifkan') . ' Pengumuman'" size="sm">
    <p class="text-sm leading-relaxed text-pln-slate-600">
        @if ($pengumuman->is_active)
            Menonaktifkan pengumuman ini akan menyembunyikannya dari tampilan publik.
        @else
            Mengaktifkan pengumuman ini akan membuatnya kembali tayang jika tanggalnya masih berlaku.
        @endif
    </p>
    <x-slot:footer>
        <button type="button" data-modal-close class="rounded-lg border border-pln-slate-300 px-4 py-2 text-sm font-medium text-pln-slate-600">Batal</button>
        <form action="{{ route('admin.pengumuman.toggle-status', $pengumuman) }}" method="POST">
            @csrf
            @method('PATCH')
            <button type="submit" @class(['rounded-lg px-4 py-2 text-sm font-semibold text-white', 'bg-status-cancel' => $pengumuman->is_active, 'bg-status-done' => ! $pengumuman->is_active])>
                {{ $pengumuman->is_active ? 'Ya, Nonaktifkan' : 'Ya, Aktifkan' }}
            </button>
        </form>
    </x-slot:footer>
</x-modal>

<x-modal id="modal-hapus-pengumuman-mobile-{{ $pengumuman->id }}" title="Hapus Pengumuman" size="sm">
    <p class="text-sm leading-relaxed text-pln-slate-600">
        Yakin ingin menghapus pengumuman ini? Tindakan ini tidak dapat dibatalkan.
    </p>
    <x-slot:footer>
        <button type="button" data-modal-close class="rounded-lg border border-pln-slate-300 px-4 py-2 text-sm font-medium text-pln-slate-600">Batal</button>
        <form action="{{ route('admin.pengumuman.destroy', $pengumuman) }}" method="POST">
            @csrf
            @method('DELETE')
            <button type="submit" class="rounded-lg bg-status-cancel px-4 py-2 text-sm font-semibold text-white">Ya, Hapus</button>
        </form>
    </x-slot:footer>
</x-modal>