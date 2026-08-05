@extends('layouts.dashboard')

@section('title', 'Pengaturan Sistem')
@section('page-title', 'Pengaturan Sistem')
@section('page-subtitle', 'Dashboard > Pengaturan Sistem')
@section('user-initial', 'A')
@section('user-name', 'Admin')
@section('user-role', 'Administrator')

@section('content')

    <div class="mx-auto max-w-3xl space-y-6">

        @if (session('success'))
            <x-alert variant="success" title="Berhasil" dismissible>{{ session('success') }}</x-alert>
        @endif

        @if ($errors->any())
            <x-alert variant="danger" title="Periksa kembali data Anda">
                <ul class="list-inside list-disc space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </x-alert>
        @endif

        <x-card padding="p-6">
            <div>
                <h1 class="font-display text-xl font-bold text-pln-navy-950">Pengaturan Sistem</h1>
                <p class="mt-1 text-sm text-pln-slate-600">
                    Kelola informasi umum dan konfigurasi dasar sistem SIRA-PLN.
                </p>
            </div>
        </x-card>

        <form action="{{ route('admin.pengaturan.update') }}" method="POST" novalidate>
            @csrf
            @method('PUT')

            <x-card padding="p-6">
                <x-slot:header>
                    <h2 class="flex items-center gap-2 font-display text-base font-semibold text-pln-navy-900">
                        <x-icon name="building-office-2" class="h-5 w-5 text-pln-navy-700" />
                        Informasi Umum
                    </h2>
                </x-slot:header>

                <div class="space-y-5">
                    <x-input
                        label="Nama Aplikasi"
                        name="nama_aplikasi"
                        :value="old('nama_aplikasi', $pengaturan->nama_aplikasi)"
                        placeholder="Contoh: SIRA-PLN"
                        required
                        :error="$errors->first('nama_aplikasi')"
                    />

                    <div class="grid gap-5 sm:grid-cols-2">
                        <x-input
                            label="Nomor Contact Center"
                            name="nomor_contact_center"
                            :value="old('nomor_contact_center', $pengaturan->nomor_contact_center)"
                            placeholder="Contoh: 123"
                            required
                            :error="$errors->first('nomor_contact_center')"
                        />
                        <x-input
                            label="Email Contact Center"
                            name="email_contact_center"
                            type="email"
                            :value="old('email_contact_center', $pengaturan->email_contact_center)"
                            placeholder="Contoh: info@pln.co.id"
                            :error="$errors->first('email_contact_center')"
                        />
                    </div>

                    <div>
                        <label for="alamat_kantor" class="mb-1.5 block text-sm font-medium text-pln-slate-900">
                            Alamat Kantor Pelayanan
                        </label>
                        <textarea
                            name="alamat_kantor"
                            id="alamat_kantor"
                            rows="2"
                            maxlength="255"
                            placeholder="Contoh: Jl. Contoh No. 123, Kota Contoh, 12345"
                            class="block w-full rounded-lg border px-3.5 py-2.5 text-sm text-pln-slate-900 placeholder:text-pln-slate-400 focus:outline-none focus:ring-2 {{ $errors->has('alamat_kantor') ? 'border-status-cancel focus:ring-status-cancel/40' : 'border-pln-slate-200 focus:border-pln-navy-700 focus:ring-pln-navy-700/20' }}"
                        >{{ old('alamat_kantor', $pengaturan->alamat_kantor) }}</textarea>
                        @error('alamat_kantor')
                            <p class="mt-1.5 text-sm text-status-cancel">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </x-card>

            <x-card padding="p-6" class="mt-6">
                <x-slot:header>
                    <h2 class="flex items-center gap-2 font-display text-base font-semibold text-pln-navy-900">
                        <x-icon name="clock" class="h-5 w-5 text-pln-navy-700" />
                        Jam Operasional Default
                    </h2>
                </x-slot:header>

                <p class="mb-4 text-sm text-pln-slate-500">
                    Nilai ini dipakai sebagai acuan umum jam layanan kantor. Jam operasional per hari dan kuota per slot tetap dikelola melalui menu <strong>Jadwal &amp; Kuota</strong>.
                </p>

                <div class="grid gap-5 sm:grid-cols-2">
                    <x-input
                        label="Jam Buka"
                        name="jam_buka_default"
                        type="time"
                        :value="old('jam_buka_default', substr($pengaturan->jam_buka_default, 0, 5))"
                        required
                        :error="$errors->first('jam_buka_default')"
                    />
                    <x-input
                        label="Jam Tutup"
                        name="jam_tutup_default"
                        type="time"
                        :value="old('jam_tutup_default', substr($pengaturan->jam_tutup_default, 0, 5))"
                        required
                        :error="$errors->first('jam_tutup_default')"
                    />
                </div>
            </x-card>

            <x-card padding="p-6" class="mt-6">
                <x-slot:header>
                    <h2 class="flex items-center gap-2 font-display text-base font-semibold text-pln-navy-900">
                        <x-icon name="document-text" class="h-5 w-5 text-pln-navy-700" />
                        Batas Unggah Dokumen
                    </h2>
                </x-slot:header>

                <p class="mb-4 text-sm text-pln-slate-500">
                    Nilai referensi untuk kebijakan unggah dokumen pendukung pada Form Reservasi pelanggan.
                </p>

                <div class="grid gap-5 sm:grid-cols-2">
                    <x-input
                        label="Maksimal Ukuran per Dokumen (MB)"
                        name="maksimal_ukuran_dokumen_mb"
                        type="number"
                        min="1"
                        max="20"
                        :value="old('maksimal_ukuran_dokumen_mb', $pengaturan->maksimal_ukuran_dokumen_mb)"
                        required
                        :error="$errors->first('maksimal_ukuran_dokumen_mb')"
                    />
                    <x-input
                        label="Maksimal Jumlah Dokumen"
                        name="maksimal_jumlah_dokumen"
                        type="number"
                        min="1"
                        max="10"
                        :value="old('maksimal_jumlah_dokumen', $pengaturan->maksimal_jumlah_dokumen)"
                        required
                        :error="$errors->first('maksimal_jumlah_dokumen')"
                    />
                </div>
            </x-card>

            <div class="mt-6 flex justify-end">
                <x-button type="submit" variant="primary" size="md">
                    <x-icon name="check" class="h-4 w-4" />
                    Simpan Pengaturan
                </x-button>
            </div>
        </form>

    </div>

@endsection