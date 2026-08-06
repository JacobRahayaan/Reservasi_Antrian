@extends('layouts.dashboard')

@section('title', 'Laporan')
@section('page-title', 'Laporan')
@section('page-subtitle', 'Dashboard > Laporan')
@section('user-initial', 'A')
@section('user-name', 'Admin')
@section('user-role', 'Administrator')

@section('content')

    <div class="space-y-6">

        <x-card padding="p-6">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h1 class="font-display text-xl font-bold text-pln-navy-950">Laporan</h1>
                    <p class="mt-1 text-sm text-pln-slate-600">Statistik reservasi berdasarkan periode tanggal kedatangan.</p>
                </div>

                <a
                    href="{{ route('admin.laporan.export', request()->query()) }}"
                    class="flex items-center justify-center gap-2 rounded-lg border border-pln-slate-300 px-4 py-2.5 text-sm font-semibold text-pln-slate-700 transition hover:bg-pln-slate-50"
                >
                    <x-icon name="download" class="h-4 w-4" />
                    Export
                </a>
            </div>

            <form method="GET" class="mt-5 grid gap-4 sm:grid-cols-3 lg:items-end">
                <div>
                    <label for="tanggal_mulai" class="mb-1.5 block text-sm font-medium text-pln-slate-900">Tanggal Mulai</label>
                    <input type="date" name="tanggal_mulai" id="tanggal_mulai" value="{{ $filters['tanggal_mulai'] }}" class="w-full rounded-lg border border-pln-slate-200 px-3.5 py-2.5 text-sm text-pln-slate-700 focus:border-pln-navy-700 focus:outline-none focus:ring-2 focus:ring-pln-navy-700/20">
                </div>
                <div>
                    <label for="tanggal_akhir" class="mb-1.5 block text-sm font-medium text-pln-slate-900">Tanggal Akhir</label>
                    <input type="date" name="tanggal_akhir" id="tanggal_akhir" value="{{ $filters['tanggal_akhir'] }}" class="w-full rounded-lg border border-pln-slate-200 px-3.5 py-2.5 text-sm text-pln-slate-700 focus:border-pln-navy-700 focus:outline-none focus:ring-2 focus:ring-pln-navy-700/20">
                </div>
                <div class="flex gap-2">
                    <div class="flex-1">
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
                    <button type="submit" class="mt-6 shrink-0 rounded-lg bg-pln-navy-900 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-pln-navy-800">
                        Terapkan
                    </button>
                </div>
            </form>
        </x-card>

        {{-- KPI --}}
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
            <x-laporan.kpi-card
                icon="check"
                icon-bg="bg-status-online/10"
                icon-color="text-status-online"
                label="Tingkat Penyelesaian Online"
                :persentase="$kpi['persen_selesai_online']"
                keterangan="dari reservasi yang selesai"
            />
            <x-laporan.kpi-card
                icon="check-circle"
                icon-bg="bg-status-done/10"
                icon-color="text-status-done"
                label="Tingkat Penyelesaian"
                :persentase="$kpi['persen_penyelesaian']"
                keterangan="dari total reservasi periode ini"
            />
            <x-laporan.kpi-card
                icon="x-mark"
                icon-bg="bg-status-cancel/10"
                icon-color="text-status-cancel"
                label="Tingkat Pembatalan"
                :persentase="$kpi['persen_pembatalan']"
                keterangan="dari total reservasi periode ini"
            />
        </div>

        <div class="grid gap-6 lg:grid-cols-2">
            <x-card padding="p-6">
                <x-slot:header>
                    <h2 class="font-display text-base font-semibold text-pln-navy-900">Tren Reservasi Harian</h2>
                </x-slot:header>
                <x-dashboard.line-chart :data="$trenHarian" />
            </x-card>

            <x-card padding="p-6">
                <x-slot:header>
                    <h2 class="font-display text-base font-semibold text-pln-navy-900">Distribusi per Layanan</h2>
                </x-slot:header>
                <x-dashboard.donut-chart :data="$distribusiLayanan" :total="$totalDistribusiLayanan" />
            </x-card>
        </div>

        <x-card padding="p-6">
            <x-slot:header>
                <h2 class="font-display text-base font-semibold text-pln-navy-900">Ringkasan per Layanan</h2>
            </x-slot:header>

            @if ($ringkasanStatus['total'] === 0)
                <x-empty-state
                    title="Belum ada data pada periode ini"
                    description="Ubah rentang tanggal atau filter layanan untuk melihat data lain."
                />
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="border-b border-pln-slate-200 text-xs font-semibold uppercase tracking-wider text-pln-slate-400">
                                <th class="px-4 py-2.5">Layanan</th>
                                <th class="px-4 py-2.5">Total</th>
                                <th class="px-4 py-2.5">Menunggu Review</th>
                                <th class="px-4 py-2.5">Perlu Datang</th>
                                <th class="px-4 py-2.5">Selesai Online</th>
                                <th class="px-4 py-2.5">Selesai</th>
                                <th class="px-4 py-2.5">Dibatalkan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($ringkasanPerLayanan as $baris)
                                <tr class="border-b border-pln-slate-100 last:border-0">
                                    <td class="px-4 py-3 text-sm font-semibold text-pln-navy-900">{{ $baris['nama_layanan'] }}</td>
                                    <td class="px-4 py-3 text-sm text-pln-slate-700">{{ $baris['total'] }}</td>
                                    <td class="px-4 py-3 text-sm text-pln-slate-700">{{ $baris['menunggu_review'] }}</td>
                                    <td class="px-4 py-3 text-sm text-pln-slate-700">{{ $baris['perlu_datang'] }}</td>
                                    <td class="px-4 py-3 text-sm text-pln-slate-700">{{ $baris['selesai_online'] }}</td>
                                    <td class="px-4 py-3 text-sm text-pln-slate-700">{{ $baris['selesai'] }}</td>
                                    <td class="px-4 py-3 text-sm text-pln-slate-700">{{ $baris['dibatalkan'] }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </x-card>

    </div>

@endsection