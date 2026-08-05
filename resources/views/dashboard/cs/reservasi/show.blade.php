@extends('layouts.dashboard')

@section('title', 'Detail Reservasi')
@section('page-title', 'Detail Reservasi')
@section('page-subtitle', 'Dashboard > Daftar Reservasi > Detail Reservasi')
@section('user-initial', 'C')
@section('user-name', 'CS. Amanda')
@section('user-role', 'Customer Service')

@section('content')

    <div class="space-y-6">

        @if (session('success'))
            <x-alert variant="success" title="Berhasil" dismissible>{{ session('success') }}</x-alert>
        @endif

        @if ($errors->any())
            <x-alert variant="danger" title="Terjadi kesalahan">
                <ul class="list-inside list-disc space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </x-alert>
        @endif

        <x-reservasi.breadcrumb :items="[
            ['label' => 'Dashboard', 'href' => route('cs.dashboard')],
            ['label' => 'Daftar Reservasi', 'href' => route('cs.reservasi.index')],
            ['label' => 'Detail Reservasi'],
        ]" />

        {{-- Kartu Nomor Antrean + Aksi Header --}}
        <x-card padding="p-6">
            <div class="flex flex-col gap-5 lg:flex-row lg:items-start lg:justify-between">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wider text-pln-navy-700">Nomor Antrean</p>
                    <p class="mt-1 font-mono text-4xl font-bold text-pln-navy-950">
                        {{ substr($reservasi->nomor_antrean, 0, 1) }}-{{ substr($reservasi->nomor_antrean, 1) }}
                    </p>
                    <div class="mt-2 flex flex-wrap items-center gap-3">
                        <x-badge :variant="$reservasi->status->badgeVariant()" class="text-sm">
                            {{ $reservasi->status->label() }}
                        </x-badge>
                        <span class="text-sm text-pln-slate-500">Nomor antrean akan dipanggil sesuai urutan</span>
                    </div>
                    <p class="mt-2 flex items-center gap-1.5 text-sm text-pln-slate-500">
                        <x-icon name="calendar" class="h-4 w-4" />
                        {{ $reservasi->jadwal->tanggal->translatedFormat('d M Y') }} &middot;
                        {{ substr($reservasi->jadwal->jam_mulai, 0, 5) }} - {{ substr($reservasi->jadwal->jam_selesai, 0, 5) }}
                    </p>
                </div>

                <div class="flex shrink-0 gap-3">
                    <a
                        href="{{ route('cs.reservasi.export', ['tab' => in_array($reservasi->status->value, ['menunggu_review', 'perlu_datang']) ? 'aktif' : 'riwayat']) }}"
                        class="inline-flex items-center gap-2 rounded-lg border border-pln-slate-300 bg-white px-4 py-2.5 text-sm font-semibold text-pln-navy-800 transition hover:bg-pln-slate-50"
                    >
                        <x-icon name="download" class="h-4 w-4" />
                        Unduh Bukti
                    </a>
                    <a
                        href="#ubah-status-reservasi"
                        class="inline-flex items-center gap-2 rounded-lg border border-status-cancel/30 bg-white px-4 py-2.5 text-sm font-semibold text-status-cancel transition hover:bg-status-cancel/5"
                    >
                        <x-icon name="x-mark" class="h-4 w-4" />
                        Batalkan Reservasi
                    </a>
                </div>
            </div>

            <div class="mt-6 border-t border-pln-slate-100 pt-6">
                <x-cs-reservasi.status-progress :reservasi="$reservasi" />
            </div>
        </x-card>

        <div class="grid gap-6 lg:grid-cols-3">

            {{-- Informasi Pelanggan --}}
            <x-card padding="p-6">
                <x-slot:header>
                    <h2 class="flex items-center gap-2 font-display text-base font-semibold text-pln-navy-900">
                        <x-icon name="user" class="h-5 w-5 text-pln-navy-700" />
                        Informasi Pelanggan
                    </h2>
                </x-slot:header>

                <div class="space-y-4">
                    <x-reservasi.info-row icon="user" label="Nama Lengkap" :value="$reservasi->nama" />
                    <div class="flex items-start gap-3">
                        <span class="mt-0.5 flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-pln-navy-900/5 text-pln-navy-700">
                            <x-icon name="phone" class="h-4 w-4" />
                        </span>
                        <div class="min-w-0">
                            <p class="text-xs font-medium uppercase tracking-wider text-pln-slate-400">Nomor HP</p>
                            <p class="mt-0.5 flex items-center gap-2 text-sm font-semibold text-pln-slate-900">
                                {{ $reservasi->nomor_hp }}
                                <a
                                    href="https://wa.me/{{ preg_replace('/^0/', '62', preg_replace('/\D/', '', $reservasi->nomor_hp)) }}"
                                    target="_blank"
                                    rel="noopener"
                                    class="text-status-done"
                                    aria-label="Hubungi via WhatsApp"
                                >
                                    <svg viewBox="0 0 24 24" class="h-4 w-4" fill="currentColor" aria-hidden="true">
                                        <path d="M12 2a10 10 0 0 0-8.6 15L2 22l5.2-1.4A10 10 0 1 0 12 2Zm0 18.2c-1.6 0-3.1-.4-4.5-1.2l-.3-.2-3 .8.8-3-.2-.3A8.2 8.2 0 1 1 12 20.2Zm4.5-6.2c-.2-.1-1.5-.7-1.7-.8-.2-.1-.4-.1-.6.1-.2.2-.7.8-.8.9-.1.2-.3.2-.5.1-.2-.1-1-.4-1.9-1.2-.7-.6-1.2-1.4-1.3-1.6-.1-.2 0-.3.1-.5l.4-.5c.1-.1.1-.3.1-.4 0-.1-.6-1.4-.8-1.9-.2-.5-.4-.4-.6-.4h-.5c-.2 0-.4.1-.6.3-.2.2-.8.8-.8 1.9s.8 2.2 1 2.4c.1.1 1.6 2.4 3.9 3.4.5.2 1 .4 1.3.5.5.1 1 .1 1.4.1.4-.1 1.3-.5 1.5-1 .2-.5.2-.9.1-1Z"/>
                                    </svg>
                                </a>
                            </p>
                        </div>
                    </div>
                    <x-reservasi.info-row icon="envelope" label="Email" :value="$reservasi->email ?? '-'" />
                </div>
            </x-card>

            {{-- Informasi Reservasi --}}
            <x-card padding="p-6">
                <x-slot:header>
                    <h2 class="flex items-center gap-2 font-display text-base font-semibold text-pln-navy-900">
                        <x-icon name="document-text" class="h-5 w-5 text-pln-navy-700" />
                        Informasi Reservasi
                    </h2>
                </x-slot:header>

                <div class="space-y-4">
                    <x-reservasi.info-row icon="ticket" label="Kode Reservasi" :value="$reservasi->kode_reservasi" />
                    <x-reservasi.info-row icon="bolt" label="Jenis Layanan" :value="$reservasi->layanan->nama_layanan" />
                    <x-reservasi.info-row icon="calendar" label="Tanggal Reservasi" :value="$reservasi->jadwal->tanggal->translatedFormat('d M Y')" />
                    <x-reservasi.info-row icon="clock" label="Jam Kedatangan" :value="substr($reservasi->jadwal->jam_mulai, 0, 5) . ' - ' . substr($reservasi->jadwal->jam_selesai, 0, 5)" />
                    <x-reservasi.info-row icon="clock" label="Dibuat Pada" :value="$reservasi->created_at->translatedFormat('d M Y - H:i') . ' WIB'" />
                </div>
            </x-card>

            {{-- Keluhan --}}
            <x-card padding="p-6">
                <x-slot:header>
                    <h2 class="flex items-center gap-2 font-display text-base font-semibold text-pln-navy-900">
                        <x-icon name="document-text" class="h-5 w-5 text-pln-navy-700" />
                        Keluhan / Keterangan
                    </h2>
                </x-slot:header>

                <p class="text-sm leading-relaxed text-pln-slate-700">{{ $reservasi->keluhan }}</p>
            </x-card>

        </div>

        <div class="grid gap-6 lg:grid-cols-2">

            {{-- Dokumen --}}
            <x-card padding="p-6">
                <x-slot:header>
                    <h2 class="flex items-center gap-2 font-display text-base font-semibold text-pln-navy-900">
                        <x-icon name="document-text" class="h-5 w-5 text-pln-navy-700" />
                        Dokumen yang Diunggah
                    </h2>
                </x-slot:header>

                @if ($reservasi->dokumen->isEmpty())
                    <x-empty-state
                        title="Belum ada dokumen"
                        description="Pelanggan tidak mengunggah dokumen pendukung saat membuat reservasi ini."
                    />
                @else
                    <div class="space-y-2.5">
                        @foreach ($reservasi->dokumen as $dokumen)
                            <x-cs-reservasi.document-item :dokumen="$dokumen" :reservasi="$reservasi" />
                        @endforeach
                    </div>
                @endif
            </x-card>

            {{-- Riwayat Status --}}
            <x-card padding="p-6">
                <x-slot:header>
                    <h2 class="flex items-center gap-2 font-display text-base font-semibold text-pln-navy-900">
                        <x-icon name="clock" class="h-5 w-5 text-pln-navy-700" />
                        Riwayat Status
                    </h2>
                </x-slot:header>

                <x-reservasi.status-timeline :reservasi="$reservasi" />
            </x-card>

        </div>

        <div class="grid gap-6 lg:grid-cols-2">

            {{-- Form Catatan Baru + Riwayat Catatan --}}
            <x-card padding="p-6">
                <x-slot:header>
                    <h2 class="flex items-center gap-2 font-display text-base font-semibold text-pln-navy-900">
                        <x-icon name="document-text" class="h-5 w-5 text-pln-navy-700" />
                        Catatan Customer Service
                    </h2>
                </x-slot:header>

                <form action="{{ route('cs.reservasi.catatan.store', $reservasi) }}" method="POST">
                    @csrf

                    <div class="mb-1.5 flex items-center justify-between">
                        <label for="isi_catatan" class="text-sm font-medium text-pln-slate-900">Tulis catatan baru</label>
                        <span data-char-counter="catatan" class="text-xs text-pln-slate-400">0 / 1000</span>
                    </div>
                    <textarea
                        name="isi_catatan"
                        id="isi_catatan"
                        rows="4"
                        maxlength="1000"
                        data-char-count-target="catatan"
                        placeholder="Tulis catatan untuk pelanggan (akan terlihat di riwayat)..."
                        class="block w-full rounded-lg border px-3.5 py-2.5 text-sm text-pln-slate-900 placeholder:text-pln-slate-400 focus:outline-none focus:ring-2 {{ $errors->has('isi_catatan') ? 'border-status-cancel focus:ring-status-cancel/40' : 'border-pln-slate-200 focus:border-pln-navy-700 focus:ring-pln-navy-700/20' }}"
                    >{{ old('isi_catatan') }}</textarea>
                    @error('isi_catatan')
                        <p class="mt-1.5 text-sm text-status-cancel">{{ $message }}</p>
                    @enderror

                    <button
                        type="submit"
                        class="mt-3 flex items-center gap-2 rounded-lg bg-pln-navy-900 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-pln-navy-800"
                    >
                        <x-icon name="paper-airplane" class="h-4 w-4" />
                        Simpan Catatan
                    </button>
                </form>

                <div class="mt-6 border-t border-pln-slate-100 pt-6">
                    <h3 class="text-sm font-semibold text-pln-navy-900">Catatan Sebelumnya</h3>

                    @if ($reservasi->notes->isEmpty())
                        <p class="mt-3 text-sm text-pln-slate-400">Belum ada catatan untuk reservasi ini.</p>
                    @else
                        <div class="mt-3 space-y-3">
                            @foreach ($reservasi->notes as $note)
                                <x-cs-reservasi.note-item :note="$note" />
                            @endforeach
                        </div>
                    @endif
                </div>
            </x-card>

            {{-- Form Ubah Status --}}
            <x-card padding="p-6" id="ubah-status-reservasi">
                <x-slot:header>
                    <h2 class="flex items-center gap-2 font-display text-base font-semibold text-pln-navy-900">
                        <x-icon name="check-circle" class="h-5 w-5 text-pln-navy-700" />
                        Ubah Status Reservasi
                    </h2>
                </x-slot:header>

                @php
                    $transisiValid = $reservasi->status->transisiValidBerikutnya();
                @endphp

                @if (empty($transisiValid))
                    <x-empty-state
                        title="Status sudah final"
                        description="Reservasi dengan status \"{{ $reservasi->status->label() }}\" tidak dapat diubah lagi."
                    />
                @else
                    <form action="{{ route('cs.reservasi.status.update', $reservasi) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <p class="mb-3 text-sm text-pln-slate-600">Pilih status terbaru sesuai hasil review Anda.</p>

                        <div class="flex flex-wrap gap-2.5">
                            @foreach (['menunggu_review', 'perlu_datang', 'selesai_online', 'selesai', 'dibatalkan'] as $statusValue)
                                @php
                                    $statusEnum = \App\Enums\ReservasiStatus::from($statusValue);
                                    $bolehDipilih = in_array($statusEnum, $transisiValid, true);
                                @endphp

                                <x-cs-reservasi.status-select-button
                                    :status="$statusEnum"
                                    :checked="old('status') === $statusValue"
                                    :disabled="! $bolehDipilih"
                                />
                            @endforeach
                        </div>
                        @error('status')
                            <p class="mt-2 text-sm text-status-cancel">{{ $message }}</p>
                        @enderror

                        <div class="mt-4">
                            <label for="keterangan" class="mb-1.5 block text-sm font-medium text-pln-slate-900">
                                Keterangan (opsional)
                            </label>
                            <input
                                type="text"
                                name="keterangan"
                                id="keterangan"
                                maxlength="255"
                                value="{{ old('keterangan') }}"
                                placeholder="Contoh: Sudah dihubungi via telepon"
                                class="block w-full rounded-lg border px-3.5 py-2.5 text-sm text-pln-slate-900 placeholder:text-pln-slate-400 focus:outline-none focus:ring-2 {{ $errors->has('keterangan') ? 'border-status-cancel focus:ring-status-cancel/40' : 'border-pln-slate-200 focus:border-pln-navy-700 focus:ring-pln-navy-700/20' }}"
                            >
                            @error('keterangan')
                                <p class="mt-1.5 text-sm text-status-cancel">{{ $message }}</p>
                            @enderror
                        </div>

                        <button
                            type="submit"
                            class="mt-4 flex w-full items-center justify-center gap-2 rounded-lg bg-pln-navy-900 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-pln-navy-800 sm:w-auto"
                        >
                            <x-icon name="check" class="h-4 w-4" />
                            Perbarui Status
                        </button>
                    </form>
                @endif
            </x-card>

        </div>

        <div>
            <x-button href="{{ route('cs.reservasi.index') }}" variant="ghost" size="md">
                <svg viewBox="0 0 24 24" class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                </svg>
                Kembali ke Daftar Reservasi
            </x-button>
        </div>

    </div>

@endsection