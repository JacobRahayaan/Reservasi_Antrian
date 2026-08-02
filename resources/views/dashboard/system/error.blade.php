@extends('layouts.dashboard')

@section('title', 'Contoh Halaman Error')
@section('page-title', 'Contoh Halaman Error')

@section('content')

    <div class="space-y-6">

        <x-alert variant="danger" title="Terjadi kesalahan pada sistem">
            Halaman ini adalah contoh tampilan error/empty state yang dipakai sebagai acuan desain di seluruh dashboard. Belum terhubung ke exception handler sesungguhnya.
        </x-alert>

        <x-empty-state
            title="Halaman tidak ditemukan"
            description="Contoh tampilan untuk skenario 404/500 sebelum halaman error resmi dibangun pada sprint infrastruktur."
        >
            <x-slot:action>
                <x-button href="{{ route('landing') }}" variant="primary" size="sm">
                    Kembali ke Beranda
                </x-button>
            </x-slot:action>
        </x-empty-state>

    </div>

@endsection