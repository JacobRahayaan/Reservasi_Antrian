@props(['jadwal', 'groupId'])

@php
    $badgeVariant = match ($jadwal->status_tampilan) {
        'aktif' => 'done',
        'penuh' => 'cancel',
        default => 'neutral',
    };

    $badgeLabel = match ($jadwal->status_tampilan) {
        'aktif' => 'Aktif',
        'penuh' => 'Penuh',
        default => 'Nonaktif',
    };
@endphp

<tr id="{{ $groupId }}" class="border-b border-pln-slate-100 last:border-0">
    <td class="whitespace-nowrap px-4 py-3 text-sm text-pln-slate-700">
        <span class="flex items-center gap-2">
            <x-icon name="clock" class="h-4 w-4 text-pln-slate-400" />
            {{ substr($jadwal->jam_mulai, 0, 5) }} - {{ substr($jadwal->jam_selesai, 0, 5) }}
        </span>
    </td>
    <td class="whitespace-nowrap px-4 py-3 text-sm text-pln-slate-700">{{ $jadwal->layanan->nama_layanan }}</td>
    <td class="whitespace-nowrap px-4 py-3 text-sm text-pln-slate-700">{{ $jadwal->kuota_maksimal }}</td>
    <td class="whitespace-nowrap px-4 py-3 text-sm text-pln-slate-700">{{ $jadwal->kuota_terpakai }}</td>
    <td class="whitespace-nowrap px-4 py-3 text-sm font-semibold text-pln-navy-900">{{ $jadwal->sisaKuota() }}</td>
    <td class="whitespace-nowrap px-4 py-3">
        <button type="button" data-modal-target="modal-toggle-{{ $jadwal->id }}">
            <x-badge :variant="$badgeVariant" class="cursor-pointer">{{ $badgeLabel }}</x-badge>
        </button>
    </td>
    <td class="whitespace-nowrap px-4 py-3">
        <div class="flex items-center gap-2">
            <a
                href="{{ route('admin.jadwal.show', $jadwal) }}"
                class="flex h-8 w-8 items-center justify-center rounded-lg border border-pln-slate-200 text-pln-slate-500 transition hover:bg-pln-slate-100"
                aria-label="Lihat detail jadwal"
            >
                <x-icon name="eye" class="h-4 w-4" />
            </a>
            <a
                href="{{ route('admin.jadwal.edit', $jadwal) }}"
                class="flex h-8 w-8 items-center justify-center rounded-lg border border-pln-navy-200 text-pln-navy-700 transition hover:bg-pln-navy-50"
                aria-label="Ubah jadwal"
            >
                <x-icon name="pencil-square" class="h-4 w-4" />
            </a>
            <button
                type="button"
                data-modal-target="modal-hapus-{{ $jadwal->id }}"
                class="flex h-8 w-8 items-center justify-center rounded-lg border border-status-cancel/30 text-status-cancel transition hover:bg-status-cancel/5"
                aria-label="Hapus jadwal"
            >
                <x-icon name="trash" class="h-4 w-4" />
            </button>
        </div>
    </td>
</tr>

<x-modal id="modal-toggle-{{ $jadwal->id }}" :title="($jadwal->is_active ? 'Nonaktifkan' : 'Aktifkan') . ' Jadwal'" size="sm">
    <p class="text-sm leading-relaxed text-pln-slate-600">
        @if ($jadwal->is_active)
            Menonaktifkan slot ini akan menyembunyikannya dari Form Reservasi pelanggan. Reservasi yang sudah ada tidak akan terpengaruh.
        @else
            Mengaktifkan slot ini akan membuatnya kembali muncul di Form Reservasi pelanggan (jika masih ada sisa kuota).
        @endif
    </p>

    <x-slot:footer>
        <button type="button" data-modal-close class="rounded-lg border border-pln-slate-300 px-4 py-2 text-sm font-medium text-pln-slate-600 hover:bg-pln-slate-50">
            Batal
        </button>
        <form action="{{ route('admin.jadwal.toggle-status', $jadwal) }}" method="POST">
            @csrf
            @method('PATCH')
            <button
                type="submit"
                @class(['rounded-lg px-4 py-2 text-sm font-semibold text-white', 'bg-status-cancel hover:opacity-90' => $jadwal->is_active, 'bg-status-done hover:opacity-90' => ! $jadwal->is_active])
            >
                {{ $jadwal->is_active ? 'Ya, Nonaktifkan' : 'Ya, Aktifkan' }}
            </button>
        </form>
    </x-slot:footer>
</x-modal>

<x-modal id="modal-hapus-{{ $jadwal->id }}" title="Hapus Jadwal" size="sm">
    <p class="text-sm leading-relaxed text-pln-slate-600">
        @if ($jadwal->kuota_terpakai > 0)
            Slot ini sudah memiliki <strong>{{ $jadwal->kuota_terpakai }} reservasi</strong> dan tidak dapat dihapus. Gunakan tombol nonaktifkan sebagai gantinya.
        @else
            Yakin ingin menghapus slot <strong>{{ substr($jadwal->jam_mulai, 0, 5) }} - {{ substr($jadwal->jam_selesai, 0, 5) }}</strong> untuk layanan <strong>{{ $jadwal->layanan->nama_layanan }}</strong>? Slot ini belum pernah digunakan reservasi apa pun sehingga akan dihapus permanen.
        @endif
    </p>

    <x-slot:footer>
        <button type="button" data-modal-close class="rounded-lg border border-pln-slate-300 px-4 py-2 text-sm font-medium text-pln-slate-600 hover:bg-pln-slate-50">
            Tutup
        </button>
        @if ($jadwal->kuota_terpakai === 0)
            <form action="{{ route('admin.jadwal.destroy', $jadwal) }}" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit" class="rounded-lg bg-status-cancel px-4 py-2 text-sm font-semibold text-white hover:opacity-90">
                    Ya, Hapus
                </button>
            </form>
        @endif
    </x-slot:footer>
</x-modal>