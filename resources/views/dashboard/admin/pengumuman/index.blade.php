@extends('layouts.dashboard')

@section('title', 'Kelola Pengumuman')
@section('page-title', 'Kelola Pengumuman')
@section('page-subtitle', 'Dashboard > Pengumuman')
@section('user-initial', 'A')
@section('user-name', 'Admin')
@section('user-role', 'Administrator')

@section('content')

    <div class="space-y-6">

        @if (session('success'))
            <x-alert variant="success" title="Berhasil" dismissible>{{ session('success') }}</x-alert>
        @endif

        <x-card padding="p-6">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h1 class="font-display text-xl font-bold text-pln-navy-950">Kelola Pengumuman</h1>
                    <p class="mt-1 text-sm text-pln-slate-600">Kelola pengumuman yang ditampilkan kepada pelanggan.</p>
                </div>

                <x-button href="{{ route('admin.pengumuman.create') }}" variant="primary" size="md">
                    <x-icon name="plus" class="h-4 w-4" />
                    Tambah Pengumuman
                </x-button>
            </div>
        </x-card>

        {{-- Kartu Statistik --}}
        <div class="grid grid-cols-2 gap-4 lg:grid-cols-4">
            <x-card padding="p-5">
                <div class="flex items-center gap-3">
                    <span class="flex h-10 w-10 items-center justify-center rounded-lg bg-pln-navy-600/10 text-pln-navy-700">
                        <x-icon name="megaphone" class="h-5 w-5" />
                    </span>
                    <div>
                        <p class="font-display text-2xl font-bold text-pln-navy-950">{{ $statistik['total'] }}</p>
                        <p class="text-xs text-pln-slate-500">Pengumuman</p>
                    </div>
                </div>
                <p class="mt-3 text-sm font-medium text-pln-slate-700">Total</p>
            </x-card>

            <x-card padding="p-5">
                <div class="flex items-center gap-3">
                    <span class="flex h-10 w-10 items-center justify-center rounded-lg bg-status-done/10 text-status-done">
                        <x-icon name="check-circle" class="h-5 w-5" />
                    </span>
                    <div>
                        <p class="font-display text-2xl font-bold text-pln-navy-950">{{ $statistik['aktif'] }}</p>
                        <p class="text-xs text-pln-slate-500">Sedang tayang</p>
                    </div>
                </div>
                <p class="mt-3 text-sm font-medium text-pln-slate-700">Aktif</p>
            </x-card>

            <x-card padding="p-5">
                <div class="flex items-center gap-3">
                    <span class="flex h-10 w-10 items-center justify-center rounded-lg bg-status-review/10 text-status-review">
                        <x-icon name="clock" class="h-5 w-5" />
                    </span>
                    <div>
                        <p class="font-display text-2xl font-bold text-pln-navy-950">{{ $statistik['terjadwal'] }}</p>
                        <p class="text-xs text-pln-slate-500">Belum mulai</p>
                    </div>
                </div>
                <p class="mt-3 text-sm font-medium text-pln-slate-700">Terjadwal</p>
            </x-card>

            <x-card padding="p-5">
                <div class="flex items-center gap-3">
                    <span class="flex h-10 w-10 items-center justify-center rounded-lg bg-pln-slate-200 text-pln-slate-500">
                        <x-icon name="pause-circle" class="h-5 w-5" />
                    </span>
                    <div>
                        <p class="font-display text-2xl font-bold text-pln-navy-950">{{ $statistik['nonaktif'] }}</p>
                        <p class="text-xs text-pln-slate-500">Dinonaktifkan</p>
                    </div>
                </div>
                <p class="mt-3 text-sm font-medium text-pln-slate-700">Nonaktif</p>
            </x-card>
        </div>

        {{-- Daftar Pengumuman --}}
        <x-card padding="p-6">
            <x-slot:header>
                <div>
                    <h2 class="font-display text-base font-semibold text-pln-navy-900">Daftar Pengumuman</h2>
                    <p class="mt-0.5 text-sm text-pln-slate-500">Kelola dan pantau semua pengumuman.</p>
                </div>
            </x-slot:header>

            <form method="GET" class="mb-5 flex flex-col gap-3 sm:flex-row">
                <div class="relative flex-1">
                    <x-icon name="search" class="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-pln-slate-400" />
                    <input
                        type="text"
                        name="cari"
                        value="{{ $pencarian }}"
                        placeholder="Cari judul pengumuman..."
                        class="w-full rounded-lg border border-pln-slate-200 py-2.5 pl-10 pr-3.5 text-sm text-pln-slate-900 placeholder:text-pln-slate-400 focus:border-pln-navy-700 focus:outline-none focus:ring-2 focus:ring-pln-navy-700/20"
                    >
                </div>

                <select
                    name="status"
                    onchange="this.form.submit()"
                    class="rounded-lg border border-pln-slate-200 px-3.5 py-2.5 text-sm text-pln-slate-700 focus:border-pln-navy-700 focus:outline-none focus:ring-2 focus:ring-pln-navy-700/20 sm:w-48"
                >
                    <option value="semua" @selected($statusFilter === 'semua')>Semua Status</option>
                    <option value="aktif" @selected($statusFilter === 'aktif')>Aktif</option>
                    <option value="terjadwal" @selected($statusFilter === 'terjadwal')>Terjadwal</option>
                    <option value="berakhir" @selected($statusFilter === 'berakhir')>Berakhir</option>
                    <option value="nonaktif" @selected($statusFilter === 'nonaktif')>Nonaktif</option>
                </select>

                <button type="submit" class="rounded-lg bg-pln-navy-900 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-pln-navy-800 sm:hidden">
                    Cari
                </button>
            </form>

            @if ($pengumumans->isEmpty())
                @php
                    $judulKosong = $pencarian !== '' ? 'Pengumuman tidak ditemukan' : 'Belum ada pengumuman';
                    $deskripsiKosong = $pencarian !== ''
                        ? "Tidak ada pengumuman yang cocok dengan pencarian \"{$pencarian}\"."
                        : 'Buat pengumuman pertama untuk disampaikan kepada pelanggan.';
                @endphp
                <x-empty-state
                    :title="$judulKosong"
                    :description="$deskripsiKosong"
                >
                    @if ($pencarian === '')
                        <x-slot:action>
                            <x-button href="{{ route('admin.pengumuman.create') }}" variant="primary" size="sm">
                                <x-icon name="plus" class="h-4 w-4" />
                                Tambah Pengumuman
                            </x-button>
                        </x-slot:action>
                    @endif
                </x-empty-state>
            @else
                <div class="hidden overflow-x-auto sm:block">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="border-b border-pln-slate-200 text-xs font-semibold uppercase tracking-wider text-pln-slate-400">
                                <th class="px-4 py-2.5">No</th>
                                <th class="px-4 py-2.5">
                                    <x-layanan.sort-link column="judul" label="Judul" :sort-by="$sortBy" :sort-direction="$sortDirection" />
                                </th>
                                <th class="px-4 py-2.5">
                                    <x-layanan.sort-link column="tanggal_mulai" label="Mulai Tayang" :sort-by="$sortBy" :sort-direction="$sortDirection" />
                                </th>
                                <th class="px-4 py-2.5">Selesai Tayang</th>
                                <th class="px-4 py-2.5">
                                    <x-layanan.sort-link column="is_active" label="Status" :sort-by="$sortBy" :sort-direction="$sortDirection" />
                                </th>
                                <th class="px-4 py-2.5">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($pengumumans as $pengumuman)
                                <x-pengumuman.table-row :pengumuman="$pengumuman" :nomor="$pengumumans->firstItem() + $loop->index" />
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="space-y-3 sm:hidden">
                    @foreach ($pengumumans as $pengumuman)
                        <x-pengumuman.card :pengumuman="$pengumuman" />
                    @endforeach
                </div>

                <div class="mt-5 border-t border-pln-slate-100 pt-5">
                    <x-pagination :paginator="$pengumumans" />
                </div>
            @endif
        </x-card>

    </div>

@endsection