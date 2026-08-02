@extends('layouts.public')

@section('title', 'Detail Reservasi — ' . $reservasi->nomor_antrean)
@section('meta_description', 'Detail reservasi dan status layanan pelanggan PLN Anda.')

@section('content')

    <div class="border-b border-pln-slate-200 bg-pln-slate-100/60">
        <div class="mx-auto max-w-5xl px-4 py-6 sm:px-6 lg:px-8">
            <x-reservasi.breadcrumb :items="[
                ['label' => 'Beranda', 'href' => route('landing')],
                ['label' => 'Reservasi', 'href' => route('reservasi.create')],
                ['label' => 'Detail Reservasi'],
            ]" />

            <div class="mt-4">
                <h1 class="font-display text-2xl font-bold tracking-tight text-pln-navy-950 sm:text-3xl">
                    Detail Reservasi
                </h1>
                <p class="mt-1.5 text-sm text-pln-slate-600">
                    Simpan nomor antrean ini sebagai bukti reservasi Anda.
                </p>
            </div>
        </div>
    </div>

    <div class="mx-auto max-w-5xl px-4 py-8 sm:px-6 lg:px-8">

        @if (session('success'))
            <x-alert variant="success" title="Berhasil" dismissible class="mb-6">
                {{ session('success') }}
            </x-alert>
        @endif

        <div class="grid gap-6 lg:grid-cols-3">

            <div class="space-y-6 lg:col-span-2">

                <x-card>
                    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                        <div>
                            <p class="text-xs font-medium uppercase tracking-wider text-pln-slate-400">Nomor Antrean</p>
                            <p class="font-mono text-4xl font-bold text-pln-navy-900">{{ $reservasi->nomor_antrean }}</p>
                            <p class="mt-1 text-xs text-pln-slate-400">Kode Reservasi: {{ $reservasi->kode_reservasi }}</p>
                        </div>
                        <x-badge :variant="$reservasi->status->badgeVariant()" class="self-start text-sm sm:self-auto">
                            {{ $reservasi->status->label() }}
                        </x-badge>
                    </div>

                    <div class="mt-6 border-t border-pln-slate-100 pt-6">
                        <x-reservasi.status-stepper :status="$reservasi->status" />
                    </div>
                </x-card>

                <x-card>
                    <x-slot:header>
                        <h2 class="font-display text-base font-semibold text-pln-navy-900">Detail Reservasi</h2>
                    </x-slot:header>

                    <dl class="grid gap-x-6 gap-y-4 sm:grid-cols-2">
                        <div>
                            <dt class="text-xs font-medium uppercase tracking-wider text-pln-slate-400">Nama</dt>
                            <dd class="mt-1 text-sm font-medium text-pln-slate-900">{{ $reservasi->nama }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs font-medium uppercase tracking-wider text-pln-slate-400">Nomor HP</dt>
                            <dd class="mt-1 text-sm font-medium text-pln-slate-900">{{ $reservasi->nomor_hp }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs font-medium uppercase tracking-wider text-pln-slate-400">Email</dt>
                            <dd class="mt-1 text-sm font-medium text-pln-slate-900">{{ $reservasi->email ?? '-' }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs font-medium uppercase tracking-wider text-pln-slate-400">Jenis Layanan</dt>
                            <dd class="mt-1 text-sm font-medium text-pln-slate-900">{{ $reservasi->layanan->nama_layanan }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs font-medium uppercase tracking-wider text-pln-slate-400">Tanggal</dt>
                            <dd class="mt-1 text-sm font-medium text-pln-slate-900">
                                {{ $reservasi->jadwal->tanggal->translatedFormat('l, d F Y') }}
                            </dd>
                        </div>
                        <div>
                            <dt class="text-xs font-medium uppercase tracking-wider text-pln-slate-400">Jam Kedatangan</dt>
                            <dd class="mt-1 text-sm font-medium text-pln-slate-900">
                                {{ substr($reservasi->jadwal->jam_mulai, 0, 5) }} - {{ substr($reservasi->jadwal->jam_selesai, 0, 5) }}
                            </dd>
                        </div>
                        <div class="sm:col-span-2">
                            <dt class="text-xs font-medium uppercase tracking-wider text-pln-slate-400">Keluhan / Keterangan</dt>
                            <dd class="mt-1 text-sm leading-relaxed text-pln-slate-900">{{ $reservasi->keluhan }}</dd>
                        </div>
                    </dl>

                    @if ($reservasi->dokumen->isNotEmpty())
                        <div class="mt-5 border-t border-pln-slate-100 pt-5">
                            <p class="text-xs font-medium uppercase tracking-wider text-pln-slate-400">Dokumen Pendukung</p>
                            <ul class="mt-2 space-y-2">
                                @foreach ($reservasi->dokumen as $dokumen)
                                    <li class="flex items-center gap-2 text-sm text-pln-slate-700">
                                        <x-icon name="document-text" class="h-4 w-4 shrink-0 text-pln-slate-400" />
                                        {{ $dokumen->nama_file_asli }}
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </x-card>

                <x-card>
                    <x-slot:header>
                        <h2 class="font-display text-base font-semibold text-pln-navy-900">Catatan Petugas</h2>
                    </x-slot:header>

                    @if ($reservasi->notes->isEmpty())
                        <x-empty-state
                            title="Belum ada catatan"
                            description="Catatan dari Customer Service akan tampil di sini setelah reservasi Anda direview."
                        />
                    @else
                        <ul class="space-y-4">
                            @foreach ($reservasi->notes as $note)
                                <li class="rounded-lg bg-pln-slate-50 p-4">
                                    <p class="text-sm leading-relaxed text-pln-slate-700">{{ $note->isi_catatan }}</p>
                                    <p class="mt-2 text-xs text-pln-slate-400">
                                        {{ $note->created_at->translatedFormat('d F Y, H:i') }}
                                    </p>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </x-card>

            </div>

            <div class="space-y-6">
                <x-card>
                    <x-slot:header>
                        <h2 class="font-display text-base font-semibold text-pln-navy-900">Aksi</h2>
                    </x-slot:header>

                    <div class="space-y-3">
                        <x-button variant="ghost" size="md" class="w-full" disabled>
                            Ubah Jadwal
                        </x-button>
                        <x-button variant="ghost" size="md" class="w-full" disabled>
                            Batalkan Reservasi
                        </x-button>
                        <x-button href="tel:123" variant="primary" size="md" class="w-full">
                            <x-icon name="phone" class="h-4 w-4" />
                            Hubungi Customer Service
                        </x-button>
                    </div>

                    <p class="mt-3 text-xs text-pln-slate-400">
                        Ubah jadwal &amp; pembatalan tersedia pada sprint berikutnya.
                    </p>
                </x-card>

                <x-reservasi.bantuan-card />
            </div>

        </div>
    </div>

@endsection