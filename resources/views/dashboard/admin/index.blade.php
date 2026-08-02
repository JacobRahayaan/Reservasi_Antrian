@extends('layouts.dashboard')

@section('title', 'Dashboard Admin')
@section('page-title', 'Dashboard Admin')

@section('content')

    <div class="space-y-6">

        <x-alert variant="info" title="Sprint 0 — Placeholder">
            Halaman ini adalah fondasi tampilan Dashboard Admin. Statistik, tabel, dan aksi nyata akan dibangun pada sprint fitur Admin.
        </x-alert>

        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
            <x-card>
                <p class="text-xs font-medium uppercase tracking-wider text-pln-slate-400">Jenis Layanan Aktif</p>
                <p class="mt-2 font-display text-2xl font-semibold text-pln-navy-900">—</p>
            </x-card>
            <x-card>
                <p class="text-xs font-medium uppercase tracking-wider text-pln-slate-400">Petugas Aktif</p>
                <p class="mt-2 font-display text-2xl font-semibold text-pln-navy-900">—</p>
            </x-card>
            <x-card>
                <p class="text-xs font-medium uppercase tracking-wider text-pln-slate-400">Slot Hari Ini</p>
                <p class="mt-2 font-display text-2xl font-semibold text-pln-navy-900">—</p>
            </x-card>
            <x-card>
                <p class="text-xs font-medium uppercase tracking-wider text-pln-slate-400">Reservasi Minggu Ini</p>
                <p class="mt-2 font-display text-2xl font-semibold text-pln-navy-900">—</p>
            </x-card>
        </div>

        <x-empty-state
            title="Statistik layanan belum tersedia"
            description="Data akan tampil di sini setelah modul statistik layanan Admin dikembangkan pada sprint berikutnya."
        />

    </div>

@endsection