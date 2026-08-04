@extends('layouts.dashboard')

@section('title', 'Dashboard Customer Service')
@section('page-title', 'Dashboard Customer Service')
@section('page-subtitle', '')
@section('user-initial', 'C')
@section('user-name', 'CS. Amanda')
@section('user-role', 'Customer Service')

@section('content')

    <div class="space-y-6">

        <x-dashboard.welcome-banner nama="CS. Amanda" role="cs" />

        {{-- Kartu Statistik --}}
        <div class="grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-6">
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

            {{-- Reservasi per Jam Kedatangan --}}
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

        </div>

        {{-- Reservasi Terbaru --}}
        <x-card padding="p-6">
            <x-slot:header>
                <h2 class="font-display text-base font-semibold text-pln-navy-900">Reservasi Terbaru</h2>
                @if (Route::has('cs.reservasi.index'))
                    <a href="{{ route('cs.reservasi.index') }}" class="text-sm font-semibold text-pln-navy-700 hover:text-pln-navy-900">
                        Lihat Semua
                    </a>
                @else
                    <span class="text-sm font-semibold text-pln-slate-300" title="Halaman Daftar Reservasi CS akan tersedia pada sprint berikutnya">
                        Lihat Semua
                    </span>
                @endif
            </x-slot:header>

            <x-dashboard.tabs
                :tabs="[
                    'semua' => 'Semua',
                    'menunggu_review' => 'Menunggu Review',
                    'perlu_datang' => 'Perlu Datang',
                    'selesai_online' => 'Selesai Online',
                ]"
                :active="$tab"
            />

            <div class="mt-5">
                @if ($reservasiTerbaru->isEmpty())
                    <x-empty-state
                        title="Belum ada reservasi"
                        description="Reservasi pelanggan untuk tanggal dan status ini akan tampil di sini."
                    />
                @else
                    <div class="hidden overflow-x-auto sm:block">
                        <table class="w-full text-left">
                            <thead>
                                <tr class="border-b border-pln-slate-200 text-xs font-semibold uppercase tracking-wider text-pln-slate-400">
                                    <th class="px-4 py-2">No. Antrean</th>
                                    <th class="px-4 py-2">Kode Reservasi</th>
                                    <th class="px-4 py-2">Nama Pelanggan</th>
                                    <th class="px-4 py-2">Layanan</th>
                                    <th class="px-4 py-2">Jadwal</th>
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
            </div>
        </x-card>

    </div>

@endsection