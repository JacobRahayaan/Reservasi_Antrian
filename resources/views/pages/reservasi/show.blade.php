@extends('layouts.public')

@section('title', 'Detail Reservasi — ' . $reservasi->nomor_antrean)
@section('meta_description', 'Detail reservasi dan status layanan pelanggan PLN Anda.')

@section('content')

    <div class="border-b border-pln-slate-200 bg-pln-slate-100/60 print:hidden">
        <div class="mx-auto max-w-6xl px-4 py-6 sm:px-6 lg:px-8">
            <x-reservasi.breadcrumb :items="[
                ['label' => 'Beranda', 'href' => route('landing')],
                ['label' => 'Reservasi Saya', 'href' => route('reservasi.create')],
                ['label' => 'Detail Reservasi'],
            ]" />

            <div class="mt-4 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h1 class="font-display text-2xl font-bold tracking-tight text-pln-navy-950 sm:text-3xl">
                        Detail Reservasi
                    </h1>
                    <p class="mt-1.5 text-sm text-pln-slate-600">
                        Berikut detail reservasi layanan Anda.
                    </p>
                </div>

                <div class="hidden shrink-0 items-center gap-3 sm:flex">
                    <button
                        type="button"
                        onclick="window.print()"
                        class="inline-flex items-center gap-2 rounded-lg border border-pln-slate-300 bg-white px-4 py-2.5 text-sm font-semibold text-pln-navy-800 transition hover:bg-pln-slate-50"
                    >
                        <svg viewBox="0 0 24 24" class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v12m0 0 4-4m-4 4-4-4M4 17v2a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-2" />
                        </svg>
                        Unduh Bukti Reservasi
                    </button>

                    <button
                        type="button"
                        disabled
                        title="Pembatalan reservasi tersedia pada sprint fitur berikutnya"
                        class="inline-flex cursor-not-allowed items-center gap-2 rounded-lg border border-status-cancel/30 bg-white px-4 py-2.5 text-sm font-semibold text-status-cancel opacity-60"
                    >
                        <svg viewBox="0 0 24 24" class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 7h16M9 7V5a2 2 0 0 1 2-2h2a2 2 0 0 1 2 2v2m2 0v12a2 2 0 0 1-2 2H9a2 2 0 0 1-2-2V7h10Z" />
                        </svg>
                        Batalkan Reservasi
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="mx-auto max-w-6xl px-4 py-8 sm:px-6 lg:px-8">

        @if (session('success'))
            <x-alert variant="success" title="Berhasil" dismissible class="mb-6 print:hidden">
                {{ session('success') }}
            </x-alert>
        @endif

        <div class="grid gap-6 lg:grid-cols-3">

            {{-- Kartu Nomor Antrean --}}
            <x-card padding="p-6" class="bg-pln-navy-900/5 text-center">
                <p class="text-xs font-semibold uppercase tracking-wider text-pln-navy-700">Nomor Antrean Anda</p>
                <p class="mt-2 font-mono text-5xl font-bold text-pln-navy-950">
                    {{ substr($reservasi->nomor_antrean, 0, 1) }}-{{ substr($reservasi->nomor_antrean, 1) }}
                </p>

                <div class="mt-3 flex justify-center">
                    <x-badge :variant="$reservasi->status->badgeVariant()" class="text-sm">
                        {{ $reservasi->status->label() }}
                    </x-badge>
                </div>

                <p class="mx-auto mt-4 max-w-xs text-sm leading-relaxed text-pln-slate-600">
                    Harap datang sesuai jadwal.
                    <span class="font-semibold text-pln-navy-900">Nomor antrean akan dipanggil sesuai urutan.</span>
                </p>

				<img
					src="{{ asset('images/waiting-person.png') }}"
					alt="Ilustrasi Menunggu Antrean"
					class="mx-auto mt-5 max-h-56 w-full max-w-xs object-contain"
				/>

                <div class="mt-5 flex flex-col gap-2.5 sm:hidden">
                    <button
                        type="button"
                        onclick="window.print()"
                        class="inline-flex items-center justify-center gap-2 rounded-lg border border-pln-slate-300 bg-white px-4 py-2.5 text-sm font-semibold text-pln-navy-800"
                    >
                        <svg viewBox="0 0 24 24" class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v12m0 0 4-4m-4 4-4-4M4 17v2a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-2" />
                        </svg>
                        Unduh Bukti
                    </button>
                    <button
                        type="button"
                        disabled
                        title="Pembatalan reservasi tersedia pada sprint fitur berikutnya"
                        class="inline-flex cursor-not-allowed items-center justify-center gap-2 rounded-lg border border-status-cancel/30 bg-white px-4 py-2.5 text-sm font-semibold text-status-cancel opacity-60"
                    >
                        Batalkan
                    </button>
                </div>
            </x-card>

            {{-- Kartu Informasi Reservasi --}}
            <x-card padding="p-6">
                <x-slot:header>
                    <h2 class="flex items-center gap-2 font-display text-base font-semibold text-pln-navy-900">
                        <x-icon name="document-text" class="h-5 w-5 text-pln-navy-700" />
                        Informasi Reservasi
                    </h2>

                    <button
                        type="button"
                        data-toggle-target="informasi-reservasi-content"
                        class="flex h-8 w-8 items-center justify-center rounded-lg text-pln-slate-400 transition hover:bg-pln-slate-100 lg:hidden"
                        aria-expanded="true"
                        aria-controls="informasi-reservasi-content"
                        aria-label="Tampilkan/sembunyikan detail informasi reservasi"
                    >
                        <svg data-toggle-icon viewBox="0 0 24 24" class="h-4 w-4 transition-transform" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m18 15-6-6-6 6" />
                        </svg>
                    </button>
                </x-slot:header>

                <div id="informasi-reservasi-content" class="grid gap-x-6 gap-y-5 lg:!grid lg:grid-cols-2">
                    <x-reservasi.info-row icon="ticket" label="Kode Reservasi" :value="$reservasi->kode_reservasi" />
                    <x-reservasi.info-row icon="user" label="Nama" :value="$reservasi->nama" />
                    <x-reservasi.info-row icon="bolt" label="Jenis Layanan" :value="$reservasi->layanan->nama_layanan" />
                    <x-reservasi.info-row icon="phone" label="Nomor HP" :value="$reservasi->nomor_hp" />
                    <x-reservasi.info-row icon="clock" label="Tanggal Reservasi" :value="$reservasi->jadwal->tanggal->translatedFormat('d F Y')" />
                    <x-reservasi.info-row icon="envelope" label="Email" :value="$reservasi->email ?? '-'" />
                    <x-reservasi.info-row icon="clock" label="Jam Kedatangan" :value="substr($reservasi->jadwal->jam_mulai, 0, 5) . ' - ' . substr($reservasi->jadwal->jam_selesai, 0, 5)" />
                    <x-reservasi.info-row icon="clock" label="Dibuat Pada" :value="$reservasi->created_at->translatedFormat('d M Y - H:i') . ' WIB'" />
                </div>
            </x-card>

            {{-- Kartu Status Reservasi --}}
            <x-card padding="p-6">
                <x-slot:header>
                    <h2 class="flex items-center gap-2 font-display text-base font-semibold text-pln-navy-900">
                        <x-icon name="ticket" class="h-5 w-5 text-pln-navy-700" />
                        Status Reservasi
                    </h2>
                </x-slot:header>

                <x-reservasi.status-timeline :reservasi="$reservasi" />
            </x-card>

        </div>

        <div class="mt-6 grid gap-6 lg:grid-cols-3">

            {{-- Kartu Keluhan --}}
            <x-card padding="p-6">
                <x-slot:header>
                    <h2 class="flex items-center gap-2 font-display text-base font-semibold text-pln-navy-900">
                        <x-icon name="document-text" class="h-5 w-5 text-pln-navy-700" />
                        Keluhan / Keterangan
                    </h2>
                </x-slot:header>

                <p class="text-sm leading-relaxed text-pln-slate-700">
                    {{ $reservasi->keluhan }}
                </p>
            </x-card>

            {{-- Kartu Dokumen --}}
            <x-card padding="p-6">
                <x-slot:header>
                    <h2 class="flex items-center gap-2 font-display text-base font-semibold text-pln-navy-900">
                        <x-icon name="document-text" class="h-5 w-5 text-pln-navy-700" />
                        Dokumen yang Diunggah
                    </h2>

                    @if ($reservasi->dokumen->count() > 3)
                        <button
                            type="button"
                            data-dokumen-toggle
                            class="text-sm font-semibold text-pln-navy-700 hover:text-pln-navy-900"
                        >
                            Lihat Semua
                        </button>
                    @endif
                </x-slot:header>

                @if ($reservasi->dokumen->isEmpty())
                    <x-empty-state
                        title="Belum ada dokumen"
                        description="Anda tidak mengunggah dokumen pendukung saat membuat reservasi ini."
                    />
                @else
                    <div class="space-y-2.5">
                        @foreach ($reservasi->dokumen as $dokumen)
                            <div @class(['hidden' => $loop->index >= 3])>
                                <x-reservasi.document-item
                                    :dokumen="$dokumen"
                                    :download-url="route('reservasi.dokumen.download', ['reservasi' => $reservasi, 'dokumen' => $dokumen])"
                                />
                            </div>
                        @endforeach
                    </div>
                @endif
            </x-card>

            {{-- Kartu Catatan Petugas --}}
            <x-card padding="p-6">
                <x-slot:header>
                    <h2 class="flex items-center gap-2 font-display text-base font-semibold text-pln-navy-900">
                        <x-icon name="headphones" class="h-5 w-5 text-pln-navy-700" />
                        Catatan Terakhir dari Petugas
                    </h2>
                </x-slot:header>

                <x-reservasi.note-card :note="$reservasi->notes->first()" />
            </x-card>

        </div>

        {{-- Informasi Penting --}}
        <div class="mt-6 rounded-2xl bg-pln-slate-100/80 p-6">
            <h2 class="flex items-center gap-2 font-display text-base font-semibold text-pln-navy-900">
                <x-icon name="clock" class="h-5 w-5 text-pln-navy-700" />
                Informasi Penting
            </h2>

            <div class="mt-5 grid gap-5 sm:grid-cols-2 lg:grid-cols-4">
                <x-reservasi.info-highlight-bar
                    icon="bolt"
                    text="Harap datang 15 menit sebelum jadwal kedatangan Anda."
                />
                <x-reservasi.info-highlight-bar
                    icon="document-text"
                    text="Bawa dokumen asli sesuai persyaratan layanan."
                />
                <x-reservasi.info-highlight-bar
                    icon="ticket"
                    text="Nomor antrean akan dipanggil sesuai urutan."
                />
                <x-reservasi.info-highlight-bar
                    icon="clock"
                    text="Jika tidak hadir, reservasi akan dibatalkan otomatis."
                />
            </div>
        </div>

        {{-- Bantuan --}}
        <div class="mt-6 print:hidden">
            <x-landing.help-banner />
        </div>

        {{-- Aksi Navigasi --}}
        <div class="mt-6 flex flex-col gap-3 sm:flex-row print:hidden">
            <x-button href="{{ route('landing') }}" variant="ghost" size="md">
                <svg viewBox="0 0 24 24" class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                </svg>
                Kembali ke Beranda
            </x-button>
            <x-button href="{{ route('reservasi.create') }}" variant="primary" size="md">
                Buat Reservasi Baru
                <x-icon name="arrow-right" class="h-4 w-4" />
            </x-button>
        </div>

    </div>

@endsection