@extends('layouts.dashboard')

@section('title', 'Detail Layanan')
@section('page-title', 'Detail Layanan')
@section('page-subtitle', 'Layanan > Kelola Layanan > Detail')
@section('user-initial', 'A')
@section('user-name', 'Admin')
@section('user-role', 'Administrator')

@section('content')

    @php
        $ikonLayanan = match ($layanan->kode_layanan) {
            'A' => ['icon' => 'bolt', 'bg' => 'bg-pln-amber-500/10', 'color' => 'text-pln-amber-600'],
            'B' => ['icon' => 'document-text', 'bg' => 'bg-pln-navy-600/10', 'color' => 'text-pln-navy-700'],
            default => ['icon' => 'wrench-screwdriver', 'bg' => 'bg-status-done/10', 'color' => 'text-status-done'],
        };
    @endphp

    <div class="space-y-6">

        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <x-button href="{{ route('admin.layanan.index') }}" variant="ghost" size="sm">
                <svg viewBox="0 0 24 24" class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                </svg>
                Kembali ke Daftar Layanan
            </x-button>

            <x-button href="{{ route('admin.layanan.edit', $layanan) }}" variant="primary" size="sm">
                <x-icon name="pencil-square" class="h-4 w-4" />
                Ubah Layanan
            </x-button>
        </div>

        <x-card padding="p-6">
            <div class="flex flex-col gap-5 sm:flex-row sm:items-start sm:justify-between">
                <div class="flex items-start gap-4">
                    <span class="flex h-14 w-14 shrink-0 items-center justify-center rounded-full {{ $ikonLayanan['bg'] }} {{ $ikonLayanan['color'] }}">
                        <x-icon :name="$ikonLayanan['icon']" class="h-7 w-7" />
                    </span>
                    <div>
                        <h1 class="font-display text-xl font-bold text-pln-navy-950">{{ $layanan->nama_layanan }}</h1>
                        <p class="mt-1 font-mono text-sm text-pln-slate-500">Prefix: {{ $layanan->kode_layanan }}</p>
                        <p class="mt-2 max-w-lg text-sm leading-relaxed text-pln-slate-600">
                            {{ $layanan->deskripsi ?? 'Tidak ada deskripsi.' }}
                        </p>
                    </div>
                </div>

                <x-badge :variant="$layanan->is_active ? 'done' : 'neutral'" class="text-sm">
                    {{ $layanan->is_active ? 'Aktif' : 'Nonaktif' }}
                </x-badge>
            </div>

            <div class="mt-6 grid gap-x-6 gap-y-5 border-t border-pln-slate-100 pt-6 sm:grid-cols-2 lg:grid-cols-4">
                <x-reservasi.info-row icon="clock" label="Estimasi Waktu" :value="$layanan->estimasi_waktu_label ?? '-'" />
                <x-reservasi.info-row icon="chart-pie" label="Total Reservasi" :value="$layanan->reservasis_count . ' reservasi'" />
                <x-reservasi.info-row icon="calendar" label="Dibuat Pada" :value="$layanan->created_at->translatedFormat('d M Y, H:i') . ' WIB'" />
                <x-reservasi.info-row icon="clock" label="Terakhir Diubah" :value="$layanan->updated_at->translatedFormat('d M Y, H:i') . ' WIB'" />
            </div>
        </x-card>

        <x-card padding="p-6">
            <x-slot:header>
                <h2 class="font-display text-base font-semibold text-pln-navy-900">Reservasi Terbaru untuk Layanan Ini</h2>
            </x-slot:header>

            @if ($reservasiTerbaru->isEmpty())
                <x-empty-state
                    title="Belum ada reservasi"
                    description="Reservasi pelanggan untuk layanan ini akan tampil di sini."
                />
            @else
                <div class="hidden overflow-x-auto sm:block">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="border-b border-pln-slate-200 text-xs font-semibold uppercase tracking-wider text-pln-slate-400">
                                <th class="px-4 py-2">No. Antrean</th>
                                <th class="px-4 py-2">Nama Pelanggan</th>
                                <th class="px-4 py-2">Jadwal</th>
                                <th class="px-4 py-2">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($reservasiTerbaru as $reservasi)
                                <tr class="border-b border-pln-slate-100 last:border-0">
                                    <td class="whitespace-nowrap px-4 py-3 font-mono text-sm font-semibold text-pln-navy-900">
                                        {{ $reservasi->nomor_antrean }}
                                    </td>
                                    <td class="px-4 py-3 text-sm text-pln-slate-900">{{ $reservasi->nama }}</td>
                                    <td class="whitespace-nowrap px-4 py-3 text-sm text-pln-slate-600">
                                        {{ $reservasi->jadwal->tanggal->translatedFormat('d M Y') }} &middot;
                                        {{ substr($reservasi->jadwal->jam_mulai, 0, 5) }}
                                    </td>
                                    <td class="whitespace-nowrap px-4 py-3">
                                        <x-badge :variant="$reservasi->status->badgeVariant()">{{ $reservasi->status->label() }}</x-badge>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="space-y-3 sm:hidden">
                    @foreach ($reservasiTerbaru as $reservasi)
                        <div class="rounded-xl border border-pln-slate-200 p-4">
                            <div class="flex items-center justify-between gap-3">
                                <p class="font-mono text-sm font-bold text-pln-navy-900">{{ $reservasi->nomor_antrean }}</p>
                                <x-badge :variant="$reservasi->status->badgeVariant()">{{ $reservasi->status->label() }}</x-badge>
                            </div>
                            <p class="mt-1.5 text-sm font-medium text-pln-slate-900">{{ $reservasi->nama }}</p>
                            <p class="mt-1 text-xs text-pln-slate-500">
                                {{ $reservasi->jadwal->tanggal->translatedFormat('d M Y') }} &middot;
                                {{ substr($reservasi->jadwal->jam_mulai, 0, 5) }}
                            </p>
                        </div>
                    @endforeach
                </div>
            @endif
        </x-card>

    </div>

@endsection