@extends('layouts.dashboard')

@section('title', 'Detail Pengumuman')
@section('page-title', 'Detail Pengumuman')
@section('page-subtitle', 'Dashboard > Pengumuman > Detail')
@section('user-initial', 'A')
@section('user-name', 'Admin')
@section('user-role', 'Administrator')

@section('content')

    @php
        $badgeMap = [
            'aktif' => ['variant' => 'done', 'label' => 'Aktif'],
            'terjadwal' => ['variant' => 'review', 'label' => 'Terjadwal'],
            'berakhir' => ['variant' => 'cancel', 'label' => 'Berakhir'],
            'nonaktif' => ['variant' => 'neutral', 'label' => 'Nonaktif'],
        ];

        $badge = $badgeMap[$pengumuman->status_tampilan] ?? $badgeMap['nonaktif'];
    @endphp

    <div class="space-y-6">

        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <x-button href="{{ route('admin.pengumuman.index') }}" variant="ghost" size="sm">
                <svg viewBox="0 0 24 24" class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                </svg>
                Kembali ke Daftar Pengumuman
            </x-button>

            <x-button href="{{ route('admin.pengumuman.edit', $pengumuman) }}" variant="primary" size="sm">
                <x-icon name="pencil-square" class="h-4 w-4" />
                Ubah Pengumuman
            </x-button>
        </div>

        <x-card padding="p-6">
            <div class="flex flex-col gap-5 sm:flex-row sm:items-start sm:justify-between">
                <div class="flex items-start gap-4">
                    <span class="flex h-14 w-14 shrink-0 items-center justify-center rounded-full bg-pln-navy-600/10 text-pln-navy-700">
                        <x-icon name="megaphone" class="h-7 w-7" />
                    </span>
                    <div>
                        <h1 class="font-display text-xl font-bold text-pln-navy-950">{{ $pengumuman->judul }}</h1>
                        <p class="mt-2 max-w-lg whitespace-pre-line text-sm leading-relaxed text-pln-slate-600">
                            {{ $pengumuman->isi }}
                        </p>
                    </div>
                </div>

                <x-badge :variant="$badge['variant']" class="text-sm">{{ $badge['label'] }}</x-badge>
            </div>

            <div class="mt-6 grid gap-x-6 gap-y-5 border-t border-pln-slate-100 pt-6 sm:grid-cols-2 lg:grid-cols-4">
                <x-reservasi.info-row icon="calendar" label="Mulai Tayang" :value="$pengumuman->tanggal_mulai->translatedFormat('d F Y')" />
                <x-reservasi.info-row icon="calendar" label="Selesai Tayang" :value="$pengumuman->tanggal_selesai?->translatedFormat('d F Y') ?? 'Tanpa batas waktu'" />
                <x-reservasi.info-row icon="clock" label="Dibuat Pada" :value="$pengumuman->created_at->translatedFormat('d M Y, H:i') . ' WIB'" />
                <x-reservasi.info-row icon="clock" label="Terakhir Diubah" :value="$pengumuman->updated_at->translatedFormat('d M Y, H:i') . ' WIB'" />
            </div>
        </x-card>

    </div>

@endsection