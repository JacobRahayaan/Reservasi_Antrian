@extends('layouts.public')

@section('title', 'Buat Reservasi')
@section('meta_description', 'Isi formulir reservasi layanan pelanggan PLN. Pilih jenis layanan, tanggal, dan jam kedatangan Anda.')

@section('content')

    <div class="border-b border-pln-slate-200 bg-pln-slate-100/60">
        <div class="mx-auto max-w-6xl px-4 py-6 sm:px-6 lg:px-8">
            <x-reservasi.breadcrumb :items="[
                ['label' => 'Beranda', 'href' => route('landing')],
                ['label' => 'Reservasi', 'href' => route('reservasi.create')],
                ['label' => 'Buat Reservasi'],
            ]" />

            <div class="mt-4 flex flex-col gap-6 lg:flex-row lg:items-start lg:justify-between">
                <div>
                    <h1 class="font-display text-2xl font-bold tracking-tight text-pln-navy-950 sm:text-3xl">
                        Buat Reservasi
                    </h1>
                    <p class="mt-1.5 text-sm text-pln-slate-600">
                        Isi formulir berikut untuk melakukan reservasi layanan pelanggan PLN.
                    </p>
                </div>

                <x-reservasi.step-indicator :current="1" />
            </div>
        </div>
    </div>

    <div class="mx-auto max-w-6xl px-4 py-8 sm:px-6 lg:px-8">

        @if ($errors->any())
            <x-alert variant="danger" title="Periksa kembali data Anda" class="mb-6">
                <ul class="list-inside list-disc space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </x-alert>
        @endif

        <form
            action="{{ route('reservasi.store') }}"
            method="POST"
            enctype="multipart/form-data"
            class="grid gap-6 lg:grid-cols-3"
            novalidate
        >
            @csrf

            <div class="space-y-6 lg:col-span-2">
                <x-card>
                    <x-slot:header>
                        <h2 class="flex items-center gap-2 font-display text-base font-semibold text-pln-navy-900">
                            <x-icon name="pencil-square" class="h-5 w-5 text-pln-navy-700" />
                            Data Pemohon
                        </h2>
                    </x-slot:header>

                    <div class="space-y-5">

                        <div class="grid gap-5 sm:grid-cols-2">
                            <x-input
                                label="Nama Lengkap"
                                name="nama"
                                :value="old('nama')"
                                placeholder="Masukkan nama lengkap"
                                required
                                :error="$errors->first('nama')"
                            />

                            <x-input
                                label="Nomor HP"
                                name="nomor_hp"
                                :value="old('nomor_hp')"
                                placeholder="Contoh: 081234567890"
                                required
                                :error="$errors->first('nomor_hp')"
                            />
                        </div>

                        <x-input
                            label="Email (Opsional)"
                            name="email"
                            type="email"
                            :value="old('email')"
                            placeholder="Contoh: email@gmail.com"
                            :error="$errors->first('email')"
                        />

                        <div>
                            <label class="mb-2 block text-sm font-medium text-pln-slate-900">
                                Pilih Jenis Layanan <span class="text-status-cancel">*</span>
                            </label>

                            <div class="grid gap-3 sm:grid-cols-3">
                                @php
                                    $ikonLayanan = [
                                        'A' => ['icon' => 'bolt', 'bg' => 'bg-pln-amber-500'],
                                        'B' => ['icon' => 'document-text', 'bg' => 'bg-pln-navy-600'],
                                        'C' => ['icon' => 'wrench-screwdriver', 'bg' => 'bg-status-done'],
                                    ];
                                @endphp

                                @foreach ($layanans as $layanan)
                                    @php
                                        $ikon = $ikonLayanan[$layanan->kode_layanan] ?? ['icon' => 'bolt', 'bg' => 'bg-pln-navy-600'];
                                    @endphp

                                    <x-reservasi.layanan-radio-card
                                        name="layanan_id"
                                        :value="$layanan->id"
                                        :icon="$ikon['icon']"
                                        :icon-bg="$ikon['bg']"
                                        :title="$layanan->nama_layanan"
                                        :description="$layanan->deskripsi"
                                        :checked="(int) old('layanan_id') === $layanan->id"
                                        data-layanan-option
                                        required
                                    />
                                @endforeach
                            </div>

                            @error('layanan_id')
                                <p class="mt-1.5 text-sm text-status-cancel">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid gap-5 sm:grid-cols-2">
                            <x-input
                                label="Pilih Tanggal"
                                name="tanggal"
                                type="date"
                                id="tanggal"
                                data-tanggal-input
                                :value="old('tanggal')"
                                min="{{ now()->toDateString() }}"
                                required
                                :error="$errors->first('tanggal')"
                            />

                            <div class="w-full">
                                <label for="jadwal_id" class="mb-1.5 block text-sm font-medium text-pln-slate-900">
                                    Pilih Jam Kedatangan <span class="text-status-cancel">*</span>
                                </label>
                                <select
                                    name="jadwal_id"
                                    id="jadwal_id"
                                    data-jadwal-select
                                    required
                                    class="block w-full rounded-lg border px-3.5 py-2.5 text-sm text-pln-slate-900 focus:outline-none focus:ring-2 {{ $errors->has('jadwal_id') ? 'border-status-cancel focus:ring-status-cancel/40' : 'border-pln-slate-200 focus:border-pln-navy-700 focus:ring-pln-navy-700/20' }}"
                                >
                                    <option value="">Pilih jenis layanan &amp; tanggal dahulu</option>
                                </select>
                                @error('jadwal_id')
                                    <p class="mt-1.5 text-sm text-status-cancel">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div>
                            <div class="mb-1.5 flex items-center justify-between">
                                <label for="keluhan" class="block text-sm font-medium text-pln-slate-900">
                                    Keluhan / Keterangan <span class="text-status-cancel">*</span>
                                </label>
                                <span data-char-counter="keluhan" class="text-xs text-pln-slate-400">0 / 500</span>
                            </div>
                            <textarea
                                name="keluhan"
                                id="keluhan"
                                rows="4"
                                maxlength="500"
                                data-char-count-target="keluhan"
                                placeholder="Tuliskan keluhan atau keterangan secara detail..."
                                required
                                class="block w-full rounded-lg border px-3.5 py-2.5 text-sm text-pln-slate-900 placeholder:text-pln-slate-400 focus:outline-none focus:ring-2 {{ $errors->has('keluhan') ? 'border-status-cancel focus:ring-status-cancel/40' : 'border-pln-slate-200 focus:border-pln-navy-700 focus:ring-pln-navy-700/20' }}"
                            >{{ old('keluhan') }}</textarea>
                            @error('keluhan')
                                <p class="mt-1.5 text-sm text-status-cancel">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-pln-slate-900">
                                Upload Dokumen (Opsional)
                            </label>
                            <p class="mb-2 text-xs text-pln-slate-400">Format: PDF, JPG, PNG. Maksimal 2MB per file.</p>
                            <x-reservasi.file-upload name="dokumen" :maksimal-file="3" :error="$errors->first('dokumen')" />
                        </div>

                    </div>

                    <x-slot:footer>
                        <div class="flex items-center justify-between">
                            <x-button href="{{ route('landing') }}" variant="ghost" size="md">
                                <x-icon name="x-mark" class="h-4 w-4" />
                                Batal
                            </x-button>
                            <x-button type="submit" variant="primary" size="md">
                                Lanjutkan
                                <x-icon name="arrow-right" class="h-4 w-4" />
                            </x-button>
                        </div>
                    </x-slot:footer>
                </x-card>
            </div>

            <div class="space-y-6">
                <x-card>
                    <x-slot:header>
                        <h2 class="flex items-center gap-2 font-display text-base font-semibold text-pln-navy-900">
                            <x-icon name="clock" class="h-5 w-5 text-pln-navy-700" />
                            Informasi Penting
                        </h2>
                    </x-slot:header>

                    <div class="space-y-5">
                        <x-reservasi.info-item icon="clock" title="Datang Sesuai Jadwal">
                            Datang ke kantor PLN sesuai tanggal dan jam yang Anda pilih.
                        </x-reservasi.info-item>

                        <x-reservasi.info-item icon="ticket" title="Nomor Antrean">
                            Nomor antrean akan diterbitkan otomatis setelah reservasi berhasil.
                        </x-reservasi.info-item>

                        <x-reservasi.info-item icon="document-text" title="Review Keluhan">
                            Keluhan Anda akan direview oleh Customer Service.
                        </x-reservasi.info-item>

                        <x-reservasi.info-item icon="bolt" icon-bg="bg-status-done/10" icon-color="text-status-done" title="Selesai Online / Datang">
                            Jika dapat diselesaikan online, petugas akan menghubungi Anda. Jika perlu datang, mohon hadir sesuai jadwal.
                        </x-reservasi.info-item>
                    </div>
                </x-card>

                <x-reservasi.bantuan-card />
            </div>

        </form>
    </div>

@endsection

@push('scripts')
    <script>
        window.reservasiConfig = {
            jadwalTersediaUrl: @json(route('reservasi.jadwal-tersedia')),
            oldJadwalId: @json(old('jadwal_id')),
        };
    </script>
@endpush