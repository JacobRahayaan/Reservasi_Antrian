@props(['petugas', 'nomor'])

<tr class="border-b border-pln-slate-100 last:border-0">
    <td class="whitespace-nowrap px-4 py-4 text-sm text-pln-slate-500">{{ $nomor }}</td>

    <td class="px-4 py-4">
        <div class="flex items-center gap-3">
            <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-pln-navy-900 text-xs font-semibold text-white">
                {{ mb_substr($petugas->nama_petugas, 0, 2) }}
            </span>
            <span class="text-sm font-semibold text-pln-navy-900">{{ $petugas->nama_petugas }}</span>
        </div>
    </td>

    <td class="whitespace-nowrap px-4 py-4 text-sm text-pln-slate-600">{{ $petugas->email }}</td>
    <td class="whitespace-nowrap px-4 py-4 text-sm text-pln-slate-600">{{ $petugas->no_hp ?? '-' }}</td>

    <td class="whitespace-nowrap px-4 py-4">
        <button type="button" data-modal-target="modal-toggle-petugas-{{ $petugas->id }}">
            <x-badge :variant="$petugas->is_active ? 'done' : 'neutral'" class="cursor-pointer">
                {{ $petugas->is_active ? 'Aktif' : 'Nonaktif' }}
            </x-badge>
        </button>
    </td>

    <td class="whitespace-nowrap px-4 py-4 text-sm text-pln-slate-500">
        {{ $petugas->created_at->translatedFormat('d M Y') }}
    </td>

    <td class="whitespace-nowrap px-4 py-4">
        <div class="flex items-center gap-2">
            <a
                href="{{ route('admin.pengguna.show', $petugas) }}"
                class="flex h-8 w-8 items-center justify-center rounded-lg border border-pln-slate-200 text-pln-slate-500 transition hover:bg-pln-slate-100"
                aria-label="Lihat detail"
            >
                <x-icon name="eye" class="h-4 w-4" />
            </a>
            <a
                href="{{ route('admin.pengguna.edit', $petugas) }}"
                class="flex h-8 w-8 items-center justify-center rounded-lg border border-pln-navy-200 text-pln-navy-700 transition hover:bg-pln-navy-50"
                aria-label="Ubah"
            >
                <x-icon name="pencil-square" class="h-4 w-4" />
            </a>
            <button
                type="button"
                data-modal-target="modal-hapus-petugas-{{ $petugas->id }}"
                class="flex h-8 w-8 items-center justify-center rounded-lg border border-status-cancel/30 text-status-cancel transition hover:bg-status-cancel/5"
                aria-label="Hapus"
            >
                <x-icon name="trash" class="h-4 w-4" />
            </button>
        </div>
    </td>
</tr>

<x-modal id="modal-toggle-petugas-{{ $petugas->id }}" :title="($petugas->is_active ? 'Nonaktifkan' : 'Aktifkan') . ' Petugas'" size="sm">
    <p class="text-sm leading-relaxed text-pln-slate-600">
        @if ($petugas->is_active)
            Menonaktifkan petugas <strong>{{ $petugas->nama_petugas }}</strong> akan mencegahnya dianggap sebagai petugas aktif oleh sistem (mis. simulasi login CS).
        @else
            Mengaktifkan petugas <strong>{{ $petugas->nama_petugas }}</strong> akan membuatnya kembali dianggap aktif oleh sistem.
        @endif
    </p>
    <x-slot:footer>
        <button type="button" data-modal-close class="rounded-lg border border-pln-slate-300 px-4 py-2 text-sm font-medium text-pln-slate-600 hover:bg-pln-slate-50">
            Batal
        </button>
        <form action="{{ route('admin.pengguna.toggle-status', $petugas) }}" method="POST">
            @csrf
            @method('PATCH')
            <button
                type="submit"
                @class(['rounded-lg px-4 py-2 text-sm font-semibold text-white', 'bg-status-cancel hover:opacity-90' => $petugas->is_active, 'bg-status-done hover:opacity-90' => ! $petugas->is_active])
            >
                {{ $petugas->is_active ? 'Ya, Nonaktifkan' : 'Ya, Aktifkan' }}
            </button>
        </form>
    </x-slot:footer>
</x-modal>

<x-modal id="modal-hapus-petugas-{{ $petugas->id }}" title="Hapus Petugas" size="sm">
    <p class="text-sm leading-relaxed text-pln-slate-600">
        @if ($petugas->pernahBertindak())
            Petugas <strong>{{ $petugas->nama_petugas }}</strong> memiliki riwayat aktivitas (catatan/perubahan status) dan
            <strong>tidak dapat dihapus</strong>. Nonaktifkan petugas ini sebagai gantinya.
        @else
            Yakin ingin menghapus petugas <strong>{{ $petugas->nama_petugas }}</strong>? Petugas ini belum pernah bertindak di sistem sehingga akan dihapus permanen.
        @endif
    </p>
    <x-slot:footer>
        <button type="button" data-modal-close class="rounded-lg border border-pln-slate-300 px-4 py-2 text-sm font-medium text-pln-slate-600 hover:bg-pln-slate-50">
            Tutup
        </button>
        @unless ($petugas->pernahBertindak())
            <form action="{{ route('admin.pengguna.destroy', $petugas) }}" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit" class="rounded-lg bg-status-cancel px-4 py-2 text-sm font-semibold text-white hover:opacity-90">
                    Ya, Hapus
                </button>
            </form>
        @endunless
    </x-slot:footer>
</x-modal>