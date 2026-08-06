@extends('layouts.dashboard')

@section('title', 'Profil Saya')
@section('page-title', 'Profil Saya')
@section('page-subtitle', 'Dashboard > Profil Saya')
@section('user-initial', 'A')
@section('user-name', 'Admin')
@section('user-role', 'Administrator')

@section('content')

    <div class="mx-auto max-w-2xl space-y-6">

        <x-alert variant="info" title="Halaman ini bersifat informasi">
            Sistem belum memiliki modul Login, sehingga belum ada akun individual per-Admin yang dapat diubah.
            Informasi di bawah ini bersifat umum. Profil per-pengguna akan tersedia setelah modul Login dibangun.
        </x-alert>

        <x-card padding="p-6">
            <div class="flex items-center gap-4">
                <span class="flex h-16 w-16 shrink-0 items-center justify-center rounded-full bg-pln-navy-900 text-xl font-semibold text-white">
                    A
                </span>
                <div>
                    <p class="font-display text-lg font-bold text-pln-navy-950">Admin</p>
                    <p class="text-sm text-pln-slate-500">Administrator</p>
                </div>
            </div>

            <div class="mt-6 space-y-4 border-t border-pln-slate-100 pt-6">
                <x-reservasi.info-row icon="phone" label="Nomor Contact Center" :value="$pengaturan->nomor_contact_center" />
                <x-reservasi.info-row icon="envelope" label="Email Contact Center" :value="$pengaturan->email_contact_center ?? '-'" />
                <x-reservasi.info-row icon="map-pin" label="Alamat Kantor" :value="$pengaturan->alamat_kantor ?? '-'" />
            </div>

            <div class="mt-6 border-t border-pln-slate-100 pt-6">
                <p class="text-sm text-pln-slate-500">
                    Untuk mengubah informasi kantor di atas, buka menu
                    <a href="{{ route('admin.pengaturan.index') }}" class="font-semibold text-pln-navy-700 hover:text-pln-navy-900">Pengaturan Sistem</a>.
                </p>
            </div>
        </x-card>

    </div>

@endsection