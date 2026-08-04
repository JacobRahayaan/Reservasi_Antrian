@extends('layouts.dashboard')

@section('title', 'Detail Jadwal')
@section('page-title', 'Detail Jadwal')
@section('page-subtitle', 'Dashboard > Jadwal & Kuota > Detail')
@section('user-initial', 'A')
@section('user-name', 'Admin')
@section('user-role', 'Administrator')

@section('content')

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

    <div class="space-y-6">

        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <x-button href="{{ route('admin.jadwal.index') }}" variant="ghost" size="sm">
                <svg viewBox="0 0 24 24" class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                </svg>
                Kembali ke Daftar Jadwal
            </x-button>

            <x-button href="{{ route('admin.jadwal.edit', $jadwal) }}" variant="primary" size="sm">
                <x-icon name="pencil-square" class="h-4 w-4" />
                Ubah Jadwal
            </x-button>
        </div>

        <x-card padding="p-6">
            <div class="flex flex-col gap-5 sm:flex-row sm:items-start sm:justify-between">
                <div>
                    <h1 class="font-display text-xl font-bold text-pln-navy-950">
                        {{ $jadwal->tanggal->translatedFormat('l, d F Y') }}
                    </h1>
                    <p class="mt-1 text-sm text-pln-slate-600">
                        {{ substr($jadwal->jam_mulai, 0, 5) }} - {{ substr($jadwal->jam_selesai, 0, 5) }} &middot; {{ $jadwal->layanan->nama_layanan }}
                    </p>
                </div>

                <x-badge :variant="$badgeVariant" class="text-sm">{{ $badgeLabel }}</x-badge>
            </div>

            <div class="mt-6 grid gap-x-6 gap-y-5 border-t border-pln-slate-100 pt-6 sm:grid-cols-2 lg:grid-cols-4">
                <x-reservasi.info-row icon="users" label="Kuota Maksimum" :value="$jadwal->kuota_maksimal" />
                <x-reservasi.info-row icon="clipboard-list" label="Kuota Terisi" :value="$jadwal->kuota_terpakai" />
                <x-reservasi.info-row icon="check-circle" label="Sisa Kuota" :value="$jadwal->sisaKuota()" />
                <x-reservasi.info-row icon="calendar" label="Dibuat Pada" :value="$jadwal->created_at->translatedFormat('d M Y, H:i') . ' WIB'" />
            </div>
        </x-card>

        <x-card padding="p-6">
            <x-slot:header>
                <h2 class="font-display text-base font-semibold text-pln-navy-900">Reservasi pada Slot Ini</h2>
            </x-slot:header>

            @if ($reservasiTerkait->isEmpty())
                <x-empty-state
                    title="Belum ada reservasi"
                    description="Reservasi pelanggan untuk slot ini akan tampil di sini."
                />
            @else
                <div class="hidden overflow-x-auto sm:block">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="border-b border-pln-slate-200 text-xs font-semibold uppercase tracking-wider text-pln-slate-400">
                                <th class="px-4 py-2">No. Antrean</th>
                                <th class="px-4 py-2">Nama Pelanggan</th>
                                <th class="px-4 py-2">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($reservasiTerkait as $reservasi)
                                <tr class="border-b border-pln-slate-100 last:border-0">
                                    <td class="whitespace-nowrap px-4 py-3 font-mono text-sm font-semibold text-pln-navy-900">{{ $reservasi->nomor_antrean }}</td>
                                    <td class="px-4 py-3 text-sm text-pln-slate-900">{{ $reservasi->nama }}</td>
                                    <td class="whitespace-nowrap px-4 py-3">
                                        <x-badge :variant="$reservasi->status->badgeVariant()">{{ $reservasi->status->label() }}</x-badge>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="space-y-3 sm:hidden">
                    @foreach ($reservasiTerkait as $reservasi)
                        <div class="rounded-xl border border-pln-slate-200 p-4">
                            <div class="flex items-center justify-between gap-3">
                                <p class="font-mono text-sm font-bold text-pln-navy-900">{{ $reservasi->nomor_antrean }}</p>
                                <x-badge :variant="$reservasi->status->badgeVariant()">{{ $reservasi->status->label() }}</x-badge>
                            </div>
                            <p class="mt-1.5 text-sm text-pln-slate-900">{{ $reservasi->nama }}</p>
                        </div>
                    @endforeach
                </div>
            @endif
        </x-card>

    </div>

@endsection