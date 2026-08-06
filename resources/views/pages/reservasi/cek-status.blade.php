@extends('layouts.public')

@section('title', 'Cek Status Reservasi')
@section('meta_description', 'Cek status reservasi layanan pelanggan PLN Anda menggunakan nomor antrean dan nomor HP.')

@section('content')

    <div class="border-b border-pln-slate-200 bg-pln-slate-100/60">
        <div class="mx-auto max-w-6xl px-4 py-6 sm:px-6 lg:px-8">
            <x-reservasi.breadcrumb :items="[
                ['label' => 'Beranda', 'href' => route('landing')],
                ['label' => 'Cek Status Reservasi'],
            ]" />

            <div class="mt-4">
                <h1 class="font-display text-2xl font-bold tracking-tight text-pln-navy-950 sm:text-3xl">
                    Cek Status Reservasi
                </h1>
                <p class="mt-1.5 text-sm text-pln-slate-600">
                    Masukkan nomor antrean dan nomor HP yang Anda gunakan saat membuat reservasi.
                </p>
            </div>
        </div>
    </div>

    <div class="mx-auto max-w-2xl px-4 py-10 sm:px-6 lg:px-8">

        @if ($errors->any())
            <x-alert variant="danger" title="Reservasi tidak ditemukan" class="mb-6">
                <ul class="list-inside list-disc space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </x-alert>
        @endif

        <x-card padding="p-6">
            <x-slot:header>
                <h2 class="flex items-center gap-2 font-display text-base font-semibold text-pln-navy-900">
                    <x-icon name="ticket" class="h-5 w-5 text-pln-navy-700" />
                    Masukkan Data Reservasi
                </h2>
            </x-slot:header>

            <form action="{{ route('reservasi.cek-status.proses') }}" method="POST" class="space-y-5" novalidate>
                @csrf

                <x-input
                    label="Nomor Antrean"
                    name="nomor_antrean"
                    :value="old('nomor_antrean')"
                    placeholder="Contoh: A012"
                    hint="Nomor antrean yang diberikan setelah reservasi berhasil dibuat."
                    required
                    :error="$errors->first('nomor_antrean')"
                />

                <x-input
                    label="Nomor HP"
                    name="nomor_hp"
                    placeholder="Contoh: 081234567890"
                    hint="Nomor HP yang Anda isi saat membuat reservasi."
                    required
                    :error="$errors->first('nomor_hp')"
                />

                <x-button type="submit" variant="primary" size="lg" class="w-full">
                    <x-icon name="search" class="h-4 w-4" />
                    Cek Status Reservasi
                </x-button>
            </form>
        </x-card>

        <div class="mt-6 text-center">
            <p class="text-sm text-pln-slate-500">
                Belum pernah membuat reservasi?
                <a href="{{ route('reservasi.create') }}" class="font-semibold text-pln-navy-700 hover:text-pln-navy-900">
                    Buat Reservasi Baru
                </a>
            </p>
        </div>

        <div class="mt-6">
            <x-reservasi.bantuan-card />
        </div>

    </div>

@endsection