@extends('layouts.dashboard')

@section('title', 'Ubah Layanan')
@section('page-title', 'Ubah Layanan')
@section('page-subtitle', 'Layanan > Kelola Layanan > Ubah Layanan')
@section('user-initial', 'A')
@section('user-name', 'Admin')
@section('user-role', 'Administrator')

@section('content')

    <div class="mx-auto max-w-3xl space-y-6">

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
            <x-slot:header>
                <h2 class="font-display text-base font-semibold text-pln-navy-900">Ubah Layanan: {{ $layanan->nama_layanan }}</h2>
            </x-slot:header>

            <form action="{{ route('admin.layanan.update', $layanan) }}" method="POST" novalidate>
                @csrf
                @method('PUT')

                <x-layanan.form :layanan="$layanan" />

                <div class="mt-6 flex items-center justify-between border-t border-pln-slate-100 pt-5">
                    <x-button href="{{ route('admin.layanan.index') }}" variant="ghost" size="md">
                        <x-icon name="x-mark" class="h-4 w-4" />
                        Batal
                    </x-button>
                    <x-button type="submit" variant="primary" size="md">
                        <x-icon name="check" class="h-4 w-4" />
                        Simpan Perubahan
                    </x-button>
                </div>
            </form>
        </x-card>

    </div>

@endsection