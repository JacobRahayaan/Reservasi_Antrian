@extends('layouts.dashboard')

@section('title', 'Profil Saya')
@section('page-title', 'Profil Saya')
@section('page-subtitle', 'Dashboard > Profil Saya')
@section('user-initial', 'C')
@section('user-name', 'CS. Amanda')
@section('user-role', 'Customer Service')

@section('content')

    <div class="mx-auto max-w-2xl space-y-6">

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

        <x-alert variant="info" title="Simulasi tanpa Login">
            Karena sistem belum memiliki modul Login, halaman ini menampilkan dan mengubah data petugas
            aktif pertama di sistem ({{ $petugas->nama_petugas }}) sebagai simulasi "petugas yang sedang bekerja".
        </x-alert>

        <x-card padding="p-6">
            <div class="flex items-center gap-4">
                <span class="flex h-16 w-16 shrink-0 items-center justify-center rounded-full bg-pln-navy-900 text-xl font-semibold text-white">
                    {{ mb_substr($petugas->nama_petugas, 0, 2) }}
                </span>
                <div>
                    <p class="font-display text-lg font-bold text-pln-navy-950">{{ $petugas->nama_petugas }}</p>
                    <p class="text-sm text-pln-slate-500">Customer Service</p>
                </div>
            </div>

            <form action="{{ route('cs.profil.update') }}" method="POST" class="mt-6 space-y-5 border-t border-pln-slate-100 pt-6" novalidate>
                @csrf
                @method('PUT')

                <x-input
                    label="Nama"
                    name="nama_petugas"
                    :value="old('nama_petugas', $petugas->nama_petugas)"
                    required
                    :error="$errors->first('nama_petugas')"
                />

                <x-input
                    label="Email"
                    name="email"
                    type="email"
                    :value="old('email', $petugas->email)"
                    required
                    :error="$errors->first('email')"
                />

                <x-input
                    label="Nomor HP"
                    name="no_hp"
                    :value="old('no_hp', $petugas->no_hp)"
                    :error="$errors->first('no_hp')"
                />

                <div class="flex justify-end border-t border-pln-slate-100 pt-5">
                    <x-button type="submit" variant="primary" size="md">
                        <x-icon name="check" class="h-4 w-4" />
                        Simpan Perubahan
                    </x-button>
                </div>
            </form>
        </x-card>

    </div>

@endsection