@props(['petugas'])

<div class="rounded-xl border border-pln-slate-200 bg-white p-4">
    <div class="flex items-start justify-between gap-3">
        <div class="flex items-center gap-3">
            <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-pln-navy-900 text-sm font-semibold text-white">
                {{ mb_substr($petugas->nama_petugas, 0, 2) }}
            </span>
            <div>
                <p class="text-sm font-semibold text-pln-navy-900">{{ $petugas->nama_petugas }}</p>
                <p class="text-xs text-pln-slate-400">{{ $petugas->email }}</p>
            </div>
        </div>

        <div class="flex items-center gap-1.5">
            <a href="{{ route('admin.pengguna.show', $petugas) }}" class="flex h-8 w-8 items-center justify-center rounded-lg border border-pln-slate-200 text-pln-slate-500" aria-label="Detail">
                <x-icon name="eye" class="h-4 w-4" />
            </a>
            <a href="{{ route('admin.pengguna.edit', $petugas) }}" class="flex h-8 w-8 items-center justify-center rounded-lg border border-pln-navy-200 text-pln-navy-700" aria-label="Ubah">
                <x-icon name="pencil-square" class="h-4 w-4" />
            </a>
            <button type="button" data-modal-target="modal-hapus-petugas-mobile-{{ $petugas->id }}" class="flex h-8 w-8 items-center justify-center rounded-lg border border-status-cancel/30 text-status-cancel" aria-label="Hapus">
                <x-icon name="trash" class="h-4 w-4" />
            </button>
        </div>
    </div>

    <div class="mt-3 flex items-center justify-between">
        <span class="text-xs text-pln-slate-400">{{ $petugas->no_hp ?? '-' }}</span>
        <button type="button" data-modal-target="modal-toggle-petugas-mobile-{{ $petugas->id }}">
            <x-badge :variant="$petugas->is_active ? 'done' : 'neutral'">
                {{ $petugas->is_active ? 'Aktif' : 'Nonaktif' }}
            </x-badge>
        </button>
    </div>
</div>

<x-modal id="modal-toggle-petugas-mobile-{{ $petugas->id }}" :title="($petugas->is_active ? 'Nonaktifkan' : 'Aktifkan') . ' Petugas'" size="sm">
    <p class="text-sm leading-relaxed text-pln-slate-600">
        @if ($petugas->is_active)
            Menonaktifkan petugas ini akan mencegahnya dianggap sebagai petugas aktif oleh sistem.
        @else
            Mengaktifkan petugas ini akan membuatnya kembali dianggap aktif oleh sistem.
        @endif
    </p>
    <x-slot:footer>
        <button type="button" data-modal-close class="rounded-lg border border-pln-slate-300 px-4 py-2 text-sm font-medium text-pln-slate-600">Batal</button>
        <form action="{{ route('admin.pengguna.toggle-status', $petugas) }}" method="POST">
            @csrf
            @method('PATCH')
            <button type="submit" @class(['rounded-lg px-4 py-2 text-sm font-semibold text-white', 'bg-status-cancel' => $petugas->is_active, 'bg-status-done' => ! $petugas->is_active])>
                {{ $petugas->is_active ? 'Ya, Nonaktifkan' : 'Ya, Aktifkan' }}
            </button>
        </form>
    </x-slot:footer>
</x-modal>

<x-modal id="modal-hapus-petugas-mobile-{{ $petugas->id }}" title="Hapus Petugas" size="sm">
    <p class="text-sm leading-relaxed text-pln-slate-600">
        @if ($petugas->pernahBertindak())
            Petugas ini memiliki riwayat aktivitas dan tidak dapat dihapus. Nonaktifkan sebagai gantinya.
        @else
            Yakin ingin menghapus petugas ini? Belum pernah bertindak di sistem sehingga akan dihapus permanen.
        @endif
    </p>
    <x-slot:footer>
        <button type="button" data-modal-close class="rounded-lg border border-pln-slate-300 px-4 py-2 text-sm font-medium text-pln-slate-600">Tutup</button>
        @unless ($petugas->pernahBertindak())
            <form action="{{ route('admin.pengguna.destroy', $petugas) }}" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit" class="rounded-lg bg-status-cancel px-4 py-2 text-sm font-semibold text-white">Ya, Hapus</button>
            </form>
        @endunless
    </x-slot:footer>
</x-modal>