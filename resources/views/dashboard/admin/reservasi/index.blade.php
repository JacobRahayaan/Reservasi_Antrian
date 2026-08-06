@extends('layouts.dashboard')

@section('title', 'Reservasi')
@section('page-title', 'Reservasi')
@section('page-subtitle', 'Dashboard > Reservasi')
@section('user-initial', 'A')
@section('user-name', 'Admin')
@section('user-role', 'Administrator')

@section('content')

    <div class="space-y-6">

        <x-card padding="p-6">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h1 class="font-display text-xl font-bold text-pln-navy-950">Reservasi</h1>
                    <p class="mt-1 text-sm text-pln-slate-600">
                        Pantau seluruh reservasi pelanggan di sistem. Halaman ini bersifat monitoring 
                        perubahan status dan catatan dikelola melalui Dashboard Customer Service.
                    </p>
                </div>

                <a
                    href="{{ route('admin.reservasi.export', request()->query()) }}"
                    class="flex items-center justify-center gap-2 rounded-lg border border-pln-slate-300 px-4 py-2.5 text-sm font-semibold text-pln-slate-700 transition hover:bg-pln-slate-50"
                >
                    <x-icon name="download" class="h-4 w-4" />
                    Export
                </a>
            </div>
        </x-card>

        {{-- Kartu Statistik --}}
        <div class="grid grid-cols-2 gap-4 lg:grid-cols-3 xl:grid-cols-6">
            <x-cs-reservasi.summary-card icon="calendar" icon-bg="bg-pln-navy-600/10" icon-color="text-pln-navy-700" label="Total" :nilai="$statistik['total']" />
            <x-cs-reservasi.summary-card icon="clock" icon-bg="bg-status-review/10" icon-color="text-status-review" label="Menunggu Review" :nilai="$statistik['menunggu_review']" />
            <x-cs-reservasi.summary-card icon="walking" icon-bg="bg-status-visit/10" icon-color="text-status-visit" label="Perlu Datang" :nilai="$statistik['perlu_datang']" />
            <x-cs-reservasi.summary-card icon="check" icon-bg="bg-status-online/10" icon-color="text-status-online" label="Selesai Online" :nilai="$statistik['selesai_online']" />
            <x-cs-reservasi.summary-card icon="check-circle" icon-bg="bg-violet-500/10" icon-color="text-violet-600" label="Selesai" :nilai="$statistik['selesai']" />
            <x-cs-reservasi.summary-card icon="x-mark" icon-bg="bg-status-cancel/10" icon-color="text-status-cancel" label="Dibatalkan" :nilai="$statistik['dibatalkan']" />
        </div>

        {{-- Filter --}}
        <x-card padding="p-6">
            <form method="GET" class="grid gap-4 sm:grid-cols-2 lg:grid-cols-5 lg:items-end">
                <div class="lg:col-span-2">
                    <label for="cari" class="mb-1.5 block text-sm font-medium text-pln-slate-900">Cari</label>
                    <div class="relative">
                        <x-icon name="search" class="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-pln-slate-400" />
                        <input
                            type="text"
                            name="cari"
                            id="cari"
                            value="{{ $filters['cari'] }}"
                            placeholder="Nama, nomor antrean, atau kode reservasi..."
                            class="w-full rounded-lg border border-pln-slate-200 py-2.5 pl-10 pr-3.5 text-sm text-pln-slate-900 placeholder:text-pln-slate-400 focus:border-pln-navy-700 focus:outline-none focus:ring-2 focus:ring-pln-navy-700/20"
                        >
                    </div>
                </div>

                <div>
                    <label for="layanan_id" class="mb-1.5 block text-sm font-medium text-pln-slate-900">Layanan</label>
                    <select name="layanan_id" id="layanan_id" class="w-full rounded-lg border border-pln-slate-200 px-3.5 py-2.5 text-sm text-pln-slate-700 focus:border-pln-navy-700 focus:outline-none focus:ring-2 focus:ring-pln-navy-700/20">
                        <option value="">Semua Layanan</option>
                        @foreach ($layanans as $layanan)
                            <option value="{{ $layanan->id }}" @selected((string) $filters['layanan_id'] === (string) $layanan->id)>
                                {{ $layanan->nama_layanan }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="status" class="mb-1.5 block text-sm font-medium text-pln-slate-900">Status</label>
                    <select name="status" id="status" class="w-full rounded-lg border border-pln-slate-200 px-3.5 py-2.5 text-sm text-pln-slate-700 focus:border-pln-navy-700 focus:outline-none focus:ring-2 focus:ring-pln-navy-700/20">
                        <option value="">Semua Status</option>
                        @foreach ($opsiStatus as $value => $label)
                            <option value="{{ $value }}" @selected($filters['status'] === $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="urutan" class="mb-1.5 block text-sm font-medium text-pln-slate-900">Urutkan</label>
                    <select name="urutan" id="urutan" class="w-full rounded-lg border border-pln-slate-200 px-3.5 py-2.5 text-sm text-pln-slate-700 focus:border-pln-navy-700 focus:outline-none focus:ring-2 focus:ring-pln-navy-700/20">
                        <option value="terbaru" @selected($filters['urutan'] === 'terbaru')>Terbaru</option>
                        <option value="terlama" @selected($filters['urutan'] === 'terlama')>Terlama</option>
                    </select>
                </div>

                <div>
                    <label for="tanggal_mulai" class="mb-1.5 block text-sm font-medium text-pln-slate-900">Tanggal Mulai</label>
                    <input type="date" name="tanggal_mulai" id="tanggal_mulai" value="{{ $filters['tanggal_mulai'] }}" class="w-full rounded-lg border border-pln-slate-200 px-3.5 py-2.5 text-sm text-pln-slate-700 focus:border-pln-navy-700 focus:outline-none focus:ring-2 focus:ring-pln-navy-700/20">
                </div>

                <div>
                    <label for="tanggal_akhir" class="mb-1.5 block text-sm font-medium text-pln-slate-900">Tanggal Akhir</label>
                    <input type="date" name="tanggal_akhir" id="tanggal_akhir" value="{{ $filters['tanggal_akhir'] }}" class="w-full rounded-lg border border-pln-slate-200 px-3.5 py-2.5 text-sm text-pln-slate-700 focus:border-pln-navy-700 focus:outline-none focus:ring-2 focus:ring-pln-navy-700/20">
                </div>

                <div class="flex gap-2 lg:col-span-5">
                    <button type="submit" class="rounded-lg bg-pln-navy-900 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-pln-navy-800">
                        Terapkan Filter
                    </button>
                    <a href="{{ route('admin.reservasi.index') }}" class="rounded-lg border border-pln-slate-300 px-4 py-2.5 text-sm font-semibold text-pln-slate-700 transition hover:bg-pln-slate-50">
                        Reset
                    </a>
                </div>
            </form>
        </x-card>

        {{-- Daftar Reservasi --}}
        <x-card padding="p-6">
            <x-slot:header>
                <h2 class="font-display text-base font-semibold text-pln-navy-900">Daftar Reservasi</h2>
            </x-slot:header>

            @if ($reservasis->isEmpty())
                <x-empty-state
                    title="Belum ada reservasi"
                    description="Tidak ada reservasi yang cocok dengan filter saat ini."
                />
            @else
                <div class="hidden overflow-x-auto sm:block">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="border-b border-pln-slate-200 text-xs font-semibold uppercase tracking-wider text-pln-slate-400">
                                <th class="px-4 py-2.5">No. Antrean</th>
                                <th class="px-4 py-2.5">Kode Reservasi</th>
                                <th class="px-4 py-2.5">Nama Pelanggan</th>
                                <th class="px-4 py-2.5">Layanan</th>
                                <th class="px-4 py-2.5">Tanggal &amp; Jam</th>
                                <th class="px-4 py-2.5">Status</th>
                                <th class="px-4 py-2.5">Ditangani Oleh</th>
                                <th class="px-4 py-2.5 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($reservasis as $reservasi)
                                <x-cs-reservasi.table-row :reservasi="$reservasi" />
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="space-y-3 sm:hidden">
                    @foreach ($reservasis as $reservasi)
                        <x-cs-reservasi.card :reservasi="$reservasi" />
                    @endforeach
                </div>

                <div class="mt-5 border-t border-pln-slate-100 pt-5">
                    <x-pagination :paginator="$reservasis" />
                </div>
            @endif
        </x-card>

    </div>

@endsection