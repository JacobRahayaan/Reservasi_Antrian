@props(['jadwal'])

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

<div class="rounded-xl border border-pln-slate-200 bg-white p-4">
    <div class="flex items-start justify-between gap-3">
        <div>
            <p class="text-sm font-semibold text-pln-navy-900">
                {{ substr($jadwal->jam_mulai, 0, 5) }} - {{ substr($jadwal->jam_selesai, 0, 5) }}
            </p>
            <p class="mt-0.5 text-xs text-pln-slate-500">{{ $jadwal->layanan->nama_layanan }}</p>
        </div>

        <div class="flex items-center gap-1.5">
            <a href="{{ route('admin.jadwal.show', $jadwal) }}" class="flex h-8 w-8 items-center justify-center rounded-lg border border-pln-slate-200 text-pln-slate-500" aria-label="Detail">
                <x-icon name="eye" class="h-4 w-4" />
            </a>
            <a href="{{ route('admin.jadwal.edit', $jadwal) }}" class="flex h-8 w-8 items-center justify-center rounded-lg border border-pln-navy-200 text-pln-navy-700" aria-label="Ubah">
                <x-icon name="pencil-square" class="h-4 w-4" />
            </a>
            <button type="button" data-modal-target="modal-hapus-mobile-{{ $jadwal->id }}" class="flex h-8 w-8 items-center justify-center rounded-lg border border-status-cancel/30 text-status-cancel" aria-label="Hapus">
                <x-icon name="trash" class="h-4 w-4" />
            </button>
        </div>
    </div>

    <div class="mt-3 grid grid-cols-3 gap-2 text-center">
        <div class="rounded-lg bg-pln-slate-50 py-2">
            <p class="text-sm font-semibold text-pln-navy-900">{{ $jadwal->kuota_maksimal }}</p>
            <p class="text-[11px] text-pln-slate-400">Kuota</p>
        </div>
        <div class="rounded-lg bg-pln-slate-50 py-2">
            <p class="text-sm font-semibold text-pln-navy-900">{{ $jadwal->kuota_terpakai }}</p>
            <p class="text-[11px] text-pln-slate-400">Terisi</p>
        </div>
        <div class="rounded-lg bg-pln-slate-50 py-2">
            <p class="text-sm font-semibold text-pln-navy-900">{{ $jadwal->sisaKuota() }}</p>
            <p class="text-[11px] text-pln-slate-400">Sisa</p>
        </div>
    </div>

    <div class="mt-3">
        <button type="button" data-modal-target="modal-toggle-mobile-{{ $jadwal->id }}">
            <x-badge :variant="$badgeVariant">{{ $badgeLabel }}</x-badge>
        </button>
    </div>
</div>

<x-modal id="modal-toggle-mobile-{{ $jadwal->id }}" :title="($jadwal->is_active ? 'Nonaktifkan' : 'Aktifkan') . ' Jadwal'" size="sm">
    <p class="text-sm leading-relaxed text-pln-slate-600">
        @if ($jadwal->is_active)
            Menonaktifkan slot ini akan menyembunyikannya dari Form Reservasi pelanggan.
        @else
            Mengaktifkan slot ini akan membuatnya kembali tersedia di Form Reservasi.
        @endif
    </p>
    <x-slot:footer>
        <button type="button" data-modal-close class="rounded-lg border border-pln-slate-300 px-4 py-2 text-sm font-medium text-pln-slate-600">Batal</button>
        <form action="{{ route('admin.jadwal.toggle-status', $jadwal) }}" method="POST">
            @csrf
            @method('PATCH')
            <button type="submit" @class(['rounded-lg px-4 py-2 text-sm font-semibold text-white', 'bg-status-cancel' => $jadwal->is_active, 'bg-status-done' => ! $jadwal->is_active])>
                {{ $jadwal->is_active ? 'Ya, Nonaktifkan' : 'Ya, Aktifkan' }}
            </button>
        </form>
    </x-slot:footer>
</x-modal>

<x-modal id="modal-hapus-mobile-{{ $jadwal->id }}" title="Hapus Jadwal" size="sm">
    <p class="text-sm leading-relaxed text-pln-slate-600">
        @if ($jadwal->kuota_terpakai > 0)
            Slot ini sudah memiliki reservasi dan tidak dapat dihapus. Gunakan tombol nonaktifkan sebagai gantinya.
        @else
            Yakin ingin menghapus slot ini? Belum pernah digunakan reservasi apa pun sehingga akan dihapus permanen.
        @endif
    </p>
    <x-slot:footer>
        <button type="button" data-modal-close class="rounded-lg border border-pln-slate-300 px-4 py-2 text-sm font-medium text-pln-slate-600">Tutup</button>
        @if ($jadwal->kuota_terpakai === 0)
            <form action="{{ route('admin.jadwal.destroy', $jadwal) }}" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit" class="rounded-lg bg-status-cancel px-4 py-2 text-sm font-semibold text-white">Ya, Hapus</button>
            </form>
        @endif
    </x-slot:footer>
</x-modal>