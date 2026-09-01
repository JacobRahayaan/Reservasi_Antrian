@extends('layouts.dashboard')

@section('title', 'Dashboard Admin')
@section('page-title', 'Dashboard Admin')
@section('page-subtitle', 'Ringkasan informasi dan statistik sistem')
@section('user-initial', 'A')
@section('user-name', 'Admin')
@section('user-role', 'Administrator')

@section('content')

    <div class="space-y-6">

        <x-dashboard.welcome-banner nama="Admin" />

        {{-- Kartu Statistik --}}
        <div class="grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-7">
            @foreach ($kartuStatistik as $kartu)
                <x-dashboard.stat-card
                    :label="$kartu['label']"
                    :nilai="$kartu['nilai']"
                    :icon="$kartu['icon']"
                    :warna="$kartu['warna']"
                    :persentase="$kartu['persentase']"
                    :arah="$kartu['arah']"
                    :keterangan="$kartu['keterangan']"
                />
            @endforeach
        </div>

        <div class="grid gap-6 lg:grid-cols-2">

            {{-- Grafik Reservasi 7 Hari Terakhir --}}
            <x-card padding="p-6">
                <x-slot:header>
                    <h2 class="font-display text-base font-semibold text-pln-navy-900">
                        Grafik Reservasi 7 Hari Terakhir
                    </h2>
                </x-slot:header>

                <x-dashboard.line-chart :data="$grafikMingguan" />
            </x-card>

            {{-- Distribusi Reservasi per Layanan --}}
            <x-card padding="p-6">
                <x-slot:header>
                    <h2 class="font-display text-base font-semibold text-pln-navy-900">
                        Distribusi Reservasi per Layanan
                    </h2>
                    <span class="rounded-lg border border-pln-slate-200 px-3 py-1.5 text-xs font-medium text-pln-slate-500">
                        Hari Ini
                    </span>
                </x-slot:header>

                <x-dashboard.donut-chart :data="$distribusiLayanan" :total="$totalDistribusiLayanan" />
            </x-card>

        </div>

        <div class="grid gap-6 lg:grid-cols-2">

            {{-- Reservasi Terbaru --}}
            <x-card padding="p-6">
                <x-slot:header>
                    <h2 class="font-display text-base font-semibold text-pln-navy-900">Reservasi Terbaru</h2>
                    @if (Route::has('admin.reservasi.index'))
                        <a href="{{ route('admin.reservasi.index') }}" class="text-sm font-semibold text-pln-navy-700 hover:text-pln-navy-900">
                            Lihat Semua
                        </a>
                    @else
                        <span class="text-sm font-semibold text-pln-slate-300" title="Modul akan tersedia pada sprint berikutnya">
                            Lihat Semua
                        </span>
                    @endif
                </x-slot:header>

                @if ($reservasiTerbaru->isEmpty())
                    <x-empty-state
                        title="Belum ada reservasi"
                        description="Reservasi yang masuk dari pelanggan akan tampil di sini."
                    />
                @else
                    <div class="hidden overflow-x-auto sm:block">
                        <table class="w-full text-left">
                            <thead>
                                <tr class="border-b border-pln-slate-200 text-xs font-semibold uppercase tracking-wider text-pln-slate-400">
                                    <th class="px-4 py-2">No. Antrean</th>
                                    <th class="px-4 py-2">Nama Pelanggan</th>
                                    <th class="px-4 py-2">Layanan</th>
                                    <th class="px-4 py-2">Jam Kedatangan</th>
                                    <th class="px-4 py-2">Status</th>
                                    <th class="px-4 py-2 text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($reservasiTerbaru as $reservasi)
                                    <x-dashboard.activity-table-row :reservasi="$reservasi" />
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="space-y-3 sm:hidden">
                        @foreach ($reservasiTerbaru as $reservasi)
                            <x-dashboard.activity-card :reservasi="$reservasi" />
                        @endforeach
                    </div>
                @endif
            </x-card>

            {{-- Reservasi per Jam Kedatangan + Ringkasan Sistem --}}
            <div class="space-y-6">
                <x-card padding="p-6">
                    <x-slot:header>
                        <h2 class="font-display text-base font-semibold text-pln-navy-900">
                            Reservasi per Jam Kedatangan
                        </h2>
                        <span class="rounded-lg border border-pln-slate-200 px-3 py-1.5 text-xs font-medium text-pln-slate-500">
                            Hari Ini
                        </span>
                    </x-slot:header>

                    <x-dashboard.bar-chart :data="$reservasiPerJam" />
                </x-card>

                <x-card padding="p-6">
                    <x-slot:header>
                        <h2 class="font-display text-base font-semibold text-pln-navy-900">Ringkasan Sistem</h2>
                    </x-slot:header>

                    <div class="grid grid-cols-2 gap-3">
                        <x-dashboard.summary-mini-card
                            icon="bolt"
                            label="Total Layanan"
                            :value="$ringkasanSistem['total_layanan']"
                            :href="Route::has('admin.layanan.index') ? route('admin.layanan.index') : null"
                        />
                        <x-dashboard.summary-mini-card
                            icon="ticket"
                            icon-bg="bg-status-review/10"
                            icon-color="text-status-review"
                            label="Total Jadwal (Hari Ini)"
                            :value="$ringkasanSistem['total_jadwal']"
                            :href="Route::has('admin.jadwal.index') ? route('admin.jadwal.index') : null"
                        />
                        <x-dashboard.summary-mini-card
                            icon="users"
                            icon-bg="bg-status-done/10"
                            icon-color="text-status-done"
                            label="Total Pengguna"
                            :value="$ringkasanSistem['total_pengguna']"
                            :href="Route::has('admin.pengguna.index') ? route('admin.pengguna.index') : null"
                        />
                        <x-dashboard.summary-mini-card
                            icon="megaphone"
                            icon-bg="bg-pln-amber-500/10"
                            icon-color="text-pln-amber-600"
                            label="Pengumuman Aktif"
                            :value="$ringkasanSistem['pengumuman_aktif']"
                            :href="Route::has('admin.pengumuman.index') ? route('admin.pengumuman.index') : null"
                        />
                    </div>
                </x-card>
            </div>

        </div>

    </div>

@endsection