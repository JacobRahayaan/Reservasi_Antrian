@extends('layouts.dashboard')

@section('title', 'Dashboard Customer Service')
@section('page-title', 'Dashboard Customer Service')

@section('content')

    <div class="space-y-6">

        <x-alert variant="info" title="Sprint 0 — Placeholder">
            Halaman ini adalah fondasi tampilan Dashboard Customer Service. Daftar reservasi dan alur review akan dibangun pada sprint fitur CS.
        </x-alert>

        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
            <x-card>
                <p class="text-xs font-medium uppercase tracking-wider text-pln-slate-400">Menunggu Review</p>
                <p class="mt-2 font-display text-2xl font-semibold text-status-review">—</p>
            </x-card>
            <x-card>
                <p class="text-xs font-medium uppercase tracking-wider text-pln-slate-400">Perlu Datang</p>
                <p class="mt-2 font-display text-2xl font-semibold text-status-visit">—</p>
            </x-card>
            <x-card>
                <p class="text-xs font-medium uppercase tracking-wider text-pln-slate-400">Selesai Online</p>
                <p class="mt-2 font-display text-2xl font-semibold text-status-online">—</p>
            </x-card>
            <x-card>
                <p class="text-xs font-medium uppercase tracking-wider text-pln-slate-400">Selesai</p>
                <p class="mt-2 font-display text-2xl font-semibold text-status-done">—</p>
            </x-card>
        </div>

        <x-empty-state
            title="Belum ada reservasi untuk direview"
            description="Reservasi yang masuk akan muncul di sini, diurutkan berdasarkan jadwal kedatangan."
        />

    </div>

@endsection