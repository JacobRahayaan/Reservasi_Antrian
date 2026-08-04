@props(['layanan', 'nomor'])

@php
    $ikonLayanan = match ($layanan->kode_layanan) {
        'A' => [
            'icon' => 'bolt',
            'bg' => 'bg-pln-amber-500/10',
            'color' => 'text-pln-amber-600',
        ],
        'B' => [
            'icon' => 'document-text',
            'bg' => 'bg-pln-navy-600/10',
            'color' => 'text-pln-navy-700',
        ],
        default => [
            'icon' => 'wrench-screwdriver',
            'bg' => 'bg-status-done/10',
            'color' => 'text-status-done',
        ],
    };
@endphp

<tr class="border-b border-pln-slate-100 last:border-0">

    <td class="whitespace-nowrap px-4 py-4 text-sm text-pln-slate-500">
        {{ $nomor }}
    </td>

    <td class="px-4 py-4">
        <div class="flex items-center gap-3">
            <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full {{ $ikonLayanan['bg'] }} {{ $ikonLayanan['color'] }}">
                <x-icon :name="$ikonLayanan['icon']" class="h-4 w-4"/>
            </span>

            <span class="text-sm font-semibold text-pln-navy-900">
                {{ $layanan->nama_layanan }}
            </span>
        </div>
    </td>

    <td class="max-w-xs px-4 py-4 text-sm text-pln-slate-600">
        <p class="line-clamp-2">
            {{ $layanan->deskripsi ?: '-' }}
        </p>
    </td>

    <td class="whitespace-nowrap px-4 py-4 text-sm text-pln-slate-600">
        {{ $layanan->estimasi_waktu_label ?? '-' }}
    </td>

    <td class="whitespace-nowrap px-4 py-4">

        <button
            type="button"
            data-modal-target="modal-toggle-{{ $layanan->id }}"
            class="inline-flex"
        >
            <x-badge
                :variant="$layanan->is_active ? 'done' : 'neutral'"
                class="cursor-pointer"
            >
                {{ $layanan->is_active ? 'Aktif' : 'Nonaktif' }}
            </x-badge>
        </button>

    </td>

    <td class="whitespace-nowrap px-4 py-4 text-sm text-pln-slate-500">
        {{ $layanan->created_at?->translatedFormat('d M Y H:i') ?? '-' }}
    </td>

    <td class="whitespace-nowrap px-4 py-4">

        <div class="flex items-center gap-2">

            <a
                href="{{ route('admin.layanan.show', $layanan) }}"
                class="flex h-8 w-8 items-center justify-center rounded-lg border border-pln-slate-200 text-pln-slate-500 transition hover:bg-pln-slate-100"
                aria-label="Lihat detail {{ $layanan->nama_layanan }}"
            >
                <x-icon name="eye" class="h-4 w-4"/>
            </a>

            <a
                href="{{ route('admin.layanan.edit', $layanan) }}"
                class="flex h-8 w-8 items-center justify-center rounded-lg border border-pln-navy-200 text-pln-navy-700 transition hover:bg-pln-navy-50"
                aria-label="Edit {{ $layanan->nama_layanan }}"
            >
                <x-icon name="pencil-square" class="h-4 w-4"/>
            </a>

            <button
                type="button"
                data-modal-target="modal-hapus-{{ $layanan->id }}"
                class="flex h-8 w-8 items-center justify-center rounded-lg border border-status-cancel/30 text-status-cancel transition hover:bg-status-cancel/5"
                aria-label="Hapus {{ $layanan->nama_layanan }}"
            >
                <x-icon name="trash" class="h-4 w-4"/>
            </button>

        </div>

    </td>

</tr>

{{-- ===================== --}}
{{-- Modal Toggle Status --}}
{{-- ===================== --}}

<x-modal
    id="modal-toggle-{{ $layanan->id }}"
    :title="($layanan->is_active ? 'Nonaktifkan' : 'Aktifkan').' Layanan'"
    size="sm"
>

    <p class="text-sm leading-relaxed text-pln-slate-600">

        @if($layanan->is_active)

            Menonaktifkan layanan
            <strong>{{ $layanan->nama_layanan }}</strong>
            akan menyembunyikannya dari Form Reservasi pelanggan.
            Reservasi yang sudah dibuat tetap tidak berubah.

        @else

            Mengaktifkan layanan
            <strong>{{ $layanan->nama_layanan }}</strong>
            akan membuatnya kembali tersedia pada Form Reservasi pelanggan.

        @endif

    </p>

    <x-slot:footer>

        <button
            type="button"
            data-modal-close
            class="rounded-lg border border-pln-slate-300 px-4 py-2 text-sm font-medium text-pln-slate-600 hover:bg-pln-slate-50"
        >
            Batal
        </button>

        <form
            action="{{ route('admin.layanan.toggle-status',$layanan) }}"
            method="POST"
        >
            @csrf
            @method('PATCH')

            <button
                type="submit"
                @class([
                    'rounded-lg px-4 py-2 text-sm font-semibold text-white hover:opacity-90',
                    'bg-status-cancel'=>$layanan->is_active,
                    'bg-status-done'=>!$layanan->is_active,
                ])
            >
                {{ $layanan->is_active ? 'Ya, Nonaktifkan' : 'Ya, Aktifkan' }}
            </button>

        </form>

    </x-slot:footer>

</x-modal>

{{-- ===================== --}}
{{-- Modal Delete --}}
{{-- ===================== --}}

<x-modal
    id="modal-hapus-{{ $layanan->id }}"
    title="Hapus Layanan"
    size="sm"
>

    <p class="text-sm leading-relaxed text-pln-slate-600">

        Yakin ingin menghapus layanan
        <strong>{{ $layanan->nama_layanan }}</strong>?

        @if($layanan->pernahDigunakan())

            Layanan ini sudah memiliki riwayat reservasi sehingga
            akan <strong>dinonaktifkan dan disembunyikan</strong>
            (soft delete). Seluruh riwayat reservasi tetap aman.

        @else

            Layanan ini belum pernah digunakan sehingga akan
            <strong>dihapus permanen</strong>.

        @endif

    </p>

    <x-slot:footer>

        <button
            type="button"
            data-modal-close
            class="rounded-lg border border-pln-slate-300 px-4 py-2 text-sm font-medium text-pln-slate-600 hover:bg-pln-slate-50"
        >
            Batal
        </button>

        <form
            action="{{ route('admin.layanan.destroy',$layanan) }}"
            method="POST"
        >
            @csrf
            @method('DELETE')

            <button
                type="submit"
                class="rounded-lg bg-status-cancel px-4 py-2 text-sm font-semibold text-white hover:opacity-90"
            >
                Ya, Hapus
            </button>

        </form>

    </x-slot:footer>

</x-modal>