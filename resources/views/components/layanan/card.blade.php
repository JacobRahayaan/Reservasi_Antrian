@props(['layanan'])

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

<div class="rounded-xl border border-pln-slate-200 bg-white p-4">

    <div class="flex items-start justify-between gap-3">

        <div class="flex items-center gap-3">
            <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full {{ $ikonLayanan['bg'] }} {{ $ikonLayanan['color'] }}">
                <x-icon :name="$ikonLayanan['icon']" class="h-5 w-5" />
            </span>

            <div>
                <p class="text-sm font-semibold text-pln-navy-900">
                    {{ $layanan->nama_layanan }}
                </p>

                <p class="text-xs text-pln-slate-400">
                    Prefix: {{ $layanan->kode_layanan }}
                </p>
            </div>
        </div>

        <div class="flex items-center gap-1.5">

            <a
                href="{{ route('admin.layanan.show', $layanan) }}"
                class="flex h-8 w-8 items-center justify-center rounded-lg border border-pln-slate-200 text-pln-slate-500 transition hover:bg-pln-slate-100"
                aria-label="Lihat detail"
            >
                <x-icon name="eye" class="h-4 w-4" />
            </a>

            <a
                href="{{ route('admin.layanan.edit', $layanan) }}"
                class="flex h-8 w-8 items-center justify-center rounded-lg border border-pln-navy-200 text-pln-navy-700 transition hover:bg-pln-navy-50"
                aria-label="Ubah"
            >
                <x-icon name="pencil-square" class="h-4 w-4" />
            </a>

            <button
                type="button"
                data-modal-target="modal-hapus-mobile-{{ $layanan->id }}"
                class="flex h-8 w-8 items-center justify-center rounded-lg border border-status-cancel/30 text-status-cancel transition hover:bg-status-cancel/5"
                aria-label="Hapus"
            >
                <x-icon name="trash" class="h-4 w-4" />
            </button>

        </div>

    </div>

    <p class="mt-3 line-clamp-2 text-xs text-pln-slate-500">
        {{ $layanan->deskripsi ?? '-' }}
    </p>

    <div class="mt-3 flex items-center justify-between">

        <span class="text-xs text-pln-slate-400">
            {{ $layanan->estimasi_waktu_label ?? '-' }}
        </span>

        <button
            type="button"
            data-modal-target="modal-toggle-mobile-{{ $layanan->id }}"
        >
            <x-badge :variant="$layanan->is_active ? 'done' : 'neutral'">
                {{ $layanan->is_active ? 'Aktif' : 'Nonaktif' }}
            </x-badge>
        </button>

    </div>

</div>

{{-- ===========================
    MODAL TOGGLE STATUS
=========================== --}}

<x-modal
    id="modal-toggle-mobile-{{ $layanan->id }}"
    :title="($layanan->is_active ? 'Nonaktifkan' : 'Aktifkan') . ' Layanan'"
    size="sm"
>

    <p class="text-sm leading-relaxed text-pln-slate-600">
        @if ($layanan->is_active)
            Menonaktifkan layanan
            <strong>{{ $layanan->nama_layanan }}</strong>
            akan menyembunyikannya dari Form Reservasi pelanggan.
        @else
            Mengaktifkan layanan
            <strong>{{ $layanan->nama_layanan }}</strong>
            akan membuatnya kembali tersedia di Form Reservasi.
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
            action="{{ route('admin.layanan.toggle-status', $layanan) }}"
            method="POST"
        >
            @csrf
            @method('PATCH')

            <button
                type="submit"
                @class([
                    'rounded-lg px-4 py-2 text-sm font-semibold text-white',
                    'bg-status-cancel hover:opacity-90' => $layanan->is_active,
                    'bg-status-done hover:opacity-90' => ! $layanan->is_active,
                ])
            >
                {{ $layanan->is_active ? 'Ya, Nonaktifkan' : 'Ya, Aktifkan' }}
            </button>

        </form>

    </x-slot:footer>

</x-modal>

{{-- ===========================
    MODAL HAPUS
=========================== --}}

<x-modal
    id="modal-hapus-mobile-{{ $layanan->id }}"
    title="Hapus Layanan"
    size="sm"
>

    <p class="text-sm leading-relaxed text-pln-slate-600">

        Yakin ingin menghapus layanan
        <strong>{{ $layanan->nama_layanan }}</strong>?

        @if (($layanan->reservasis_count ?? 0) > 0)

            Layanan ini memiliki
            <strong>{{ $layanan->reservasis_count }}</strong>
            riwayat reservasi sehingga akan
            <strong>disembunyikan dan dinonaktifkan</strong>.

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
            action="{{ route('admin.layanan.destroy', $layanan) }}"
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