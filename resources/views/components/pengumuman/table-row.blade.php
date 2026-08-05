@props(['pengumuman', 'nomor'])

@php
    $badgeMap = [
        'aktif' => ['variant' => 'done', 'label' => 'Aktif'],
        'terjadwal' => ['variant' => 'review', 'label' => 'Terjadwal'],
        'berakhir' => ['variant' => 'cancel', 'label' => 'Berakhir'],
        'nonaktif' => ['variant' => 'neutral', 'label' => 'Nonaktif'],
    ];

    $badge = $badgeMap[$pengumuman->status_tampilan] ?? $badgeMap['nonaktif'];
@endphp

<tr class="border-b border-pln-slate-100 last:border-0">
    <td class="whitespace-nowrap px-4 py-4 text-sm text-pln-slate-500">{{ $nomor }}</td>

    <td class="max-w-sm px-4 py-4">
        <p class="text-sm font-semibold text-pln-navy-900">{{ $pengumuman->judul }}</p>
        <p class="mt-0.5 line-clamp-1 text-xs text-pln-slate-500">{{ $pengumuman->isi }}</p>
    </td>

    <td class="whitespace-nowrap px-4 py-4 text-sm text-pln-slate-600">
        {{ $pengumuman->tanggal_mulai->translatedFormat('d M Y') }}
    </td>

    <td class="whitespace-nowrap px-4 py-4 text-sm text-pln-slate-600">
        {{ $pengumuman->tanggal_selesai?->translatedFormat('d M Y') ?? 'Tanpa batas' }}
    </td>

    <td class="whitespace-nowrap px-4 py-4">
        <button type="button" data-modal-target="modal-toggle-pengumuman-{{ $pengumuman->id }}">
            <x-badge :variant="$badge['variant']" class="cursor-pointer">{{ $badge['label'] }}</x-badge>
        </button>
    </td>

    <td class="whitespace-nowrap px-4 py-4">
        <div class="flex items-center gap-2">
            
                href="{{ route('admin.pengumuman.show', $pengumuman) }}"
                class="flex h-8 w-8 items-center justify-center rounded-lg border border-pln-slate-200 text-pln-slate-500 transition hover:bg-pln-slate-100"
                aria-label="Lihat detail"
            >
                <x-icon name="eye" class="h-4 w-4" />
            </a>
            
                href="{{ route('admin.pengumuman.edit', $pengumuman) }}"
                class="flex h-8 w-8 items-center justify-center rounded-lg border border-pln-navy-200 text-pln-navy-700 transition hover:bg-pln-navy-50"
                aria-label="Ubah"
            >
                <x-icon name="pencil-square" class="h-4 w-4" />
            </a>
            <button
                type="button"
                data-modal-target="modal-hapus-pengumuman-{{ $pengumuman->id }}"
                class="flex h-8 w-8 items-center justify-center rounded-lg border border-status-cancel/30 text-status-cancel transition hover:bg-status-cancel/5"
                aria-label="Hapus"
            >
                <x-icon name="trash" class="h-4 w-4" />
            </button>
        </div>
    </td>
</tr>

<x-modal id="modal-toggle-pengumuman-{{ $pengumuman->id }}" :title="($pengumuman->is_active ? 'Nonaktifkan' : 'Aktifkan') . ' Pengumuman'" size="sm">
    <p class="text-sm leading-relaxed text-pln-slate-600">
        @if ($pengumuman->is_active)
            Menonaktifkan pengumuman <strong>{{ $pengumuman->judul }}</strong> akan menyembunyikannya dari tampilan publik, terlepas dari tanggal tayangnya.
        @else
            Mengaktifkan pengumuman <strong>{{ $pengumuman->judul }}</strong> akan membuatnya kembali tayang jika tanggalnya masih berlaku.
        @endif
    </p>

    <x-slot:footer>
        <button type="button" data-modal-close class="rounded-lg border border-pln-slate-300 px-4 py-2 text-sm font-medium text-pln-slate-600 hover:bg-pln-slate-50">
            Batal
        </button>
        <form action="{{ route('admin.pengumuman.toggle-status', $pengumuman) }}" method="POST">
            @csrf
            @method('PATCH')
            <button
                type="submit"
                @class(['rounded-lg px-4 py-2 text-sm font-semibold text-white', 'bg-status-cancel hover:opacity-90' => $pengumuman->is_active, 'bg-status-done hover:opacity-90' => ! $pengumuman->is_active])
            >
                {{ $pengumuman->is_active ? 'Ya, Nonaktifkan' : 'Ya, Aktifkan' }}
            </button>
        </form>
    </x-slot:footer>
</x-modal>

<x-modal id="modal-hapus-pengumuman-{{ $pengumuman->id }}" title="Hapus Pengumuman" size="sm">
    <p class="text-sm leading-relaxed text-pln-slate-600">
        Yakin ingin menghapus pengumuman <strong>{{ $pengumuman->judul }}</strong>? Tindakan ini tidak dapat dibatalkan.
    </p>

    <x-slot:footer>
        <button type="button" data-modal-close class="rounded-lg border border-pln-slate-300 px-4 py-2 text-sm font-medium text-pln-slate-600 hover:bg-pln-slate-50">
            Batal
        </button>
        <form action="{{ route('admin.pengumuman.destroy', $pengumuman) }}" method="POST">
            @csrf
            @method('DELETE')
            <button type="submit" class="rounded-lg bg-status-cancel px-4 py-2 text-sm font-semibold text-white hover:opacity-90">
                Ya, Hapus
            </button>
        </form>
    </x-slot:footer>
</x-modal>