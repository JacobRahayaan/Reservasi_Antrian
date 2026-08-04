@extends('layouts.dashboard')

@section('title', 'Kelola Jadwal & Kuota')
@section('page-title', 'Kelola Jadwal & Kuota')
@section('page-subtitle', 'Dashboard > Jadwal & Kuota')
@section('user-initial', 'A')
@section('user-name', 'Admin')
@section('user-role', 'Administrator')

@section('content')

    <div class="space-y-6">

        @if (session('success'))
            <x-alert variant="success" title="Berhasil" dismissible>{{ session('success') }}</x-alert>
        @endif

        @if (session('error'))
            <x-alert variant="danger" title="Tidak dapat dihapus" dismissible>{{ session('error') }}</x-alert>
        @endif

        <x-card padding="p-6">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h1 class="font-display text-xl font-bold text-pln-navy-950">Kelola Jadwal &amp; Kuota</h1>
                    <p class="mt-1 text-sm text-pln-slate-600">Atur jadwal pelayanan dan kuota antrean setiap jam.</p>
                </div>

                <x-button href="{{ route('admin.jadwal.create') }}" variant="primary" size="md">
                    <x-icon name="plus" class="h-4 w-4" />
                    Tambah Jadwal
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
                        <p class="font-display text-2xl font-bold text-pln-navy-950">{{ $statistik['total_tanggal'] }}</p>
                        <p class="text-xs text-pln-slate-500">Tanggal</p>
                    </div>
                </div>
                <p class="mt-3 text-sm font-medium text-pln-slate-700">Total Tanggal Aktif</p>
            </x-card>

            <x-card padding="p-5">
                <div class="flex items-center gap-3">
                    <span class="flex h-10 w-10 items-center justify-center rounded-lg bg-pln-amber-500/10 text-pln-amber-600">
                        <x-icon name="clock" class="h-5 w-5" />
                    </span>
                    <div>
                        <p class="font-display text-2xl font-bold text-pln-navy-950">{{ $statistik['total_slot'] }}</p>
                        <p class="text-xs text-pln-slate-500">Slot</p>
                    </div>
                </div>
                <p class="mt-3 text-sm font-medium text-pln-slate-700">Total Slot Waktu</p>
            </x-card>

            <x-card padding="p-5">
                <div class="flex items-center gap-3">
                    <span class="flex h-10 w-10 items-center justify-center rounded-lg bg-violet-500/10 text-violet-600">
                        <x-icon name="users" class="h-5 w-5" />
                    </span>
                    <div>
                        <p class="font-display text-2xl font-bold text-pln-navy-950">{{ number_format($statistik['total_kuota'], 0, ',', '.') }}</p>
                        <p class="text-xs text-pln-slate-500">Kuota antrean</p>
                    </div>
                </div>
                <p class="mt-3 text-sm font-medium text-pln-slate-700">Total Kuota</p>
            </x-card>

            <x-card padding="p-5">
                <div class="flex items-center gap-3">
                    <span class="flex h-10 w-10 items-center justify-center rounded-lg bg-status-done/10 text-status-done">
                        <x-icon name="check-circle" class="h-5 w-5" />
                    </span>
                    <div>
                        <p class="font-display text-2xl font-bold text-pln-navy-950">{{ $statistik['kuota_tersedia_hari_ini'] }}</p>
                        <p class="text-xs text-pln-slate-500">Kuota</p>
                    </div>
                </div>
                <p class="mt-3 text-sm font-medium text-pln-slate-700">Kuota Tersedia Hari Ini</p>
            </x-card>
        </div>

        {{-- Filter --}}
        <x-card padding="p-6">
            <form method="GET" class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4 lg:items-end">
                <div class="lg:col-span-2">
                    <label for="cari" class="mb-1.5 block text-sm font-medium text-pln-slate-900">Cari Layanan</label>
                    <div class="relative">
                        <x-icon name="search" class="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-pln-slate-400" />
                        <input
                            type="text"
                            name="cari"
                            id="cari"
                            value="{{ $pencarian }}"
                            placeholder="Cari nama layanan..."
                            class="w-full rounded-lg border border-pln-slate-200 py-2.5 pl-10 pr-3.5 text-sm text-pln-slate-900 placeholder:text-pln-slate-400 focus:border-pln-navy-700 focus:outline-none focus:ring-2 focus:ring-pln-navy-700/20"
                        >
                    </div>
                </div>

                <div>
                    <label for="layanan_id" class="mb-1.5 block text-sm font-medium text-pln-slate-900">Pilih Layanan</label>
                    <select
                        name="layanan_id"
                        id="layanan_id"
                        class="w-full rounded-lg border border-pln-slate-200 px-3.5 py-2.5 text-sm text-pln-slate-700 focus:border-pln-navy-700 focus:outline-none focus:ring-2 focus:ring-pln-navy-700/20"
                    >
                        <option value="">Semua Layanan</option>
                        @foreach ($layanans as $layanan)
                            <option value="{{ $layanan->id }}" @selected((string) $layananFilter === (string) $layanan->id)>
                                {{ $layanan->nama_layanan }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="tanggal" class="mb-1.5 block text-sm font-medium text-pln-slate-900">Pilih Tanggal</label>
                    <input
                        type="date"
                        name="tanggal"
                        id="tanggal"
                        value="{{ $tanggalFilter->toDateString() }}"
                        class="w-full rounded-lg border border-pln-slate-200 px-3.5 py-2.5 text-sm text-pln-slate-700 focus:border-pln-navy-700 focus:outline-none focus:ring-2 focus:ring-pln-navy-700/20"
                    >
                </div>

                <div class="flex gap-2 lg:col-span-4">
                    <button type="submit" class="rounded-lg bg-pln-navy-900 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-pln-navy-800">
                        Terapkan Filter
                    </button>
                    <a href="{{ route('admin.jadwal.index') }}" class="flex items-center gap-2 rounded-lg border border-pln-slate-300 px-4 py-2.5 text-sm font-semibold text-pln-slate-700 transition hover:bg-pln-slate-50">
                        <x-icon name="calendar" class="h-4 w-4" />
                        Hari Ini
                    </a>
                    <a href="{{ route('admin.jadwal.export', request()->query()) }}" class="flex items-center gap-2 rounded-lg border border-pln-slate-300 px-4 py-2.5 text-sm font-semibold text-pln-slate-700 transition hover:bg-pln-slate-50">
                        <x-icon name="download" class="h-4 w-4" />
                        Export
                    </a>
                </div>
            </form>
        </x-card>

        {{-- Daftar Jadwal --}}
        <x-card padding="p-6">
            <x-slot:header>
                <div>
                    <h2 class="font-display text-base font-semibold text-pln-navy-900">Daftar Jadwal &amp; Kuota</h2>
                    <p class="mt-0.5 text-sm text-pln-slate-500">Daftar jadwal pelayanan dan kuota antrean.</p>
                </div>
            </x-slot:header>

            @if ($jadwals->isEmpty())
                <x-empty-state
                    title="Belum ada jadwal"
                    description="Tidak ada jadwal yang cocok dengan filter saat ini. Tambahkan jadwal baru atau ubah filter pencarian."
                >
                    <x-slot:action>
                        <x-button href="{{ route('admin.jadwal.create') }}" variant="primary" size="sm">
                            <x-icon name="plus" class="h-4 w-4" />
                            Tambah Jadwal
                        </x-button>
                    </x-slot:action>
                </x-empty-state>
            @else
                <div class="hidden overflow-x-auto sm:block">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="border-b border-pln-slate-200 text-xs font-semibold uppercase tracking-wider text-pln-slate-400">
                                <th class="px-4 py-2.5">
                                    <x-layanan.sort-link column="jam_mulai" label="Jam Layanan" :sort-by="$sortBy" :sort-direction="$sortDirection" />
                                </th>
                                <th class="px-4 py-2.5">Layanan</th>
                                <th class="px-4 py-2.5">
                                    <x-layanan.sort-link column="kuota_maksimal" label="Kuota / Jam" :sort-by="$sortBy" :sort-direction="$sortDirection" />
                                </th>
                                <th class="px-4 py-2.5">
                                    <x-layanan.sort-link column="kuota_terpakai" label="Kuota Terisi" :sort-by="$sortBy" :sort-direction="$sortDirection" />
                                </th>
                                <th class="px-4 py-2.5">Sisa Kuota</th>
                                <th class="px-4 py-2.5">
                                    <x-layanan.sort-link column="is_active" label="Status" :sort-by="$sortBy" :sort-direction="$sortDirection" />
                                </th>
                                <th class="px-4 py-2.5">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $tanggalSebelumnya = null; @endphp
                            @foreach ($jadwals as $jadwal)
                                @if (! $tanggalSebelumnya || ! $tanggalSebelumnya->isSameDay($jadwal->tanggal))
                                    <x-jadwal.date-group-header :tanggal="$jadwal->tanggal" />
                                    @php $tanggalSebelumnya = $jadwal->tanggal; @endphp
                                @endif

                                <x-jadwal.table-row :jadwal="$jadwal" group-id="grup-tanggal-{{ $jadwal->tanggal->format('Ymd') }}" />
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="space-y-5 sm:hidden">
                    @php $tanggalSebelumnyaMobile = null; @endphp
                    @foreach ($jadwals as $jadwal)
                        @if (! $tanggalSebelumnyaMobile || ! $tanggalSebelumnyaMobile->isSameDay($jadwal->tanggal))
                            <p class="flex items-center gap-2 text-sm font-semibold text-pln-navy-900">
                                <x-icon name="calendar" class="h-4 w-4" />
                                {{ $jadwal->tanggal->translatedFormat('d F Y') }}
                            </p>
                            @php $tanggalSebelumnyaMobile = $jadwal->tanggal; @endphp
                        @endif

                        <x-jadwal.card :jadwal="$jadwal" />
                    @endforeach
                </div>

                <div class="mt-5 border-t border-pln-slate-100 pt-5">
                    <x-pagination :paginator="$jadwals" />
                </div>
            @endif
        </x-card>

    </div>

@endsection