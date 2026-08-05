@extends('layouts.dashboard')

@section('title', 'Reservasi')
@section('page-title', 'Reservasi')
@section('page-subtitle', 'Dashboard > Reservasi')
@section('user-initial', 'C')
@section('user-name', 'CS. Amanda')
@section('user-role', 'Customer Service')

@section('content')

    <div class="space-y-6">

        <x-card padding="p-6">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h1 class="font-display text-xl font-bold text-pln-navy-950">Reservasi</h1>
                    <p class="mt-1 text-sm text-pln-slate-600">Kelola semua reservasi pelanggan.</p>
                </div>

                <a
                    href="{{ route('cs.reservasi.export', request()->query()) }}"
                    class="flex items-center justify-center gap-2 rounded-lg border border-pln-slate-300 px-4 py-2.5 text-sm font-semibold text-pln-slate-700 transition hover:bg-pln-slate-50"
                >
                    <x-icon name="download" class="h-4 w-4" />
                    Export
                </a>
            </div>

            <div class="mt-5 flex items-center justify-between gap-3 border-b border-pln-slate-200">
                <div class="flex gap-1 overflow-x-auto" role="tablist">
                    <a
                        href="{{ route('cs.reservasi.index', ['tab' => 'aktif']) }}"
                        role="tab"
                        @class([
                            'whitespace-nowrap border-b-2 px-4 py-2.5 text-sm font-medium transition',
                            'border-pln-navy-700 text-pln-navy-900' => $tab === 'aktif',
                            'border-transparent text-pln-slate-500 hover:text-pln-navy-900' => $tab !== 'aktif',
                        ])
                        aria-selected="{{ $tab === 'aktif' ? 'true' : 'false' }}"
                    >
                        Reservasi Aktif
                    </a>
                    <a
                        href="{{ route('cs.reservasi.index', ['tab' => 'riwayat']) }}"
                        role="tab"
                        @class([
                            'whitespace-nowrap border-b-2 px-4 py-2.5 text-sm font-medium transition',
                            'border-pln-navy-700 text-pln-navy-900' => $tab === 'riwayat',
                            'border-transparent text-pln-slate-500 hover:text-pln-navy-900' => $tab !== 'riwayat',
                        ])
                        aria-selected="{{ $tab === 'riwayat' ? 'true' : 'false' }}"
                    >
                        Riwayat Reservasi
                    </a>
                </div>

                <button
                    type="button"
                    data-toggle-target="panel-filter-reservasi"
                    class="mb-2 flex shrink-0 items-center gap-2 rounded-lg border border-pln-slate-300 px-3 py-2 text-sm font-medium text-pln-slate-700 sm:hidden"
                    aria-expanded="false"
                    aria-controls="panel-filter-reservasi"
                >
                    <x-icon name="filter" class="h-4 w-4" />
                    Filter
                </button>
            </div>
        </x-card>

        <x-card padding="p-6">
            <x-cs-reservasi.filter-panel :layanans="$layanans" :opsi-status="$opsiStatus" :filters="$filters" />
        </x-card>

        {{-- Kartu Statistik --}}
        <div class="grid grid-cols-2 gap-4 lg:grid-cols-4">
            @if ($tab === 'aktif')
                <x-cs-reservasi.summary-card
                    icon="clock"
                    icon-bg="bg-status-review/10"
                    icon-color="text-status-review"
                    label="Menunggu Review"
                    :nilai="$statistik['menunggu_review']"
                />
                <x-cs-reservasi.summary-card
                    icon="walking"
                    icon-bg="bg-status-visit/10"
                    icon-color="text-status-visit"
                    label="Perlu Datang"
                    :nilai="$statistik['perlu_datang']"
                />
            @else
                <x-cs-reservasi.summary-card
                    icon="check"
                    icon-bg="bg-status-online/10"
                    icon-color="text-status-online"
                    label="Selesai Online"
                    :nilai="$statistik['selesai_online']"
                />
                <x-cs-reservasi.summary-card
                    icon="check-circle"
                    icon-bg="bg-violet-500/10"
                    icon-color="text-violet-600"
                    label="Selesai"
                    :nilai="$statistik['selesai']"
                />
                <x-cs-reservasi.summary-card
                    icon="x-mark"
                    icon-bg="bg-status-cancel/10"
                    icon-color="text-status-cancel"
                    label="Dibatalkan"
                    :nilai="$statistik['dibatalkan']"
                />
            @endif

            <x-cs-reservasi.summary-card
                icon="calendar"
                icon-bg="bg-pln-navy-600/10"
                icon-color="text-pln-navy-700"
                label="Total"
                :nilai="$statistik['total']"
            />
        </div>

        {{-- Daftar Reservasi --}}
        <x-card padding="p-6">
            <x-slot:header>
                <div>
                    <h2 class="font-display text-base font-semibold text-pln-navy-900">
                        Daftar {{ $tab === 'aktif' ? 'Reservasi Aktif' : 'Riwayat Reservasi' }}
                    </h2>
                </div>
            </x-slot:header>

            @if ($reservasis->isEmpty())
                <x-empty-state
                    title="Belum ada reservasi"
                    description="Tidak ada reservasi yang cocok dengan filter saat ini. Coba ubah kata kunci atau filter pencarian."
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