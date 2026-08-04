@extends('layouts.dashboard')

@section('title', 'Tambah Jadwal')
@section('page-title', 'Tambah Jadwal')
@section('page-subtitle', 'Dashboard > Jadwal & Kuota > Tambah Jadwal')
@section('user-initial', 'A')
@section('user-name', 'Admin')
@section('user-role', 'Administrator')

@section('content')

    <div class="mx-auto max-w-2xl space-y-6">

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
                <h2 class="font-display text-base font-semibold text-pln-navy-900">Tambah Jadwal Baru</h2>
            </x-slot:header>

            <form action="{{ route('admin.jadwal.store') }}" method="POST" novalidate>
                @csrf

                <x-jadwal.form :layanans="$layanans" />

                <div class="mt-6 flex items-center justify-between border-t border-pln-slate-100 pt-5">
                    <x-button href="{{ route('admin.jadwal.index') }}" variant="ghost" size="md">
                        <x-icon name="x-mark" class="h-4 w-4" />
                        Batal
                    </x-button>
                    <x-button type="submit" variant="primary" size="md">
                        <x-icon name="check" class="h-4 w-4" />
                        Simpan Jadwal
                    </x-button>
                </div>
            </form>
        </x-card>

    </div>

@endsection