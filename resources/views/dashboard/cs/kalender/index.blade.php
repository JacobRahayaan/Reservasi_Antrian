@extends('layouts.dashboard')

@section('title', 'Kalender Jadwal')
@section('page-title', 'Kalender Jadwal')
@section('page-subtitle', 'Dashboard > Kalender Jadwal')
@section('user-initial', 'C')
@section('user-name', 'CS. Amanda')
@section('user-role', 'Customer Service')

@section('content')

    @php
        $bulanSebelumnya = $bulan->subMonth()->format('Y-m');
        $bulanBerikutnya = $bulan->addMonth()->format('Y-m');
    @endphp

    <div class="space-y-6">

        <x-card padding="p-6">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h1 class="font-display text-xl font-bold text-pln-navy-950">Kalender Jadwal</h1>
                    <p class="mt-1 text-sm text-pln-slate-600">
                        Ringkasan slot dan kuota reservasi per tanggal. Klik tanggal untuk melihat reservasi hari itu.
                    </p>
                </div>

                <div class="flex items-center gap-2">
                    <a href="{{ route('cs.kalender.index', ['bulan' => $bulanSebelumnya]) }}" class="flex h-9 w-9 items-center justify-center rounded-lg border border-pln-slate-200 text-pln-slate-600 transition hover:bg-pln-slate-100" aria-label="Bulan sebelumnya">
                        <x-icon name="chevron-right" class="h-4 w-4 rotate-180" />
                    </a>
                    <span class="w-36 text-center text-sm font-semibold text-pln-navy-900">{{ $bulan->translatedFormat('F Y') }}</span>
                    <a href="{{ route('cs.kalender.index', ['bulan' => $bulanBerikutnya]) }}" class="flex h-9 w-9 items-center justify-center rounded-lg border border-pln-slate-200 text-pln-slate-600 transition hover:bg-pln-slate-100" aria-label="Bulan berikutnya">
                        <x-icon name="chevron-right" class="h-4 w-4" />
                    </a>
                    <a href="{{ route('cs.kalender.index') }}" class="rounded-lg border border-pln-slate-300 px-3 py-2 text-sm font-medium text-pln-slate-700 transition hover:bg-pln-slate-50">
                        Bulan Ini
                    </a>
                </div>
            </div>
        </x-card>

        <div class="grid grid-cols-2 gap-4 lg:grid-cols-4">
            <x-card padding="p-5">
                <p class="text-xs font-medium uppercase tracking-wider text-pln-slate-400">Total Slot</p>
                <p class="mt-1 font-display text-2xl font-bold text-pln-navy-950">{{ $ringkasan['total_slot'] }}</p>
            </x-card>
            <x-card padding="p-5">
                <p class="text-xs font-medium uppercase tracking-wider text-pln-slate-400">Total Kuota</p>
                <p class="mt-1 font-display text-2xl font-bold text-pln-navy-950">{{ $ringkasan['total_kuota'] }}</p>
            </x-card>
            <x-card padding="p-5">
                <p class="text-xs font-medium uppercase tracking-wider text-pln-slate-400">Kuota Terisi</p>
                <p class="mt-1 font-display text-2xl font-bold text-pln-navy-950">{{ $ringkasan['total_terisi'] }}</p>
            </x-card>
            <x-card padding="p-5">
                <p class="text-xs font-medium uppercase tracking-wider text-pln-slate-400">Sisa Kuota</p>
                <p class="mt-1 font-display text-2xl font-bold text-pln-navy-950">{{ $ringkasan['total_sisa'] }}</p>
            </x-card>
        </div>

        <x-card padding="p-6">
            <x-kalender.month-grid :hari-data="$hariData" :bulan="$bulan" :href-builder="$hrefBuilder" />

            <div class="mt-5 flex flex-wrap items-center gap-4 border-t border-pln-slate-100 pt-4 text-xs text-pln-slate-500">
                <span class="flex items-center gap-1.5"><span class="h-2.5 w-2.5 rounded-full bg-status-done"></span> Kuota masih longgar</span>
                <span class="flex items-center gap-1.5"><span class="h-2.5 w-2.5 rounded-full bg-pln-amber-500"></span> Kuota mulai menipis</span>
                <span class="flex items-center gap-1.5"><span class="h-2.5 w-2.5 rounded-full bg-status-cancel"></span> Kuota hampir penuh</span>
                <span class="flex items-center gap-1.5"><span class="h-2.5 w-2.5 rounded-full bg-pln-slate-300"></span> Tidak ada jadwal</span>
            </div>
        </x-card>

    </div>

@endsection