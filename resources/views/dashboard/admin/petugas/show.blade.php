@extends('layouts.dashboard')

@section('title', 'Detail Petugas')
@section('page-title', 'Detail Petugas')
@section('page-subtitle', 'Dashboard > Pengguna > Detail')
@section('user-initial', 'A')
@section('user-name', 'Admin')
@section('user-role', 'Administrator')

@section('content')

    <div class="space-y-6">

        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <x-button href="{{ route('admin.pengguna.index') }}" variant="ghost" size="sm">
                <svg viewBox="0 0 24 24" class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                </svg>
                Kembali ke Daftar Pengguna
            </x-button>

            <x-button href="{{ route('admin.pengguna.edit', $petugas) }}" variant="primary" size="sm">
                <x-icon name="pencil-square" class="h-4 w-4" />
                Ubah Petugas
            </x-button>
        </div>

        <x-card padding="p-6">
            <div class="flex flex-col gap-5 sm:flex-row sm:items-start sm:justify-between">
                <div class="flex items-start gap-4">
                    <span class="flex h-14 w-14 shrink-0 items-center justify-center rounded-full bg-pln-navy-900 text-lg font-semibold text-white">
                        {{ mb_substr($petugas->nama_petugas, 0, 2) }}
                    </span>
                    <div>
                        <h1 class="font-display text-xl font-bold text-pln-navy-950">{{ $petugas->nama_petugas }}</h1>
                        <p class="mt-1 text-sm text-pln-slate-500">{{ $petugas->email }}</p>
                    </div>
                </div>

                <x-badge :variant="$petugas->is_active ? 'done' : 'neutral'" class="text-sm">
                    {{ $petugas->is_active ? 'Aktif' : 'Nonaktif' }}
                </x-badge>
            </div>

            <div class="mt-6 grid gap-x-6 gap-y-5 border-t border-pln-slate-100 pt-6 sm:grid-cols-2 lg:grid-cols-4">
                <x-reservasi.info-row icon="phone" label="Nomor HP" :value="$petugas->no_hp ?? '-'" />
                <x-reservasi.info-row icon="document-text" label="Catatan Ditulis" :value="$petugas->notes_count . ' catatan'" />
                <x-reservasi.info-row icon="ticket" label="Perubahan Status" :value="$petugas->status_histories_count . ' perubahan'" />
                <x-reservasi.info-row icon="calendar" label="Bergabung Sejak" :value="$petugas->created_at->translatedFormat('d F Y')" />
            </div>
        </x-card>

        <x-card padding="p-6">
            <x-slot:header>
                <h2 class="font-display text-base font-semibold text-pln-navy-900">Catatan Terbaru yang Ditulis</h2>
            </x-slot:header>

            @if ($catatanTerbaru->isEmpty())
                <x-empty-state
                    title="Belum ada catatan"
                    description="Catatan yang ditulis petugas ini pada reservasi pelanggan akan tampil di sini."
                />
            @else
                <ul class="space-y-3">
                    @foreach ($catatanTerbaru as $catatan)
                        <li class="rounded-lg border border-pln-slate-200 p-4">
                            <div class="flex items-center justify-between gap-3">
                                <span class="font-mono text-xs font-semibold text-pln-navy-700">
                                    {{ $catatan->reservasi->kode_reservasi }} &middot; {{ $catatan->reservasi->nomor_antrean }}
                                </span>
                                <span class="text-xs text-pln-slate-400">{{ $catatan->created_at->translatedFormat('d M Y, H:i') }}</span>
                            </div>
                            <p class="mt-2 text-sm leading-relaxed text-pln-slate-700">{{ $catatan->isi_catatan }}</p>
                        </li>
                    @endforeach
                </ul>
            @endif
        </x-card>

    </div>

@endsection