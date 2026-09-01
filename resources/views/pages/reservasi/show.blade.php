@extends('layouts.public')

@section('title', 'Detail Reservasi ' . $reservasi->nomor_antrean)
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

        @if (session('error'))
            <x-alert variant="danger" title="Tidak dapat diproses" dismissible class="mb-6 print:hidden">
                {{ session('error') }}
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
                </div>
            </x-card>

            {{-- Kartu Informasi Reservasi --}}
            <x-card padding="p-6">
                <x-slot:header>
                    <h2 class="flex items-center gap-2 font-display text-base font-semibold text-pln-navy-900">
                        <x-icon name="document-text" class="h-5 w-5 text-pln-navy-700" />
                        Informasi Reservasi
                    </h2>
                </x-slot:header>

                <div class="grid gap-x-6 gap-y-5 lg:grid-cols-2">
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
                </x-slot:header>

                @if ($reservasi->dokumen->isEmpty())
                    <x-empty-state
                        title="Belum ada dokumen"
                        description="Anda tidak mengunggah dokumen pendukung saat membuat reservasi ini."
                    />
                @else
                    <div class="space-y-2.5">
                        @foreach ($reservasi->dokumen as $dokumen)
                            <div class="flex items-center gap-3 rounded-lg border border-pln-slate-200 bg-white px-4 py-3">
                                <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-pln-navy-900/5 text-pln-navy-700">
                                    <x-icon name="document-text" class="h-4 w-4" />
                                </span>
                                <div class="min-w-0 flex-1">
                                    <p class="truncate text-sm font-medium text-pln-slate-900">{{ $dokumen->nama_file_asli }}</p>
                                    <p class="text-xs text-pln-slate-400">{{ \Illuminate\Support\Number::fileSize($dokumen->ukuran_file, precision: 1) }}</p>
                                </div>
                                <a
                                    href="{{ route('reservasi.dokumen.download', ['reservasi' => $reservasi, 'dokumen' => $dokumen]) }}"
                                    class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg text-pln-navy-700 transition hover:bg-pln-slate-100"
                                    aria-label="Unduh {{ $dokumen->nama_file_asli }}"
                                >
                                    <x-icon name="download" class="h-4 w-4" />
                                </a>
                            </div>
                        @endforeach
                    </div>
                @endif
            </x-card>

            {{-- Kartu Catatan Petugas --}}
            <x-card padding="p-6">
                <x-slot:header>
                    <h2 class="font-display text-base font-semibold text-pln-navy-900">Catatan Terakhir dari Petugas</h2>
                </x-slot:header>

                @if ($reservasi->notes->isEmpty())
                    <x-empty-state
                        title="Belum ada catatan"
                        description="Catatan dari Customer Service akan tampil di sini setelah reservasi Anda direview."
                    />
                @else
                    @foreach ($reservasi->notes as $note)
                        <p class="text-sm leading-relaxed text-pln-slate-700">{{ $note->isi_catatan }}</p>
                        <p class="mt-2 text-xs text-pln-slate-400">{{ $note->created_at->translatedFormat('d F Y, H:i') }}</p>
                    @endforeach
                @endif
            </x-card>

        </div>

        <div class="mt-6 print:hidden">
            <x-landing.help-banner />
        </div>

        <div class="mt-6 grid gap-6 lg:grid-cols-3 print:hidden">
            <div class="lg:col-span-2">
                <x-button href="{{ route('landing') }}" variant="ghost" size="md">
                    <svg viewBox="0 0 24 24" class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                    </svg>
                    Kembali ke Beranda
                </x-button>
            </div>

            {{-- Kartu Aksi: Ubah Jadwal & Batalkan Reservasi --}}
            <x-card>
                <x-slot:header>
                    <h2 class="font-display text-base font-semibold text-pln-navy-900">Aksi</h2>
                </x-slot:header>

                <div class="space-y-3">
                    @if ($reservasi->status->bisaDiubahJadwalOlehPelanggan())
                        <x-button href="{{ route('reservasi.ubah-jadwal.edit', $reservasi) }}" variant="ghost" size="md" class="w-full">
                            <x-icon name="calendar" class="h-4 w-4" />
                            Ubah Jadwal
                        </x-button>
                    @else
                        <x-button
                            variant="ghost"
                            size="md"
                            class="w-full"
                            disabled
                            title="Reservasi dengan status &quot;{{ $reservasi->status->label() }}&quot; tidak dapat diubah jadwalnya"
                        >
                            Ubah Jadwal
                        </x-button>
                    @endif

                    @if ($reservasi->status->bisaDibatalkanOlehPelanggan())
                        <button
                            type="button"
                            data-modal-target="modal-batalkan-reservasi"
                            class="inline-flex w-full items-center justify-center gap-2 rounded-lg border border-status-cancel/30 px-4 py-2.5 text-sm font-medium text-status-cancel transition hover:bg-status-cancel/5"
                        >
                            <x-icon name="x-mark" class="h-4 w-4" />
                            Batalkan Reservasi
                        </button>
                    @else
                        <x-button
                            variant="ghost"
                            size="md"
                            class="w-full"
                            disabled
                            title="Reservasi dengan status &quot;{{ $reservasi->status->label() }}&quot; tidak dapat dibatalkan"
                        >
                            Batalkan Reservasi
                        </x-button>
                    @endif

                    <x-button href="tel:123" variant="primary" size="md" class="w-full">
                        <x-icon name="phone" class="h-4 w-4" />
                        Hubungi Customer Service
                    </x-button>
                </div>
            </x-card>
        </div>

    </div>

    @if ($reservasi->status->bisaDibatalkanOlehPelanggan())
        <x-modal id="modal-batalkan-reservasi" title="Batalkan Reservasi" size="sm">
            <form id="form-batalkan-reservasi" action="{{ route('reservasi.batalkan', $reservasi) }}" method="POST">
                @csrf
                @method('DELETE')

                <p class="text-sm leading-relaxed text-pln-slate-600">
                    Reservasi <strong>{{ $reservasi->kode_reservasi }}</strong> dengan nomor antrean
                    <strong>{{ $reservasi->nomor_antrean }}</strong> akan dibatalkan dan tidak dapat dikembalikan.
                </p>

                <div class="mt-4">
                    <x-input
                        label="Konfirmasi Nomor HP"
                        name="nomor_hp_konfirmasi"
                        placeholder="Masukkan nomor HP yang digunakan saat reservasi"
                        required
                        :error="$errors->first('nomor_hp_konfirmasi')"
                    />
                </div>

                <div class="mt-4">
                    <label for="alasan" class="mb-1.5 block text-sm font-medium text-pln-slate-900">
                        Alasan pembatalan (opsional)
                    </label>
                    <textarea
                        name="alasan"
                        id="alasan"
                        rows="3"
                        maxlength="255"
                        placeholder="Contoh: Sudah tidak diperlukan"
                        class="block w-full rounded-lg border border-pln-slate-200 px-3.5 py-2.5 text-sm text-pln-slate-900 placeholder:text-pln-slate-400 focus:border-pln-navy-700 focus:outline-none focus:ring-2 focus:ring-pln-navy-700/20"
                    >{{ old('alasan') }}</textarea>
                </div>
            </form>

            <x-slot:footer>
                <button
                    type="button"
                    data-modal-close
                    class="rounded-lg border border-pln-slate-300 px-4 py-2 text-sm font-medium text-pln-slate-600 hover:bg-pln-slate-50"
                >
                    Batal
                </button>
                <button
                    type="submit"
                    form="form-batalkan-reservasi"
                    class="rounded-lg bg-status-cancel px-4 py-2 text-sm font-semibold text-white hover:opacity-90"
                >
                    Ya, Batalkan Reservasi
                </button>
            </x-slot:footer>
        </x-modal>
    @endif

@endsection