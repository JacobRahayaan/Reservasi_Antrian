@extends('layouts.dashboard')

@section('title', 'Kelola Layanan')
@section('page-title', 'Kelola Layanan')
@section('page-subtitle', 'Layanan > Kelola Layanan')
@section('user-initial', 'A')
@section('user-name', 'Admin')
@section('user-role', 'Administrator')

@section('content')

    <div class="space-y-6">

        @if (session('success'))
            <x-alert variant="success" title="Berhasil" dismissible>
                {{ session('success') }}
            </x-alert>
        @endif

        <x-card padding="p-6">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h1 class="font-display text-xl font-bold text-pln-navy-950">Kelola Layanan</h1>
                    <p class="mt-1 text-sm text-pln-slate-600">
                        Kelola semua jenis layanan yang tersedia untuk pelanggan.
                    </p>
                </div>

                <x-button href="{{ route('admin.layanan.create') }}" variant="primary" size="md">
                    <x-icon name="plus" class="h-4 w-4" />
                    Tambah Layanan
                </x-button>
            </div>
        </x-card>

        {{-- Kartu Statistik --}}
        <div class="grid grid-cols-2 gap-4 lg:grid-cols-4">
            <x-card padding="p-5">
                <div class="flex items-center gap-3">
                    <span class="flex h-10 w-10 items-center justify-center rounded-lg bg-pln-navy-600/10 text-pln-navy-700">
                        <x-icon name="calendar" class="h-5 w-5" />
                    </span>
                    <div>
                        <p class="font-display text-2xl font-bold text-pln-navy-950">{{ $statistik['total'] }}</p>
                        <p class="text-xs text-pln-slate-500">Jenis layanan</p>
                    </div>
                </div>
                <p class="mt-3 text-sm font-medium text-pln-slate-700">Total Layanan</p>
            </x-card>

            <x-card padding="p-5">
                <div class="flex items-center gap-3">
                    <span class="flex h-10 w-10 items-center justify-center rounded-lg bg-status-done/10 text-status-done">
                        <x-icon name="check" class="h-5 w-5" />
                    </span>
                    <div>
                        <p class="font-display text-2xl font-bold text-pln-navy-950">{{ $statistik['aktif'] }}</p>
                        <p class="text-xs text-pln-slate-500">Layanan aktif</p>
                    </div>
                </div>
                <p class="mt-3 text-sm font-medium text-pln-slate-700">Aktif</p>
            </x-card>

            <x-card padding="p-5">
                <div class="flex items-center gap-3">
                    <span class="flex h-10 w-10 items-center justify-center rounded-lg bg-pln-amber-500/10 text-pln-amber-600">
                        <x-icon name="pause-circle" class="h-5 w-5" />
                    </span>
                    <div>
                        <p class="font-display text-2xl font-bold text-pln-navy-950">{{ $statistik['nonaktif'] }}</p>
                        <p class="text-xs text-pln-slate-500">Layanan nonaktif</p>
                    </div>
                </div>
                <p class="mt-3 text-sm font-medium text-pln-slate-700">Nonaktif</p>
            </x-card>

            <x-card padding="p-5">
                <div class="flex items-center gap-3">
                    <span class="flex h-10 w-10 items-center justify-center rounded-lg bg-violet-500/10 text-violet-600">
                        <x-icon name="chart-pie" class="h-5 w-5" />
                    </span>
                    <div>
                        <p class="font-display text-2xl font-bold text-pln-navy-950">{{ $statistik['digunakan_hari_ini'] }}</p>
                        <p class="text-xs text-pln-slate-500">Reservasi</p>
                    </div>
                </div>
                <p class="mt-3 text-sm font-medium text-pln-slate-700">Digunakan Hari Ini</p>
            </x-card>
        </div>

        {{-- Daftar Layanan --}}
        <x-card padding="p-6">
            <x-slot:header>
                <div>
                    <h2 class="font-display text-base font-semibold text-pln-navy-900">Daftar Layanan</h2>
                    <p class="mt-0.5 text-sm text-pln-slate-500">Kelola dan pantau semua layanan yang tersedia.</p>
                </div>
            </x-slot:header>

            <form method="GET" class="mb-5 flex flex-col gap-3 sm:flex-row">
                <div class="relative flex-1">
                    <x-icon name="search" class="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-pln-slate-400" />
                    <input
                        type="text"
                        name="cari"
                        value="{{ $pencarian }}"
                        placeholder="Cari layanan..."
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
                    <option value="nonaktif" @selected($statusFilter === 'nonaktif')>Nonaktif</option>
                </select>

                <button
                    type="submit"
                    class="rounded-lg bg-pln-navy-900 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-pln-navy-800 sm:hidden"
                >
                    Cari
                </button>
            </form>

            @if ($layanans->isEmpty())
                @php
                    $emptyTitle = $pencarian !== '' ? 'Layanan tidak ditemukan' : 'Belum ada layanan';
                    $emptyDescription = $pencarian !== ''
                        ? "Tidak ada layanan yang cocok dengan pencarian '{$pencarian}'."
                        : 'Tambahkan jenis layanan pertama untuk mulai menerima reservasi pelanggan.';
                @endphp

                <x-empty-state
                    title="{{ $emptyTitle }}"
                    description="{{ $emptyDescription }}"
                >
                    @if ($pencarian === '')
                        <x-slot:action>
                            <x-button href="{{ route('admin.layanan.create') }}" variant="primary" size="sm">
                                <x-icon name="plus" class="h-4 w-4" />
                                Tambah Layanan
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
                                    <x-layanan.sort-link column="nama_layanan" label="Layanan" :sort-by="$sortBy" :sort-direction="$sortDirection" />
                                </th>
                                <th class="px-4 py-2.5">Deskripsi</th>
                                <th class="px-4 py-2.5">Estimasi Waktu</th>
                                <th class="px-4 py-2.5">
                                    <x-layanan.sort-link column="is_active" label="Status" :sort-by="$sortBy" :sort-direction="$sortDirection" />
                                </th>
                                <th class="px-4 py-2.5">
                                    <x-layanan.sort-link column="created_at" label="Dibuat Pada" :sort-by="$sortBy" :sort-direction="$sortDirection" />
                                </th>
                                <th class="px-4 py-2.5">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($layanans as $layanan)
                                <x-layanan.table-row :layanan="$layanan" :nomor="$layanans->firstItem() + $loop->index" />
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="space-y-3 sm:hidden">
                    @foreach ($layanans as $layanan)
                        <x-layanan.card :layanan="$layanan" />
                    @endforeach
                </div>

                <div class="mt-5 border-t border-pln-slate-100 pt-5">
                    <x-pagination :paginator="$layanans" />
                </div>
            @endif
        </x-card>

    </div>

@endsection